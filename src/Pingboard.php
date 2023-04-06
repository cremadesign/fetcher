<?php
	
	namespace Crema;
	
	use Crema\CurlRequest;
	
	class Pingboard {
		public function __construct($credentials) {
			$this->urls = (object) [
				'auth' => "https://app.pingboard.com/oauth/token?grant_type=client_credentials",
				'company' => "https://app.pingboard.com/api/v2/companies/my_company",
				'groups' => "https://app.pingboard.com/api/v2/groups",
				'users' => "https://app.pingboard.com/api/v2/users"
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
			
			$this->access_token = $response->object()->access_token;
			
			$headers = $this->curl->setRequestHeaders([
				"Authorization" => "Bearer $this->access_token"
			]);
		}
		
		public function get_headers() {
			return $this->curl->getRequestHeaders();
		}
		
		public function company() {
			$response = $this->curl->get($this->urls->company);
			return $response->json();
		}
		
		public function groups() {
			$response = $this->curl->get($this->urls->groups);
			return $response->json();
		}
		
		public function users() {
			$response = $this->curl->get($this->urls->users);
			return $response->json();
		}
	}
	
?>
