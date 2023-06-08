<?php

namespace app\models;

use app\core\Model;

class accountModel extends Model
{
	// Модуль шифрования пароля
	public function getPassword($userPassword) {
		$salt = 'dfk65465lgF6889FJKs';
		$userPassword = md5($salt . $userPassword . $salt);
		return $userPassword;
	}

	// Модуль проверки попыток (Защита от Брута)
	public function сheckingAttempts()
	{
		$IP=$_SERVER['REMOTE_ADDR']; // Узнали АйПи

		$params = ['IP' => $IP];
		$result = $this->row("SELECT * FROM `LC` WHERE `IP` = :IP", $params); // Делаем запрос
		$valueError = $result ? $result[0]->loginCounter : 1;

		// Если такого еще нет
		if (!$result) {
			$params = ['IP' => $IP, 'loginCounter' => $valueError, 'time' => time()];
			$this->query("INSERT INTO `LC` (`IP`, `loginCounter`, `time`) VALUE (:IP, :loginCounter, :time)", $params);

			$result = ['status' => 'Error', 'message' => 'Мы не нашли такого пользователя. Попробуйте еще раз.'];
			return $result;
		}

		// Если такой уже есть, но не исчерпаны попытки
		if ($result && ($valueError < 9) && ($result[0]->time < time())) {
			$valueError += 1;
			$params = ['IP' => $IP, 'loginCounter' => $valueError, 'time' => time()];
			$this->query("UPDATE `LC` SET `loginCounter` = :loginCounter, `time` = :time WHERE `IP` = :IP", $params);

			$result = ['status' => 'Error', 'message' => 'Мы не нашли такого пользователя. Попробуйте еще раз. Попыток осталось: ' . (10 - $valueError)];
			return $result;
		}

		// Если исчерпаны попытки
		if ($result && ($valueError == 9)) {
			$params = ['IP' => $IP, 'loginCounter' => 0, 'time' => time() + 120];
			$this->query("UPDATE `LC` SET `loginCounter` = :loginCounter, `time` = :time WHERE `IP` = :IP", $params);

			$result = ['status' => 'Error', 'message' => 'Мы не нашли такого пользователя. Количество попыток исчерпано. Попробуйте через 2 минуты.'];
			return $result;
		}

		// Если уже исчерпаны попытки и время еще не вышло
		if ($result && ($result[0]->time > time())) {
			$result = ['status' => 'Error', 'message' => 'Количество попыток исчерпано. Попробуйте позже.'];
			return $result;
		}
	}

	// Модуль при входе / авторизации
	public function loginModel()
	{
		$userLogin = trim(filter_var($_POST['login'], FILTER_SANITIZE_SPECIAL_CHARS));
		$userPassword = trim(filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS));

		// Начинаем проверку формы
		if (empty($userLogin)) {
			// Поле Логин
			$result = ['status' => 'Error', 'message' => 'Нельзя оставлять поле "Логин" пустым'];
			return $result;

		} elseif (strlen($userLogin) < 2) {
			$result = ['status' => 'Error', 'message' => 'В поле "Логин" должно быть не менее 2 символов'];
			return $result;
		}

		if (empty($userPassword)) {
			// Поле пароля
			$result = ['status' => 'Error', 'message' => 'Поле "Пароль" и нельзя оставлять пустым'];
			return $result;

		} elseif (strlen($userPassword) <= 6) {		
			$result = ['status' => 'Error', 'message' => 'Поле "Пароль" должно содержать не менее 6 символов'];
			return $result;
		}

		// Если все нормально
		$userPassword = $this->getPassword($userPassword); // шифруем пароль
		$params = ['login' => $userLogin, 'password' => $userPassword];
		$result = $this->row("SELECT * FROM `users` WHERE `login` = :login AND `password` = :password", $params); // Проверяем есть ли такой

		$IP=$_SERVER['REMOTE_ADDR']; // Узнали АйПи
		$params = ['IP' => $IP];
		$time = $this->column("SELECT `time` FROM `LC` WHERE `IP` = :IP", $params);
		$time = $time ? $time : 0;

