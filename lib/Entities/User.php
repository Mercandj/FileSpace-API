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
		$_date_creation,
		$_date_last_connection,
		$_first_name,
		$_last_name,
		$_email,
		$_android_id,
		$_num_files,
		$_size_files,
		$_id_file_profile_picture;

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

	public function getDate_creation(){
		return $this->_date_creation;
	}

	public function getDate_last_connection(){
		return $this->_date_last_connection;
	}

	public function getAndroid_id(){
		return $this->_android_id;
	}

	public function getNum_files(){
		return $this->_num_files;
	}

	public function getSize_files(){
		return $this->_size_files;
	}

	public function getId_file_profile_picture(){
		return $this->_id_file_profile_picture;
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

	public function setDate_creation($date){
		if(!empty($date)){
			$this->_date_creation = $date;
		}
	}

	public function setDate_last_connection($date){
		if(!empty($date)){
			$this->_date_last_connection = $date;
		}
	}

	public function setAndroid_id($android_id){
		if(!empty($android_id)){
			$this->_android_id = $android_id;
		}
	}

	public function setNum_files($num_files){
		if(!empty($num_files)){
			$this->_num_files = $num_files;
		}
	}

	public function setSize_files($size_files){
		if(!empty($size_files)){
			$this->_size_files = $size_files;
		}
	}

	public function setId_file_profile_picture($id_file_profile_picture){
		if(!empty($id_file_profile_picture)){
			$this->_id_file_profile_picture = $id_file_profile_picture;
		}
	}

	public function isValid(){
		return !empty($this->_username) && !empty($this->_password);
	}

	public function toArray() {
		$json['id'] = $this->getId();
		$json['username'] = $this->getUsername();
		if($this->getLast_name()!=null)
			$json['last_name'] = $this->getLast_name();
		if($this->getFirst_name()!=null)
			$json['first_name'] = $this->getFirst_name();
		if($this->getEmail()!=null)
			$json['email'] = $this->getEmail();
		if($this->getDate_creation()!=null)
			$json['date_creation'] = $this->getDate_creation();
		if($this->getDate_last_connection()!=null)
			$json['date_last_connection'] = $this->getDate_last_connection();
		if($this->isAdmin()!=null)
			$json['admin'] = $this->isAdmin();
		if($this->getNum_files()!=null)
			$json['num_files'] = $this->getNum_files();
		if($this->getSize_files()!=null)
			$json['size_files'] = $this->getSize_files();
		if($this->getId_file_profile_picture()!=null)
			$json['id_file_profile_picture'] = $this->getId_file_profile_picture();
        return $json;
    }
}