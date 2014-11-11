<?php 
namespace lib;

abstract class ApplicationComponent{
	protected $_app;

	public function __construct(Application $app){
		$this->_app = $app;
	}
}