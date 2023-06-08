<?php
// Этот файл выводит нам все ошибки, которые могут возникнуть в коде. Этакий ручной Debugger.
require 'app/lib/Dev.php';
// Это автозагрузчик Классов.
require 'autoload.php';

use app\core\Router;


session_start();
$router = new Router;

$router->run();

