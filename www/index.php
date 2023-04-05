<?php
	
	require_once '../vendor/autoload.php';
	use Crema\CurlRequest;
	
	$apis = json_decode(file_get_contents('tests.json'), true);
	$credentials = json_decode(file_get_contents('../config.json'))->pingboard;
	
	$apis['pingboard']['auth']['payload'] = [
		'client_id' => $credentials->client_id,
		'client_secret' => $credentials->client_secret
	];
	
	extract($apis['pingboard']['auth']);
	
	$fetcher = new CurlRequest();
	$response = $fetcher->request($method, $url, $payload, $headers);
	
	header('Content-Type: application/json');
	echo json_encode($response->json(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>
