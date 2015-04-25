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
			->get('/file','File#get')
			->post('/file','File#post')
			->get('/file/test','File#test')
			->get('/file/:id','File#download')
			->post('/file/:id','File#update')
			->delete('/file/:id','File#delete')
			->get('/robotics/:id','Robotics#get')
			->post('/robotics/:id','Robotics#post')
			->post('/user_message/:id','ConversationMessage#post')
			->get('/user_message/:id','ConversationMessage#get')
			->get('/user_conversation','Conversation#get')
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