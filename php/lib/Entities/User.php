<?php
namespace lib\Entities;

class User extends \lib\Entity{

	const INVALID_USERNAME = 1,
		INVALID_PASSWORD = 2,
		INVALID_EMAIL = 3,
		KEY_ADMIN = 1;

	protected $_id,
		$_username,
		$_password,
		$_admin,
		$_date_create,
		$_date_last_connection,
		$_first_name,
		$_last_name,
		$_email;

	public function getId(){
		return $this->_id;
	}

	public function getUsername(){
		return $this->_username;
	}

	public function getPassword(){
		return $this->_password;
	}

	public function getFirst_name(){
		return $this->_first_name;
	}

	public function getLast_name(){
		return $this->_last_name;
	}

	public function getEmail(){
		return $this->_email;
	}

	public function getDate_create(){
		return $this->_date_create;
	}

	public function getDate_last_connection(){
		return $this->_date_last_connection;
	}

	public function isAdmin(){
		return ($this->_admin == self::KEY_ADMIN ) ? true : false;
	}

	public function setId($id){
		if(!empty($id)){
			$this->_id = $id;
		}
	}

	public function setUsername($username){
		if(!empty($username)){
			$this->_username = $username;
		}else{
			$this->_errors[] = self::INVALID_USERNAME;
		}
	}

	public function setPassword($password){
		if(!empty($password)){
			$this->_password = $password;
		}else{
			$this->_errors[] = self::INVALID_PASSWORD;
		}
	}

	public function setAdmin($boolean){
		if($boolean == 1){
			$this->_admin = self::KEY_ADMIN;
		}else{
			$this->_admin = 0;
		}
	}

	public function setFirst_name($first_name){
		if(!empty($first_name)){
			$this->_first_name = $first_name;
		}else{
			// Well ...
		}
	}

	public function setLast_name($last_name){
		if(!empty($last_name)){
			$this->_last_name = $last_name;
		}else{
			// Well ...
		}
	}

	public function setEmail($email){
		if(!empty($email)){ // Need a REGEX
			$this->_email = $email;
		}else{
			$this->_errors[] = self::INVALID_EMAIL;
		}
	}

	public function setDate_create($date){
		if(!empty($date)){
			$this->_date_create = $date;
		}
	}

	public function setDate_last_connection($date){
		if(!empty($date)){
			$this->_date_last_connection = $date;
		}
	}

	public function isValid(){
		return !empty($this->_username) && !empty($this->_password);
	}

	public function jsonSerialize() {
		$json = [];
		$json['id'] = $this->getId();
		$json['username'] = $this->getUsername();
		$json['admin'] = $this->isAdmin();
        return $json;
    }
}