		// Проверили, что такой есть и что он не в блоке.
		if ($result && ($time < time())) {
			setcookie('loginpreview', $userLogin, time() + 60*60*24*30, "/");
			$_SESSION['authorize']['login'] = $userLogin;	

			$_SESSION['authorize']['accountInfo'] = $result[0];
			$account = $this->accountModel();

			$result = ['status' => 'Done', 'accountInfo' => $account];
			return $result;
			
		} else {
			$result = $this->сheckingAttempts();
			return $result;
		}
		
	}

	// Модуль при выходе из Личного Кабинета
	public function logoutModel()
	{
		setcookie('loginpreview', '', time() - 60*60*24*365, "/");
		unset($_COOKIE['loginpreview']);
		unset($_SESSION['authorize']); 
		session_destroy();

		$result = ['status' => 'Done', 'url' => 'login'];
		return $result;
	}

	// Модуль при регистрации
	public function registerModel()
	{
		$userName = trim(filter_var($_POST['userName'], FILTER_SANITIZE_SPECIAL_CHARS));
		$userBirthDay = $_POST['birthDay'];
		$userImage = ($_FILES['image']['name'] !== '') ? 'public/src/img/' . time() . $_FILES['image']['name'] : 'public/src/img/Logo.png';
		$userLogin = trim(filter_var($_POST['login'], FILTER_SANITIZE_SPECIAL_CHARS));
		$userPassword = trim(filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS));
		$userRepeatPassword = trim(filter_var($_POST['repeatPassword'], FILTER_SANITIZE_SPECIAL_CHARS));

		// Начинаем проверку формы
		if (empty($userName)) {
			// Поле Имя
			$result = ['status' => 'Error', 'message' => 'Нельзя оставлять поле "Имя" пустым'];
			return $result;

		} elseif (strlen($userName) < 2) {
			$result = ['status' => 'Error', 'message' => 'В поле "Имя" должно быть больше 2-х символов'];
			return $result;
		}

		if (empty($userLogin)) {
			// Поле Логин
			$result = ['status' => 'Error', 'message' => 'Нельзя оставлять поле "Логин" пустым'];
			return $result;

		} elseif (strlen($userLogin) < 2) {
			$result = ['status' => 'Error', 'message' => 'В поле "Логин" должно быть больше 2-х символов'];
			return $result;

		} elseif ($this->column("SELECT `login` FROM `users` WHERE `login` = :login", ['login' => $userLogin])) { 
			$result = ['status' => 'Error', 'message' => 'Аккаунт с таким Логином уже существует'];
			return $result;
		}

		if (empty($userPassword) || empty($userRepeatPassword)) {
			// Поле пароля
			$result = ['status' => 'Error', 'message' => 'Поля "Пароль" и "Повторить пароль" нельзя оставлять пустыми'];
			return $result;

		} elseif (strlen($userPassword) <= 6) {
			$result = ['status' => 'Error', 'message' => 'Поле "Пароль" должно содержать от 6 символов'];
			return $result;

		} elseif ($userPassword !== $userRepeatPassword) {
			$result = ['status' => 'Error', 'message' => 'Поля "Пароль" и "Повторить пароль" не совпадают'];
			return $result;

		}

		// Загружаем фотку
		$saveImage = move_uploaded_file($_FILES['image']['tmp_name'], $userImage);
		if ($_FILES['image']['tmp_name'] !== '' && !$saveImage) {
			$result = ['status' => 'Error', 'message' => 'Ошибка при загрузке аватарки'];
			return $result;
		}

		// Если все нормально
		$userPassword = $this->getPassword($userPassword);
		$params = ['name' => $userName, 
								'login' => $userLogin, 
								'password' => $userPassword, 
								'birthday' => $userBirthDay, 
								'image' => $userImage];

		$this->query("INSERT INTO `users` (`name`, `login`, `password`, `birthday`, `image`) VALUE (:name, :login, :password, :birthday, :image)", $params);
		
		$result = ['status' => 'Done', 'url' => '/login'];
		return $result;
	}
   
	public function accountModel()
	{
		ob_start();
		include 'app/views/account/account.php';
		$account = ob_get_clean();
		return $account;
	}

	
}