<?php

session_start();

define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASSWORD", "");
define("DB_DBNAME", "colony24"); // A DEFINIR

require __DIR__.'/library/helpers.php';
require __DIR__.'/controllers/front_controller.php';
require __DIR__ .'/controllers/back_controller.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$method = $_SERVER['REQUEST_METHOD'];