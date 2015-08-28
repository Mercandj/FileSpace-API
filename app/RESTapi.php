<?php
namespace app;
use \app\controller\UserController;
use \app\controller\ServerDaemonController;

class RESTapi extends \lib\Application {

	public function run() {
		
		$this->_router
			->get('/user','User#get')
			->post('/user','User#post')
			->get('/android_app','File#download_android_app')
			->post('/launchdaemon/:id','ServerDaemon#launchDaemon')
			->authorize((new UserController($this))->isUser())
			->authorize((new ServerDaemonController($this))->checkDaemon())
			->post('/user_put','User#put')
			->post('/user_delete/:id','User#delete')
			->get('/user/:id','User#getById')
			->get('/file','File#get')
			->post('/file','File#post')
			->get('/file/test','File#test')
			->get('/file/:id','File#download')
			->post('/file/:id','File#update')
			->post('/file_delete/:id','File#delete')
			->get('/home','Home#get')
			->post('/robotics','Robotics#raspberry')
			->get('/robotics/:id','Robotics#get')
			->post('/robotics/:id','Robotics#post')
			->get('/user_message/:id','ConversationMessage#get')
			->post('/user_message/:id','ConversationMessage#post')
			->delete('/user_message/:id','ConversationMessage#delete')
			->get('/user_conversation','Conversation#get')
			->post('/user_conversation/:id','Conversation#post')
			->get('/information','Information#get')
			->get('/genealogy/:id','Genealogy#get')
			->post('/genealogy','Genealogy#post')
			->authorize((new UserController($this))->isAdmin())
			->get('/user_connection','UserConnection#get')
			->get('/daemon','ServerDaemon#get')
			->post('/daemon','ServerDaemon#post')
			->otherwise(function(){
				\lib\HTTPResponse::redirect404();
			});
		
		exit();
	}
}
