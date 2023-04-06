<?php
	
	require_once '../vendor/autoload.php';
	require_once 'common.php';
	
	use Crema\Pingboard;
	
	$credentials = json_decode(file_get_contents('../config.json'))->pingboard;
	$pingboard = new Pingboard($credentials);
	
	$users = $pingboard->users();
	
	dump($users);

?>
