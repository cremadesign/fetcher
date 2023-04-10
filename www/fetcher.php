<?php
	
	require_once '../vendor/autoload.php';
	use Crema\Fetcher;
	
	$fetcher = new Fetcher();
	$response = $fetcher->request("https://dummyjson.com/products/1");
	
	dump($response);
	
?>
