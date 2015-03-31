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
			->post('/file/:id','File#update')
			->delete('/file/:id','File#delete')
			->get('/home/:id','Home#get')
			->post('/home/:id','Home#post')
			->post('/user_message/:id','UserMessage#post')
			->get('/information','Information#get')	
			->otherwise(function(){
				\lib\HTTPResponse::redirect404();
			});
		
		exit();
	}
}