<?php

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Src\Config\MessageQueue;

$messageQueue = new MessageQueue();
$messageQueue->processQueue();
