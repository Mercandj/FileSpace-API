<?php
namespace lib;
use \lib\Models\ConfigManager;

abstract class Application{
	public $_config,
		$_pdo,
		$_page,
		$_HTTPResponse,
		$_HTTPRequest,
		$_parameters;

	public function __construct(){
		$this->parseIncomingParams();
		$this->_page = new Page($this);
		$this->_HTTPResponse = new HTTPResponse($this);
		$this->_request = new HTTPRequest($this);
		$this->_config =  ConfigManager::getInstance();
		$connexion = new Connexion($this);	
		$this->_pdo = $connexion->getPDO();	
	}

	abstract public function run();

	public function getName(){
		return $this->_name;
	}

	public function getController(){	
		try{
			$route = Router::get($_SERVER['REQUEST_URI']);
		}catch(\RuntimeException $e){
			$this->_HTTPResponse->redirect404();
		}
		
		$controleurPath = '\app\\'.$this->_name.'\\controller\\'.$route->getController().'Controller';
		return new $controleurPath($this,$route->getAction(), $route->getMatches());
	}

	private function parseIncomingParams() {
        $_parameters = array();
 
        // first of all, pull the GET vars
        if (isset($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $_parameters);
        }
 
        // now how about PUT/POST bodies? These override what we got from GET
        $body = file_get_contents("php://input");
        $content_type = false;
        if(isset($_SERVER['CONTENT_TYPE'])) {
            $content_type = $_SERVER['CONTENT_TYPE'];
        }
        switch($content_type) {
            case "application/json":
                $body_params = json_decode($body);
                if($body_params) {
                    foreach($body_params as $param_name => $param_value) {
                        $_parameters[$param_name] = $param_value;
                    }
                }
                $this->format = "json";
                break;
            case "application/x-www-form-urlencoded":
                parse_str($body, $postvars);
                foreach($postvars as $field => $value) {
                    $_parameters[$field] = $value;
 
                }
                $this->format = "html";
                break;
            default:
                // we could parse other supported formats here
                break;
        }
        $this->_parameters = $_parameters;
    }

}