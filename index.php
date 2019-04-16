<?php
declare(strict_types=1);
error_reporting(-1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');



require 'core/classes/Loader.php';
require 'vendor/autoload.php';

use Core\Classes\{
    Database,
    Loader ,
    Router,
    Session
};


$loader = new Loader();
$router = new Router();
Database::init();
Session::start();
$router->run();