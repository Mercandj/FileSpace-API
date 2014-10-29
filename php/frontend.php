<?php
	require 'lib/autoload.php';
	

	echo 'json_decode '.json_decode(file_get_contents('php://input'), true);

	echo 'file_get_contents '.file_get_contents('php://input');

	// INSTANCIATION DE l'APPLICATION
	$app = new app\frontend\ApplicationFrontend();
	$app->run();

?>