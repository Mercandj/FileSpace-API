<?php
namespace lib\Models;
use \lib\Entities\User;

class UserManager extends \lib\Manager {
	protected static $instance;

	public function add(User $user) {
		$username = $user->getUsername();
		$password = $user->getPassword();
		$date_create = $user->getDate_create();
		$date_last_connection = $user->getDate_last_connection();
		$last_name = $user->getLast_name();
		$first_name = $user->getFirst_name();
		$email = $user->getEmail();

		$req = $this->_db->prepare('INSERT INTO user(username,password,date_create,date_last_connection, last_name, first_name, email) VALUES (:username, :password, :date_create, :date_last_connection, :last_name, :first_name, :email)');
		$req->bindParam(':username',$username,\PDO::PARAM_STR);
		$req->bindParam(':password',$password,\PDO::PARAM_STR);
		$req->bindParam(':date_create',$date_create,\PDO::PARAM_STR);
		$req->bindParam(':date_last_connection',$date_last_connection,\PDO::PARAM_STR);
		$req->bindParam(':last_name',$last_name,\PDO::PARAM_STR);
		$req->bindParam(':first_name',$first_name,\PDO::PARAM_STR);
		$req->bindParam(':email',$email,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function delete($id) {
		$req = $this->_db->prepare('DELETE FROM user WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();
		$req->closeCursor();
	}

	public function update(User $user) {
		
		$id = $user->getId();
		$username = $user->getUsername();
		$password = $user->getPassword();

		$req = $this->_db->prepare('UPDATE user SET username = :username, password = :password WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':username',$username,\PDO::PARAM_STR);
		$req->bindParam(':password',$password,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function updateConnection(User $user){
		$username = $user->getUsername();
		$date_last_connection = $user->getDate_last_connection();

		$req = $this->_db->prepare('UPDATE user SET date_last_connection = :date_last_connection WHERE username = :username');
		$req->bindParam(':username',$username,\PDO::PARAM_STR);
		$req->bindParam(':date_last_connection',$date_last_connection,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function get($username) {
		$req = $this->_db->prepare('SELECT id,username,password,admin FROM user WHERE username = :username');
    	$req->bindParam(':username', $username, \PDO::PARAM_STR);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	print_r($donnee);
    	$req->closeCursor();
    	return new User($donnee);
	}

	public function getById($id) {
		$req = $this->_db->prepare('SELECT id,username,password,admin FROM user WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new User($donnee);
	}

	public function exist($username) {

		$req = $this->_db->prepare('SELECT id FROM user WHERE username = :username');
    	$req->bindParam(':username', $username,\PDO::PARAM_STR);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return ($donnee['id'] != NULL) ? true : false;
	}
}