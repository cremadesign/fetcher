<?php
	
	require_once '../vendor/autoload.php';
	require_once 'common.php';
	
	use Crema\CurlRequest;
	
	$urls = (object) [
		'auth' => "https://app.pingboard.com/oauth/token?grant_type=client_credentials",
		'company' => "https://app.pingboard.com/api/v2/companies/my_company",
		'groups' => "https://app.pingboard.com/api/v2/groups",
		'users' => "https://app.pingboard.com/api/v2/users"
	];
	
	
	// === Get Authentication Token ============================================
	$credentials = json_decode(file_get_contents('../config.json'))->pingboard;
	$curl = new CurlRequest();
	$response = $curl->request("POST", $urls->auth, [
		'client_id' => $credentials->client_id,
		'client_secret' => $credentials->client_secret
	]);
	
	$token = $response->object()->access_token;
	
	$headers = $curl->setRequestHeaders([
		"Authorization" => "Bearer $token"
	]);
	
	
	// === Make API Request ====================================================
	$users = $curl->get($urls->users);

	
	// === Print Response ======================================================
	header('Content-Type: application/json');
	dump($users->object()->users);

?>
