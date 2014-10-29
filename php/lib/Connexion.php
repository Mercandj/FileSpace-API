<?php
namespace lib;

class Connexion{
	private $_pdo;

	public function __construct(){
		try{
			$this->_pdo = new \PDO('sqlite:app/dbblog');
		}catch(Exception $e){
		    die('Erreur : '.$e->getMessage());
		}
	}

	public function getPDO(){
		return $this->_pdo;
	}
}