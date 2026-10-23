<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($subject) ?></title>
</head>

<body style="margin:0; padding:0; background:#154a58; font-family:'Josefin Sans', Arial, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#154a58;">
        <tr>
            <td align="center" style="padding:24px 12px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:520px;">
                    <tr>
                        <td align="center" style="padding-bottom:16px;">
                            <img src="<?= e(url('images/logo3-light.png')) ?>" alt="Sunnemeerten" width="160" style="display:block;">
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#2A5E47; border-radius:16px; padding:24px; color:#f8fafc; font-size:16px; line-height:1.5;">
                            <?= $content ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding-top:16px; color:#FFE399; font-size:12px;">
                            Je ontvangt deze mail van de Sunnemeerten app. <a href="<?= e(url()) ?>" style="color:#FFE399;"><?= e(url()) ?></a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
