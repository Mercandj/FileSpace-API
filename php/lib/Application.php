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
        $_parameters = json_decode($body, true);

        //$_parameters = json_decode($_POST['json'], true);

        $this->_parameters = $_parameters;
    }

}