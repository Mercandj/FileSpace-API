<?php
namespace app\controller;
use \lib\Entities\ServerDaemon;
use \lib\Entities\ServerDaemonPing;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class ServerDaemonController extends \lib\Controller {

	/**
	 */
	public function get() {
		$result = [];
		$json['succeed'] = false;
		$serverDaemonManager = $this->getManagerof('ServerDaemon');
		$serverDaemonPingManager = $this->getManagerof('ServerDaemonPing');

		$server_daemon_array = $serverDaemonManager->getAll();
		foreach ($server_daemon_array as $server_daemon) {
			$result[] = $server_daemon->toArray();
		}

		$json['succeed'] = true;
		$json['result'] = $result;

		HTTPResponse::send(json_encode($json, JSON_NUMERIC_CHECK));
	}

	/**
	 */
	public function post() {
		$result = [];
		$json['succeed'] = false;
		$serverDaemonManager = $this->getManagerof('ServerDaemon');
		$serverDaemonPingManager = $this->getManagerof('ServerDaemonPing');

		$id_user = $this->_app->_config->getId_user();
		$server_daeomn_array = $serverDaemonManager->getAllByServerId(1);
		if(is_array($server_daeomn_array))
			if(sizeof($server_daeomn_array) > 0) {
				$json['succeed'] = true;
				$json['toast'] = 'A daemon with id == 0 already exists.';
				HTTPResponse::send(json_encode($json, JSON_NUMERIC_CHECK));
				return;
			}
		$serverDaemon = new ServerDaemon(array(
			'id'=> 0,
			'visibility' => 1,
			'date_creation' => date('Y-m-d H:i:s'),
			'id_user' => $id_user,
			'id_server_daemon' => 1
		));
		$serverDaemonManager->add($serverDaemon);


		$json['succeed'] = true;
		$json['toast'] = 'Daemon has been added.';

		HTTPResponse::send(json_encode($json, JSON_NUMERIC_CHECK));
	}

	/**
	*	Check daemon activity
	*/
	public function checkDaemon() {
		$serverDaemonManager = $this->getManagerof('ServerDaemon');
		$serverDaemonPingManager = $this->getManagerof('ServerDaemonPing');

		$id_user = $this->_app->_config->getId_user();
		$server_daeomn_array = $serverDaemonManager->getAllByServerId(1);
		$serverDaemon = new ServerDaemon(array(
			'id'=> 0,
			'visibility' => 1,
			'date_creation' => date('Y-m-d H:i:s'),
			'id_user' => $id_user,
			'id_server_daemon' => 1,
			'activate' => 1,
			'running' => 0,
			'sleep_second' => 36000
		));
		if($this->isArrayEmpty($server_daeomn_array))
			$serverDaemonManager->add($serverDaemon);

		$server_daemon_array = $serverDaemonManager->getAllActivate();
		foreach ($server_daemon_array as $server_daemon) {
			if($server_daemon->getActivate()==1) {
				$server_daeomn_ping_array = $serverDaemonPingManager->getByServerDaemonId($server_daemon->getId());				
				if($this->isArrayEmpty($server_daeomn_ping_array))
				{
					// Set running to false because launchDaemon() check if the daemin is running or not.
					$server_daemon->setRunning(0);
					$serverDaemonManager->updateRunning($server_daemon);				

					// TODO curl request to launchDaemon($id)
					$url = 'http://'.$_SERVER['HTTP_HOST'].$this->_app->_config->get('root').'/launchdaemon/'.($server_daemon->getId());
				    $fields = array(
				        'test' => 'test1'
				    ); 
				    $headers = array(
				        'Content-Type: application/json'
				    );
				    $ch = curl_init();
				    curl_setopt($ch, CURLOPT_URL, $url);
				    curl_setopt($ch, CURLOPT_POST, true);
				    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
				    $result = curl_exec($ch);      
				    if ($result === FALSE) {
				        die('Curl failed: ' . curl_error($ch));
				    }
				    curl_close($ch);

				}
				else
				{
					// TODO compare the last ping date with (the daemon sleep_second  -  current date)
				}
			}
		}
		return true;
	}

	function isArrayEmpty($array) {
		if(!is_array($array))
			return false;
		return count($array) == 0;
	}


	function launchDaemon($id) {
		$serverDaemonManager = $this->getManagerof('ServerDaemon');
		$serverDaemonPingManager = $this->getManagerof('ServerDaemonPing');

		if($server_daemon = $serverDaemonManager->existById($id)) {
			$server_daemon = $serverDaemonManager->getById($id);

			if($server_daemon->getActivate()==1 && $server_daemon->getRunning()==0) {
				set_time_limit(0);
				ignore_user_abort(1);

				$isRunning = true;

				while($isRunning) {

					$server_daemon->setRunning(1);
					$serverDaemonManager->updateRunning($server_daemon);

					// TODO make daemon action
					//if($server_daemon->getId_server_daemon() == 1) {
						$this->sendNotif('Message from daemon ^^');
					//}

					$serverDaemonPing = new ServerDaemonPing(array(
						'id'=> 0,
						'visibility' => 1,
						'date_creation' => date('Y-m-d H:i:s'),
						'id_server_daemon' => $id,
						'content' => 'customcontent : $server_daemon->getId_server_daemon()='.$server_daemon->getId_server_daemon()
					));
					$serverDaemonPingManager->add($serverDaemonPing);

					// TODO compute the sleep time

					// TODO sleep
					sleep($server_daemon->getSleep_second());

					$isRunning = false;
				}

				$server_daemon->setRunning(0);
				$serverDaemonManager->updateRunning($server_daemon);

			}
		}
	}






	function sendNotif($pushMessage) {
	  try {
	    $bdd = new PDO('mysql:host=localhost;dbname=jarvis', 'root', '');
	  }
	  catch(Exception $e) {
	    die('Erreur : '.$e->getMessage());
	  }

	  //this block is to post message to GCM on-click
	  $pushStatus = "";

	  $req = $bdd->prepare('SELECT * FROM `user` WHERE `admin` = 1');
	  $req->execute();

	  while($donnees = $req->fetch()) {
	    $gcmRegID  = $donnees['android_id'];

	    if (isset($gcmRegID) && isset($pushMessage)) {   
	      $gcmRegIds = array($gcmRegID);
	      $message = array("m" => $pushMessage);
	      $pushStatus = $this->sendPushNotificationToGCM($gcmRegIds, $message);
	    } 
	  }
	}

	//generic php function to send GCM push notification
	function sendPushNotificationToGCM($registatoin_ids, $message) {
	//Google cloud messaging GCM-API url
	    $url = 'https://android.googleapis.com/gcm/send';
	    $fields = array(
	        'registration_ids' => $registatoin_ids,
	        'data' => $message,
	    ); 
	    $headers = array(
	        'Authorization: key=' . "AIzaSyALmR120lJH_ZN4NZO4_JyU7K_08OJwG2Q",
	        'Content-Type: application/json'
	    );
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	    $result = curl_exec($ch);      
	    if ($result === FALSE) {
	        die('Curl failed: ' . curl_error($ch));
	    }
	    curl_close($ch);
	    return $result;
	}
}