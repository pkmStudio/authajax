<?php

namespace app\core;


class View
{
    public $path;
    public $route;
    public $layout = 'default';




    public function __construct($route) {
        // ? Когда поймешь зачем эта строчка, вернись и напиши.
        $this->route = $route;

        // Формируем путь к странице отображения
        $this->path = $route['controller'] . '/' . $route['action'];
    }

    // Эта функция выводит ошибки
    public static function errorCode($code)
    {
        $path = 'app/views/errors/' . $code . '.php';
        http_response_code($code);
        if (file_exists($path)) {
            require $path;
        }
        exit;
    }
    
    // Эта функция собирает страницу для показа. 
    public function render($title, $vars = [])
    {
        $path = 'app/views/' . $this->path. '.php';

        // Разворачиваем массив на переменные.
        extract($vars);

        // Проверяем на существование файла тела страницы.
        if (file_exists($path)) {
            // Если true подключаем его.
            ob_start();
            require $path;
            $content = ob_get_clean();
            require 'app/views/layouts/' . $this->layout . '.php';
        } else {
            // Если false - ошибка.
            echo 'Вид не найден';
        }
    }

    // Эта функция переводит пользователя на другую страницу.
    public function redirectServer($url)
    {
        header('Location: ' . $url);
    }    
    
    // Эта функция по окончанию AJAX переводит пользователя на другую страницу.
    public function redirectAJAX($url)
    {
        exit (json_encode(['url' => $url]));
    }

    // Эта функция по окончанию AJAX отправляет отчет.
    public function message($result)
    {
        exit (json_encode($result));
    }
}

