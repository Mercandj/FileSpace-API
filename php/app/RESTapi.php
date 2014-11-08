<?php
namespace app;
use \app\controller\UserController;

class RESTapi extends \lib\Application {

	public function run() {

		$this->_router
			->get('/user','User#get')
			->post('/user','User#post');

		if((new UserController($this))->isUser()){
			$this->_router
				->get('/file','File#get')
				->post('/file','File#post')
				->get('/file/:id','File#download')
				->put('/file/:id','File#put')
				->delete('/file/:id','File#delete');

		}else {
			\lib\HTTPResponse::redirect401();
		}

		\lib\HTTPResponse::redirect404();
		
		exit();
	}
}