<?php
namespace lib\Models;
use \lib\Entities\User;

class UserManager extends \lib\Manager {
	protected static $instance;

	public function add(User $user) {
		$username = $user->getUsername();
		$password = $user->getPassword();
		$date_create = $user->getDate_create();
		$last_name = $user->getLast_name();
		$first_name = $user->getFirst_name();
		$email = $user->getEmail();

		$req = $this->_db->prepare('INSERT INTO user(username,password,date_create,date_last_connection, last_name, first_name, email) VALUES (:username, :password, :date_create, :date_last_connection, :last_name, :first_name, :email)');
		$req->bindParam(':username',$username,\PDO::PARAM_STR);
		$req->bindParam(':password',$password,\PDO::PARAM_STR);
		$req->bindParam(':date_create',$date_create,\PDO::PARAM_STR);
		$req->bindParam(':date_last_connection',$date_create,\PDO::PARAM_STR);
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

	public function get($username) {
		$req = $this->_db->prepare('SELECT id,username,password,admin FROM user WHERE username = :username');
    	$req->bindParam(':username', $username, \PDO::PARAM_STR);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
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

	public function getAll() {
		$user = array();

		$req = $this->_db->query('SELECT id,username FROM user');

    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$user[] = new User($donnees);
	    $req->closeCursor();
	    return $user;
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