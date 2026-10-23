<?php

const MAIL_PATH = ROOT_PATH . '/storage/mails';

function url(string $query = ''): string
{
    return rtrim(config('app_url'), '/') . '/' . $query;
}

// Verstuurt een mail met een template uit resources/views/emails. Een mislukte mail stopt de pagina niet.
function sendMail(string $to, string $subject, string $template, array $data = []): bool
{
    $html = renderMail($template, $data + ['subject' => $subject]);

    try {
        if (config('mail')['driver'] === 'smtp') {
            smtpSend($to, $subject, buildMessage($to, $subject, $html));
        } else {
            logMail($to, $subject, $html);
        }
        return true;
    } catch (Throwable $exception) {
        error_log('Mail naar ' . $to . ' mislukt: ' . $exception->getMessage());
        return false;
    }
}

function renderMail(string $template, array $data): string
{
    extract($data);

    ob_start();
    require VIEW_PATH . '/emails/' . $template . '.view.php';
    $content = ob_get_clean();

    ob_start();
    require VIEW_PATH . '/emails/layout.view.php';
    return ob_get_clean();
}

function htmlToText(string $html): string
{
    $html = preg_replace('/<a [^>]*href="([^"]+)"[^>]*>(.*?)<\/a>/is', '$2: $1', $html);
    $html = preg_replace('/<(br|\/p|\/h1|\/h2|\/tr)[^>]*>/i', "\n", $html);
    $text = html_entity_decode(strip_tags($html), ENT_QUOTES, 'UTF-8');
    return trim(preg_replace("/\n\s*\n\s*/", "\n\n", preg_replace('/[ \t]+/', ' ', $text)));
}

// Lokaal worden mails niet verstuurd maar opgeslagen, zodat je ze kunt bekijken op ?page=mailbox
function logMail(string $to, string $subject, string $html): void
{
    if (!is_dir(MAIL_PATH)) {
        mkdir(MAIL_PATH, 0755, true);
    }

    $file = MAIL_PATH . '/' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.json';
    file_put_contents($file, json_encode([
        'to' => $to,
        'subject' => $subject,
        'html' => $html,
        'created_at' => now(),
    ]));
}

function buildMessage(string $to, string $subject, string $html): string
{
    $mail = config('mail');
    $boundary = 'sunnemeerten_' . bin2hex(random_bytes(8));
    $domain = substr(strrchr($mail['from_email'], '@'), 1);

    $headers = [
        'Date: ' . date('r'),
        'From: =?UTF-8?B?' . base64_encode($mail['from_name']) . "?= <{$mail['from_email']}>",
        "To: <$to>",
        'Subject: =?UTF-8?B?' . base64_encode($subject) . '?=',
        'Message-ID: <' . bin2hex(random_bytes(16)) . "@$domain>",
        'MIME-Version: 1.0',
        "Content-Type: multipart/alternative; boundary=\"$boundary\"",
    ];

    $parts = '';
    foreach (['text/plain' => htmlToText($html), 'text/html' => $html] as $type => $body) {
        $parts .= "--$boundary\r\n"
            . "Content-Type: $type; charset=UTF-8\r\n"
            . "Content-Transfer-Encoding: quoted-printable\r\n\r\n"
            . quoted_printable_encode($body) . "\r\n";
    }

    return implode("\r\n", $headers) . "\r\n\r\n" . $parts . "--$boundary--";
}

// Een kleine SMTP-client, zodat er geen extra pakketten nodig zijn
function smtpSend(string $to, string $subject, string $message): void
{
    $mail = config('mail');
    $address = ($mail['encryption'] === 'ssl' ? 'ssl://' : 'tcp://') . $mail['host'] . ':' . $mail['port'];
    $socket = stream_socket_client($address, $errorCode, $errorMessage, 15);
    if (!$socket) {
        throw new RuntimeException("Kan geen verbinding maken met $address: $errorMessage");
    }
    stream_set_timeout($socket, 15);

    $read = function () use ($socket): string {
        $response = '';
        while (($line = fgets($socket, 515)) !== false) {
            $response .= $line;
            if (($line[3] ?? ' ') === ' ') {
                break;
            }
        }
        return $response;
    };
    $command = function (string $line, array $expected) use ($socket, $read): void {
        fwrite($socket, $line . "\r\n");
        $response = $read();
        if (!in_array((int) substr($response, 0, 3), $expected)) {
            throw new RuntimeException('SMTP gaf een fout: ' . trim($response));
        }
    };

    $read();
    $hostname = parse_url(config('app_url'), PHP_URL_HOST) ?: 'localhost';
    $command("EHLO $hostname", [250]);

    if ($mail['encryption'] === 'tls') {
        $command('STARTTLS', [220]);
        stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
        $command("EHLO $hostname", [250]);
    }
    if ($mail['username'] !== '') {
        $command('AUTH LOGIN', [334]);
        $command(base64_encode($mail['username']), [334]);
        $command(base64_encode($mail['password']), [235]);
    }

    $command("MAIL FROM:<{$mail['from_email']}>", [250]);
    $command("RCPT TO:<$to>", [250, 251]);
    $command('DATA', [354]);
    // Regels die met een punt beginnen krijgen een extra punt, anders ziet de server ze als einde van de mail
    $command(preg_replace('/^\./m', '..', $message) . "\r\n.", [250]);
    $command('QUIT', [221]);
    fclose($socket);
}
