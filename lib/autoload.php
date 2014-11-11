<?php
	function autoload($class){
		//echo '<pre>Autoload : ' . $class;
		$path = str_replace('\\', '/', $class).'.php';
		//echo "\n    =&gt; $path</pre>";
		require_once $path;
	}
	spl_autoload_register('autoload');