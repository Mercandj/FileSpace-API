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

		$response = file_get_contents($this->_app->_config->get('server_robotics') . "/api/v1/pin/".$pin_id);

		$json['succeed'] = true;
		$json['result'] = array(

			array(
				"title" => "response",
				"value" => "".$response
			)

		);

		HTTPResponse::send(json_encode($json));
	}


	/**
	 * Do robotics actions
	 * @uri    	/robotics
	 * @method 	POST
	 */
	public function post($pin_id) {

		$value = '0';

		if(HTTPRequest::postExist('value'))
			$value = HTTPRequest::postData('value');

		$url = $this->_app->_config->get('server_robotics') . "/api/v1/pin/".$pin_id;
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

		HTTPResponse::send(json_encode($json));
	}

}