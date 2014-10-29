<?php
namespace lib;

class Connexion{
	private $_pdo;

	public function __construct(Application $app){

		//echo $app->_config->get('bdd_name').'   '.$app->_config->get('bdd_login').'   '.$app->_config->get('bdd_password');

		try{
			$this->_pdo = new \PDO('mysql:host=localhost;dbname='.$app->_config->get('bdd_name'), $app->_config->get('bdd_login'), $app->_config->get('bdd_password'));
		}catch(Exception $e){
		    die('Erreur : '.$e->getMessage());
		}
	}

	public function getPDO(){
		return $this->_pdo;
	}
}