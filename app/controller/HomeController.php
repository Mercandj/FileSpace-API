<?php
namespace app\controller;
use \lib\Entities\User;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class HomeController extends \lib\Controller {

	/**
	 * Get the home informations : home automation
	 * @uri    /home
	 * @method GET
	 * @return JSON with info about home automation
	 */
	public function get() {

		$response = file_get_contents($this->_app->_config->get('server_home_automation'));

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

		$url = $this->_app->_config->get('server_home_automation');
		$data = array('key1' => 'value1', 'key2' => 'value2');

		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($data),
		    ),
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