<?php
	
	require_once '../vendor/autoload.php';
	require_once 'common.php';
	
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
	
	/*/
	$headers = [
		"Content-Type" => "application/json5",
		"X-Authorization" => "Bearer"
	];
	
	$headers = array_merge([
		"Content-Type" => "application/json"
	], $headers);
	
	$headers = array_merge([], $headers);
	
	$header_keys = array_keys($headers);
	$headers = array_map(fn($k, $v) => "$k: $v", $header_keys, $headers);
	
	dump($headers);
	/*/
	
?>
