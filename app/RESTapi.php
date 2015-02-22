<?php
namespace app;
use \app\controller\UserController;

class RESTapi extends \lib\Application {

	public function run() {

		$this->_router
			->get('/user','User#get')
			->post('/user','User#post')
			->authorize((new UserController($this))->isUser())
			->get('/file','File#get')
			->post('/file','File#post')
			->get('/file/test','File#test')
			->get('/file/:id','File#download')
			->put('/file/:id','File#put')
			->delete('/file/:id','File#delete')
			->get('/home','Home#get')
			->post('/home','Home#post')
			->get('/information','Information#get')	
			->otherwise(function(){
				\lib\HTTPResponse::redirect404();
			});
		
		exit();
	}
}