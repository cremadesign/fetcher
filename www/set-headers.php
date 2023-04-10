<?php
	
	require_once '../vendor/autoload.php';
	use Crema\CurlRequest;
	
	// === Get Authentication Token ============================================
	$curl = new CurlRequest();
	
	$curl->setRequestHeaders([
		"Authorization" => "Bearer"
	]);
	
	$curl->setRequestHeaders([
		"X-Authorization" => "Bearer"
	]);
	
	$headers = $curl->getRequestHeaders();
	
	dump($headers);
	
?>
