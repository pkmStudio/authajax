<?php
// Батя всех контроллеров, здесь описан основной функционал взаимодействия пользователя с сайтом. 

namespace app\core;

use app\core\View;

abstract class Controller
{
    public $route;
    public $acl;
    public $view;
    public $model;

    public function __construct($route) {
        // Записываем маршрут от страницы, на которой сидим и доп параметры. 
        $this->route = $route;
        //Создаем объект на основе класса View и передаем информацию, о том что нам отобразить в класс View
        $this->view = new View($route);
        // Создаем объект модели, если такая есть. 
        $this->model = $this->loadModel($route['controller']);
    }

    // Эта функция загружает нужную модель по имени контроллера.
    public function loadModel($name)
    {
        $path = ucfirst($name);
        $path = "app\models\\{$path}Model";

        // Проверяем на наличие класса модели с таким именем.
        if (class_exists($path)) {
            // Если вернулось true - создаем объект.
            return new $path;
        } 
        else {
            // Если вернулось false.
            View::errorCode(404);
        }
    }

}
