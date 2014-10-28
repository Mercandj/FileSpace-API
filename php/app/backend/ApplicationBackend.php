<?php
namespace app\backend;

class Applicationbackend extends \lib\Application{
	protected $_name;

	public function __construct(){
		parent::__construct();
		$this->_name = 'backend';
	}

	public function run(){
		

		exit();
	}
}