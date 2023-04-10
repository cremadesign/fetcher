<?php
	
	$url = "https://dummyjson.com/products";
	$headers = [
		'Content-Type: application/json',
		'X-Custom: something'
	];
	
	$ch = curl_init($url);
	
	if (strpos($_SERVER["HTTP_HOST"], '.test') !== false) {
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	}
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	
	$response = curl_exec($ch);
	
	$result = [
		'headerSize' => curl_getinfo($ch, CURLINFO_HEADER_SIZE),
		'request' => curl_getInfo($ch, CURLINFO_HEADER_OUT),
		'statusCode' => curl_getInfo($ch, CURLINFO_HTTP_CODE),
		'response' => $response
	];
	
	if (curl_errno($ch)) {
		$result['error'] = curl_error($ch);
	}
	
	// $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	// echo $headerSize;
	//
	// exit();
	
	curl_close($ch);
	
	header('Content-Type: application/json');
	echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>
