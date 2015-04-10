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
			}
		}
	}
}