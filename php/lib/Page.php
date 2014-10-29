<?php
namespace lib;

class Page extends ApplicationComponent{
	private $_hashmap = array();

	private function minify($output){
		return preg_replace(
		    array(
				'/ {2,}/',
		        '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'
		    ),
		    array(
				' ',
		        ''
		    ),
		    $output
		);
	}

	public function assign($key, $value){
		$this->_hashmap[$key] = $value;
	}

	public function draw($view){			
		extract($this->_hashmap);

		ob_start();
		require __DIR__.'/../app/'.$this->_app->getName().'/view/'.$view;

		if($this->_app->getName() != 'backend'){
			return $this->minify(ob_get_clean());
		}else{
			return ob_get_clean();
		}
	}
}