<?php
require __DIR__ . '/../src/bootstrap.php';
require_patient();

$st = $db->prepare('SELECT passport_photo FROM patients WHERE patient_id=? AND is_active=1');
$st->execute([(int)$_SESSION['patient_id']]);
$photoName = $st->fetchColumn();

if (!is_string($photoName) || !preg_match('/\A[a-f0-9]{32}\.(?:jpg|png|webp)\z/', $photoName)) {
    http_response_code(404);
    exit;
}

$photoPath = dirname(__DIR__) . '/storage/patient-photos/' . $photoName;
if (!is_file($photoPath)) {
    http_response_code(404);
    exit;
}

$mimeType = (new finfo(FILEINFO_MIME_TYPE))->file($photoPath);
if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp'], true)) {
    http_response_code(404);
    exit;
}

header('Content-Type: ' . $mimeType);
header('Content-Disposition: inline');
header('Cache-Control: private, no-store');
header('X-Content-Type-Options: nosniff');
header("Content-Security-Policy: default-src 'none'; sandbox");
readfile($photoPath);

