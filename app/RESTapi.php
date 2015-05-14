<?php
namespace app;
use \app\controller\UserController;
use \app\controller\ServerDaemonController;

class RESTapi extends \lib\Application {

	public function run() {
		
		$this->_router
			->get('/user','User#get')
			->post('/user','User#post')
			->post('/launchdaemon/:id','ServerDaemon#launchDaemon')
			->authorize((new UserController($this))->isUser())
			->authorize((new ServerDaemonController($this))->checkDaemon())
			->post('/user_put','User#put')
			->get('/file','File#get')
			->post('/file','File#post')
			->get('/file/test','File#test')
			->get('/file/:id','File#download')
			->post('/file/:id','File#update')
			->delete('/file/:id','File#delete')
			->get('/home','Home#get')
			->get('/robotics','Robotics#get_test')
			->post('/robotics','Robotics#post_test')
			->get('/robotics/:id','Robotics#get')
			->post('/robotics/:id','Robotics#post')			
			->get('/user_message/:id','ConversationMessage#get')
			->post('/user_message/:id','ConversationMessage#post')
			->get('/user_conversation','Conversation#get')
			->post('/user_conversation/:id','Conversation#post')
			->get('/information','Information#get')
			->authorize((new UserController($this))->isAdmin())
			->get('/daemon','ServerDaemon#get')	
			->post('/daemon','ServerDaemon#post')
			->otherwise(function(){
				\lib\HTTPResponse::redirect404();
			});
		
		exit();
	}
}
