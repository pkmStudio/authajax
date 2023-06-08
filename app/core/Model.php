<?php

namespace app\core;

use PDO;
use app\lib\Db;

abstract class Model {

	public $pdo;
	
	public function __construct() {
		$this->pdo = Db::getInstanse();
	}

	// Эта функция отправляет запросы БД.
	public function query($sql, $params = []) 
	{
		$query = $this->pdo->prepare($sql);
		if (!empty($params)) {
			foreach ($params as $key => $val) {
				if (is_int($val)) {
					$type = PDO::PARAM_INT;
				} else {
					$type = PDO::PARAM_STR;
				}
				$query->bindValue(':'.$key, $val, $type);
			}
		}
		$query->execute();
		return $query;
	}

	// Эта функция возвращает результат запроса в виде объекта.
	public function row($sql, $params = []) {
		$result = $this->query($sql, $params);
		return $result->fetchAll(PDO::FETCH_OBJ);
	}

	// Эта функция выводит по колонке какого-то запроса. 
	public function column($sql, $params = []) {
		$result = $this->query($sql, $params);
		return $result->fetchColumn();
	}
}