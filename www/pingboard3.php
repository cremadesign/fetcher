<?php
	
	require_once '../vendor/autoload.php';
	require_once 'common.php';
	use Crema\CurlRequest;
	
	class Pingboard {
		public function __construct($credentials) {
			$this->urls = (object) [
				'auth' => "https://app.pingboard.com/oauth/token?grant_type=client_credentials",
				'company' => "https://app.pingboard.com/api/v2/companies/my_company",
				'groups' => "https://app.pingboard.com/api/v2/groups",
				'users' => "https://app.pingboard.com/api/v2/users?page_size=300"
			];
			
			$this->clientId = $credentials->client_id;
			$this->clientSecret = $credentials->client_secret;
			$this->auth();
		}
		
		public function auth() {
			$this->curl = new CurlRequest();
			$response = $this->curl->request("POST", $this->urls->auth, [
				'client_id' => $this->clientId,
				'client_secret' => $this->clientSecret
			]);
			
			$token = $response->object()->access_token;
			
			$headers = $this->curl->setRequestHeaders([
				"Authorization" => "Bearer $token"
			]);
		}
		
		public function company() {
			$response = $this->curl->get($this->urls->company);
			return $response;
		}
		
		public function groups() {
			$response = $this->curl->get($this->urls->groups);
			return $response->object()->groups;
		}
		
		public function users() {
			$response = $this->curl->get($this->urls->users);
			return $response->object()->users;
		}
	}
	
	$credentials = json_decode(file_get_contents('../config.json'))->pingboard;
	$pingboard = new Pingboard($credentials);
	$users = $pingboard->groups();
	
	header('Content-Type: application/json');
	dump($users->object());

?>
