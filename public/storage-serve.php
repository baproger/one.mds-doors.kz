<?php
$file = $_GET['f'] ?? '';

if (!preg_match('/^[a-zA-Z0-9\/_\-\.]+$/', $file) || str_contains($file, '..')) {
    http_response_code(403); exit;
}

$path = dirname(__DIR__) . '/storage/app/public/' . $file;

if (!file_exists($path) || !is_file($path)) {
    http_response_code(404); exit;
}

$mimes = [
    'png'  => 'image/png',
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'gif'  => 'image/gif',
    'webp' => 'image/webp',
    'svg'  => 'image/svg+xml',
];
$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

header('Content-Type: ' . ($mimes[$ext] ?? 'application/octet-stream'));
header('Content-Length: ' . filesize($path));
header('Cache-Control: public, max-age=31536000');
readfile($path);