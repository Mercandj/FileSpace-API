<?php
namespace app\frontend\controller;

class TestController extends \lib\Controller{

	public function getCategories(){
		

		// SEND PAGE
		$this->_app->_HTTPResponse->send($this->_app->_page->draw('TestJSON.php'));
	}
}