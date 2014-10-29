<?php
	require 'lib/autoload.php';
	

	echo json_decode(file_get_contents('php://input'), true);

	// INSTANCIATION DE l'APPLICATION
	$app = new app\frontend\ApplicationFrontend();
	$app->run();

?>