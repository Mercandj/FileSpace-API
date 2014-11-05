<?php
namespace lib;

class Route{
	private $_url,
		$_controller,
		$_action,
		$_method,
		$_matches;

	public function __construct($url, $controller, $action, $method, $matches){
		$this->_url = $url;
		$this->_controller= $controller;
		$this->_action = $action;
		$this->_method = $method;
		$this->_matches = $matches;
	}

	public function getMatches(){
		return $this->_matches;
	}

	public function getUrl(){
		return $this->_url;
	}

	public function getController(){
		return $this->_controller;
	}

	public function getAction(){
		return $this->_action;
	}

	public function getMethod(){
		return $this->_method;
	}
}