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
			->get('/version/supported','Version#supported')
			->get('/support/comment','Support#commentGet')
			->post('/support/comment','Support#commentPost')
			->post('/support/comment/delete','Support#commentDelete')
			->post('/device/add','Device#addOrUpdate')
			->post('/device/push','Device#sendPush')

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

			->get('/genealogy','Genealogy#get')
			->get('/genealogy_statistics','Genealogy#statistics')
			->get('/genealogy/:id','Genealogy#getById')
			->get('/genealogy_children/:id','Genealogy#getChildren')
			->post('/genealogy','Genealogy#post')
			->post('/genealogy_put/:id','Genealogy#put')
			->post('/genealogy_delete/:id','Genealogy#delete')
			->get('/genealogy_marriage','GenealogyMarriage#get')
			->get('/genealogy_marriage/:id','GenealogyMarriage#getById')
			->post('/genealogy_marriage_put/:id','GenealogyMarriage#put')
			->post('/genealogy_marriage_delete/:id','GenealogyMarriage#delete')

			->authorize((new UserController($this))->isAdmin())

			->get('/support/comment/device_id','Support#commentGetAllIdDevice')

			->get('/user_connection','UserConnection#get')
			->get('/daemon','ServerDaemon#get')
			->post('/daemon','ServerDaemon#post')
			
			->otherwise(function(){
				\lib\HTTPResponse::redirect404();
			});
		
		exit();
	}
}
