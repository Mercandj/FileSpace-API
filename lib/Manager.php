<?php
namespace lib;

abstract class Manager{
	protected $_db;

	private function __construct($db){
		$this->_db =$db;
	}

	public static function getInstance($pdo){
		if (!isset(static::$instance)){
			static::$instance = new static($pdo);
		}
		return static::$instance;
	}
}