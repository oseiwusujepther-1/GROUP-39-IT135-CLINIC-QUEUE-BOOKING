<?php

declare(strict_types=1);

require __DIR__ . '/Database.php';

$config = require __DIR__ . '/../config/config.php';
$db = Database::connect($config['db']);

if (!isset($_SESSION)) {
    session_start();
}
