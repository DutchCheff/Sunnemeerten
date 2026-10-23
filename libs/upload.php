<?php

const IMAGE_TYPES = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/gif' => 'gif',
    'image/webp' => 'webp',
];

// Zet $_FILES['veld'] met multiple om naar een lijst van losse bestanden
function uploadedFiles(string $field): array
{
    if (empty($_FILES[$field]['name'])) {
        return [];
    }

    $files = [];
    foreach ((array) $_FILES[$field]['name'] as $index => $name) {
        $file = [
            'name' => $name,
            'tmp_name' => ((array) $_FILES[$field]['tmp_name'])[$index],
            'error' => ((array) $_FILES[$field]['error'])[$index],
            'size' => ((array) $_FILES[$field]['size'])[$index],
        ];
        if ($file['error'] !== UPLOAD_ERR_NO_FILE) {
            $files[] = $file;
        }
    }

    return $files;
}

// Controleert een upload en geeft een foutmelding terug, of null als het bestand goed is
function validateImage(array $file): ?string
{
    $maxMb = config('upload')['max_mb'];

    if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE || $file['size'] > $maxMb * 1024 * 1024) {
        return "{$file['name']} is groter dan {$maxMb} MB.";
    }
    if ($file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
        return "{$file['name']} kon niet worden geüpload.";
    }
    if (!isset(IMAGE_TYPES[mime_content_type($file['tmp_name'])]) || getimagesize($file['tmp_name']) === false) {
        return "{$file['name']} is geen foto. Alleen JPG, PNG, GIF en WEBP zijn toegestaan.";
    }

    return null;
}

// Slaat een gecontroleerde foto op met een zelfgekozen naam en geeft het pad terug (bijv. uploads/ab12.jpg)
function storeImage(string $tmpName): string
{
    $extension = IMAGE_TYPES[mime_content_type($tmpName)];
    $filename = bin2hex(random_bytes(16)) . '.' . $extension;
    $target = UPLOAD_PATH . '/' . $filename;

    if (!is_dir(UPLOAD_PATH)) {
        mkdir(UPLOAD_PATH, 0755, true);
    }

    if (!resizeImage($tmpName, $target, $extension)) {
        if (is_uploaded_file($tmpName)) {
            move_uploaded_file($tmpName, $target);
        } else {
            copy($tmpName, $target);
        }
    }

    return 'uploads/' . $filename;
}

// Verkleint grote foto's en haalt daarbij metadata zoals GPS-locatie weg. Werkt alleen met de GD extensie.
function resizeImage(string $source, string $target, string $extension): bool
{
    if (!extension_loaded('gd') || $extension === 'gif') {
        return false;
    }

    $image = match ($extension) {
        'jpg' => @imagecreatefromjpeg($source),
        'png' => @imagecreatefrompng($source),
        'webp' => @imagecreatefromwebp($source),
    };
    if (!$image) {
        return false;
    }

    if ($extension === 'jpg' && function_exists('exif_read_data')) {
        $orientation = @exif_read_data($source)['Orientation'] ?? 1;
        $image = match ($orientation) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => $image,
        };
    }

    $maxWidth = config('upload')['max_width'];
    if (imagesx($image) > $maxWidth) {
        $image = imagescale($image, $maxWidth);
    }
    imagesavealpha($image, true);

    return match ($extension) {
        'jpg' => imagejpeg($image, $target, 82),
        'png' => imagepng($image, $target, 8),
        'webp' => imagewebp($image, $target, 82),
    };
}

function deleteImage(string $path): void
{
    $file = UPLOAD_PATH . '/' . basename($path);
    if (is_file($file)) {
        unlink($file);
    }
}
