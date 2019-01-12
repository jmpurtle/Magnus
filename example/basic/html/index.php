<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../src/autoload.php';

$logger = new \Loggers\ScreenLogger();

$app = new \Magnus\Core\Application('foo', $logger);
$app->run();
