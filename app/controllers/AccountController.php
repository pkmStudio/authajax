<?php

namespace app\controllers;

use app\core\Controller;

class accountController extends Controller
{
   public function loginAction()
   {
      //! Если форма не пустая.
      if (!empty($_POST)) {
         $result = $this->model->loginModel();

         if (isset($result['url'])) {
            $this->view->redirectAJAX($result['url']);
         } else {

            $this->view->message($result);
         }
      }

      if (isset($_SESSION['authorize']['login']) || isset($_COOKIE['loginpreview'])) {
         $this->view->redirectServer('/account');
      }
      
      //echo 'Страница Логинации.';
      $this->view->render('Вход');
   }

   public function logoutAction()
   {
      $result = $this->model->logoutModel();
      if (isset($result['url'])) {
         $this->view->redirectServer($result['url']);
      }
   }

   public function registerAction()
   {
      //! Если форма не пустая.
      if (!empty($_POST)) {
         $result = $this->model->registerModel();

         if (isset($result['url'])) {
            $this->view->redirectAJAX($result['url']);
         } else {
            $this->view->message($result);
         }
      }

      if (isset($_SESSION['authorize']['login']) || isset($_COOKIE['loginpreview'])) {
         $this->view->redirectServer('/account');
      }

      //echo 'Страница регистрации.';
      $this->view->render('Новый пользователь');
   }

   public function accountAction()
   {
      //echo 'Страница ЛК.';
      $this->view->render('Личный кабинет');
   }
}
