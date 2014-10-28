<?php
namespace app\frontend;

class Applicationfrontend extends \lib\Application {
	protected $_name;

	public function __construct() {
		parent::__construct();
		$this->_name = 'frontend';
	}

	public function run() {

		exit();
	}
}