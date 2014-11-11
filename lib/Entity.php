<?php
namespace lib;

abstract class Entity{
	protected $_errors = array();

	public function __construct(array $donnee){
		if (!empty($donnee))
			$this->hydrate($donnee);
	}

	private function hydrate(array $donnee){
		foreach($donnee as $key => $value){
			$method = 'set'.ucfirst($key);
			if(method_exists($this, $method))
				$this->$method($value);
		}
	}

	public function getErrors(){
		return $this->_errors;
	}

	public function isNew(){
		return empty($this->_id);
	}
	
	abstract function isValid();
}