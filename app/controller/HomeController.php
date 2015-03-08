<?php
namespace app\controller;
use \lib\Entities\User;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class HomeController extends \lib\Controller {

	// https://github.com/projectweekend/Pi-GPIO-Server

	/**
	 * Get the home informations : home automation
	 * @uri    /home
	 * @method GET
	 * @return JSON with info about home automation
	 */
	public function get() {

		$response = file_get_contents($this->_app->_config->get('server_home_automation') . "/api/v1/pin/23");

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
	 * Do home actons
	 * @uri    	/home
	 * @method 	POST
	 */
	public function post() {

		$value = '0';

		if(HTTPRequest::postExist('value'))
			$value = HTTPRequest::postData('value');

		$url = $this->_app->_config->get('server_home_automation') . "/api/v1/pin/18";
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