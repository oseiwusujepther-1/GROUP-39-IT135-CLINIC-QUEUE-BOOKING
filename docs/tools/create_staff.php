<?php
// Usage: php tools/create_staff.php admin admin123 "Clinic Admin"
require __DIR__ . '/../src/Database.php';
$config = require __DIR__ . '/../config/config.php';
[$script, $user, $pass] = $argv + [null, null, null];
$name = $argv[3] ?? $user;
if (!$user || !$pass) exit("Usage: php tools/create_staff.php <username> <password> [\"Full Name\"]\n");

$db = Database::connect($config['db']);
$db->prepare(
    "INSERT INTO staff (full_name, username, password_hash, role) VALUES (?,?,?, 'admin')
     ON DUPLICATE KEY UPDATE password_hash=VALUES(password_hash), full_name=VALUES(full_name), is_active=1"
)->execute([$name, $user, password_hash($pass, PASSWORD_DEFAULT)]);
echo "Staff account '$user' is ready.\n";

