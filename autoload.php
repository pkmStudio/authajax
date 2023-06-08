<?php

// Автозазгрузчик классов.
spl_autoload_register(function($class){
   // заменяем обратный слеш на обычный.
   $path = str_replace('\\', '/', $class . '.php');
   // Если класс найден, то подклчаем путь.
   if (file_exists($path)) {
      require $path;
   }
});