<?php
	
	require_once '../vendor/autoload.php';
	use Crema\Fetcher;
	
	$fetcher = new Fetcher();
	$response = $fetcher->request("https://dummyjson.com/products/1");
	
	header('Content-Type: application/json');
	echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	
?>
