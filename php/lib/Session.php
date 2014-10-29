<?php
namespace lib;
use \lib\Entities\User;

session_start();

class Session{

	public function setUser(User $user){
		$_SESSION['user'] = $user;
	}

	public function getUser(){
		return $_SESSION['user'];
	}

	public function isLogin(){
		return (isset($_SESSION['user']) && $_SESSION['user'] instanceof User);
	}

	public function setFlashMessage($text){
		$_SESSION['flash'] = $text;
	}

	public function hasFlashMessage(){
		return isset($_SESSION['flash']);
	}

	public function getFlashMessage(){
		$flashMessage =  isset($_SESSION['flash']) ? $_SESSION['flash'] : null;
		unset($_SESSION['flash']);
		return $flashMessage;
	}

	public function logout(){
		session_destroy();
	}
}