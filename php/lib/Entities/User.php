<?php
namespace lib\Entities;

class User extends \lib\Entity{

	const INVALID_USERNAME = 1,
		INVALID_PASSWORD = 2,
		KEY_ADMIN = 1;

	protected $_id,
		$_username,
		$_password,
		$_admin;

	public function getId(){
		return $this->_id;
	}

	public function getUsername(){
		return $this->_username;
	}

	public function getPassword(){
		return $this->_password;
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