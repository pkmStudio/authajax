<?php
/**
 * Все начинается здесь. Тут мы подключаем файл с маршрутами, 
 * затем перебираем массив и смотрим, если такой ключ есть,
 * то подключаем соответсвтующий контроллер и вызываем метод
 */

namespace app\core;

use app\core\View;


class Router
{
    protected $routes = [];
    protected $params = [];

    public function __construct()
    {
        // Подключаем файл с маршрутами.
        $routes = require 'app/config/routes.php';

        // Перебираем маршруты и отправляем их в метод add.
        foreach ($routes as $route => $params) {
            $this->add($route, $params);
        }
    }

    // Эта функция прогоняет массив routes и к ключам(ссылкам) добавляет символы. Для дальнейшего сравнения в match.
    public function add($route, $params)
    {
        $route = preg_replace('/{([a-z]+):([^\}]+)}/', '(?P<\1>\2)', $route);
        // Здесь мы добавляем к строке route по 2 символа с каждой стороны, что бы сделать из этого регулярное выражение.
        $route = '#^'.$route.'$#';

        // Затем мы маршруты пишем в массив класса $this->routes уже в регулярном виде.
        $this->routes[$route] = $params;
    }

    // Эта функция проверяет, есть ли такой маршрут вообще?
    public function match()
    {
        // В переменную пишем глобальный массив, в котором смотрим часть ссылки после nameProject/, он же маршрут. И удаляем "/" в начале и конце.
        $url = $_SERVER['REQUEST_URI'];
        $url = trim($url, '/');
        // Перебираем массив маршрутов(ссылок) уже в регулярном виде, которые есть у нас на сайте. 
        foreach ($this->routes as $route => $params) {
            // Если находим совпадение, то пишем параметры в перменную $params и возвращаем true.
            if (preg_match($route, $url, $matches)) {

                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        if (is_numeric($match)) {
                            $match = (int) $match;
                        }
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }

        // Если маршрут не найден возвращаем false.
        return false;
    }

    // Эта функция  создает нужный класс на основе маршрута и вызывает нужный метод.
    public function run()
    {
        // Вызываем метод, который проверит, есть ли нужный маршрут у нас в наличии.
        if($this->match()) {
            // Если вернулось true.

            // Делаем первую букву, названия контроллера, заглавной.
            $path = ucfirst($this->params['controller']);
            // Прописывааем полный путь к контроллеру.
            $path = "app\controllers\\{$path}Controller";

        } else {
            // Если вернулось false.
            View::errorCode(404);
        }

        // Делаем проверку на существование контроллера.
        if (class_exists($path)) {
            //  Если вернулось true.

            // То присваиваем название метода в переменную.
            $action = $this->params['action'] . 'Action';
        } else {
            // Если вернулось false.
            View::errorCode(404);
        }

        // Делаем проверку на существование метода у контроллера.
        if (method_exists($path, $action)) {
            // Если вернулось true.

            // Создаем объект, передаем параметры (Имя контроллера и метод). Далее вызываем метод.
            $controller = new $path($this->params);
            $controller->$action();
        } else {
            // Если вернулось false.
            View::errorCode(404);
        }
        
    }
}
