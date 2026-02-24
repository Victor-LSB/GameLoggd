<?php
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
// config/config_api.php
define('RAWG_API_KEY', $_ENV['RAWG_API_KEY']);
?>