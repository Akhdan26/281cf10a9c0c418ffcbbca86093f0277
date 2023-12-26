<?php
require 'vendor/autoload.php';

use Src\Config\Database;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dbConnection = Database::getConnection();