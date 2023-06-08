<?php

// Этот файл выводит нам все ошибки, которые могут возникнуть в коде. 
// Этакий ручной Дебаггер

ini_set('display_errors', 1);
error_reporting(E_ALL);

function debug($str) {
   echo '<pre>';
   var_dump($str);
   echo '</pre>';
   exit;
}