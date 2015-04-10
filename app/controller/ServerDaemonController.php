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

		$server_daeomn_array = $serverDaemonManager->getAllByServerId(0);
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
			'id_server_daemon' => 0
		));

		$serverDaemonManager->add($serverDaemon);
		$json['succeed'] = false;
		$json['toast'] = 'Daemon has been added.';

		HTTPResponse::send(json_encode($json, JSON_NUMERIC_CHECK));
	}

	/**
	*	Check daemon activity
	*/
	public function checkDaemon() {
		$serverDaemonManager = $this->getManagerof('ServerDaemon');
		$serverDaemonPingManager = $this->getManagerof('ServerDaemonPing');

		$server_daemon_array = $serverDaemonManager->getAll();
		foreach ($server_daemon_array as $server_daemon) {
			if($server_daemon->getActivity()==1 && $server_daemon->getRunning()==1) {
				$serverDaemonPingManager->getByServerDaemonId($server_daemon->getId());
				// TODO
			}
		}
	}
}