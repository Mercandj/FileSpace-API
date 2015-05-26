<?php
namespace lib;

abstract class Controller extends ApplicationComponent {

	public function __construct(Application $app) {
		parent::__construct($app);
	}

	/**
	*	Create Instance of Manager
	*	@param Name of the Manager
	*	@return Manager Object
	*/
	protected function getManagerof($manager) {
		$path = '\\lib\Models\\'.$manager.'Manager';
		return $path::getInstance($this->_app->_pdo);
	}

	protected function getJson($array) {

		// Add update files to each request
		$apk_update = [];
		$list_file = [];
		$list_file = $this->getManagerof('File')->getApkUpdate();
		foreach ($list_file as $file) {
			$apk_update[] = $file->toArray();
		}
		$array['apk_update'] = $apk_update;

		json_encode($array, JSON_NUMERIC_CHECK);
	}
}