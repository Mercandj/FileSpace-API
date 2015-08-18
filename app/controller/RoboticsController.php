<?php
namespace app\controller;
use \lib\Entities\User;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class RoboticsController extends \lib\Controller {

	// https://github.com/projectweekend/Pi-GPIO-Server

	/**
	 * Robotics : read pin value
	 * @uri    /robotics
	 * @method GET
	 * @return JSON with info about robotics
	 */
	public function get($pin_id) {
		$json['succeed'] = false;
		
		$id_user = $this->_app->_config->getId_user();
		$userManager = $this->getManagerof('User');
		$user = $userManager->getById($id_user);

		if($user->isAdmin()) {
			$response = file_get_contents($this->_app->_config->get('server_robotics_1') . "/api/v1/pin/".$pin_id);
			$json['succeed'] = true;
			$json['result'] = array(
				array(
					"title" => "response",
					"value" => "".$response
				)
			);	
		}
		else {
			$json['toast'] = 'Unauthorized access.';
		}

		HTTPResponse::send(json_encode($json));
	}


	/**
	 * Do robotics actions
	 * @uri    	/robotics
	 * @method 	POST
	 */
	public function post($pin_id) {
		$json['succeed'] = false;
		
		$id_user = $this->_app->_config->getId_user();
		$userManager = $this->getManagerof('User');
		$user = $userManager->getById($id_user);

		if($user->isAdmin()) {
			$value = '0';
	
			if(HTTPRequest::postExist('value'))
				$value = HTTPRequest::postData('value');
				
			$servo = false;
			if(HTTPRequest::postExist('servo'))
				$servo = HTTPRequest::postData('servo');
	
			$url = $this->_app->_config->get('server_robotics_1') . "/api/v1/pin/".$pin_id;
			$data = array('value' => $value);
	
			$options = array(
			    'http' => array(
			        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			        'method'  => 'PATCH',
			        'content' => http_build_query($data),
			    )
			);
			$context  = stream_context_create($options);
			$response = file_get_contents($url, false, $context);
	
			$json['succeed'] = true;
			$json['result'] = array(
				array(
					"title" => "response",
					"value" => "".$response
				)
			);
		}
		else {
			$json['toast'] = 'Unauthorized access.';
		}

		HTTPResponse::send(json_encode($json));
	}







	/**
	 * Do robotics actions
	 * @uri    	/robotics
	 * @method 	POST
	 */
	public function raspberry() {
		$json['succeed'] = false;
		
		$id_user = $this->_app->_config->getId_user();
		$userManager = $this->getManagerof('User');
		$user = $userManager->getById($id_user);

		if($user->isAdmin()) {
			$json_data = '{"debug":"preimer message"}';
	
			if(HTTPRequest::postExist('json'))
				$json_data = HTTPRequest::postData('json');
	
			$url = $this->_app->_config->get('server_robotics_2');
	
			$options = array(
				'http' => array(
					'protocol_version' => 1.1,
					'header'  => "Content-type: application/json\r\n".
								 "Content-length: " . strlen($json_data) . "\r\n",
					'method'  => 'POST',
					'content' => $json_data,
			    )
			);
			$context  = stream_context_create($options);

			//$response_content = @file_get_contents($url, false, $context); return false
			$fp = @fopen($url, 'r', false, $context);
			if (!$fp) {
			    $json['error-1'] = "Problem with $url";
			}

			$response_content = '';
			while (!feof($fp)) {
			  $response_content .= fread($fp, 8192);
			}
			fclose($fp);
	
			$json['succeed'] = true;
			$json['raspberry-content'] = $response_content;
			$json['debug-json-nuc-request'] = $json_data;
		}
		else {
			$json['toast'] = 'Unauthorized access.';
		}

		HTTPResponse::send(json_encode($json));
	}

}
