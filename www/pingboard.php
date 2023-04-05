<?php
	
	require_once '../vendor/autoload.php';
	use Crema\CurlRequest;
	
	class Pingboard {
		public function __construct($credentials) {
			$this->cacheDir = 'data/.cached';
			$this->cacheLife = '120'; // in seconds
			
			if (!is_dir($this->cacheDir)) {
				mkdir($this->cacheDir, 0777, true);
			}
			
			// Get Authentication Token
			$this->setClientSecret($credentials->client_secret);
			$this->setClientId($credentials->client_id);
			$this->api = "https://app.pingboard.com/api/v2";
			$this->auth_url = "https://app.pingboard.com/oauth/token?grant_type=client_credentials";
			$this->payload = [
				'client_id' => $credentials->client_id,
				'client_secret' => $credentials->client_secret
			];
			
			$this->curl = new CurlRequest();
			$response = $this->curl->request("POST", $this->auth_url, $this->payload, []);
			
			$this->token = $response->json();
			$this->curl->setRequestHeaders([
				"Authorization" => "Bearer " . $this->token['access_token']
			]);
		}
		
		public function getInfo() {
			return $this->curl->getRequestHeaders();
		}
		
		private function slugify($string, $replacement = '-') {
			$slug = strtolower(preg_replace('/[^A-z0-9-]+/', $replacement, $string));
			return trim($slug, $replacement);
		}
		
		public function getToken() {
			return $this->token['access_token'];
		}
		
		public function setClientSecret(string $clientSecret): void {
			$this->clientSecret = $clientSecret;
		}
		
		public function getClientSecret(): string {
			return $this->clientSecret;
		}
		
		public function setClientId(string $clientId): void {
			$this->clientId = $clientId;
		}
		
		public function getClientId(): string {
			return $this->clientId;
		}
		
		public function getUsersSimple() {
			$url = "$this->api/users?group_id=1574888&page_size=300";
			$headers = [
				"Authorization" => "Bearer " . $this->token['access_token']
			];
			$users = $this->curl->request("GET", $url, "", $headers);
			return $users;
		}
		
		public function getUsers() {
			$users = $this->curl->get("$this->api/users?group_id=1574888&page_size=300", false, $this->token)['users'];
			
			$users = array_map(function($item) {
				$id = $item['id'];
				$locations = $item['links']['locations'] ?? [];
				$departments = $item['links']['departments'] ?? [];
			
				return [
					'id' => $item['id'],
					'first_name' => $item['first_name'],
					'last_name' => $item['last_name'],
					'nickname' => $item['nickname'],
					'job_title' => $item['job_title'],
					'email' => $item['email'],
					'phone' => $item['phone'],
					'office_phone' => $item['office_phone'],
					'reports_to_id' => $item['reports_to_id'],
					'locations' => implode(",", $locations),
					'departments' => implode(",", $departments),
					'image' => $item['avatar_urls']['original'] ?? false
				];
			}, $users);
			
			return $users;
		}
		
		public function getGroups() {
			$groups = $this->curl->get("$this->api/groups?type=department", false, $this->token)['groups'];
			
			return array_map(function($item) {
				unset($item['created_at']);
				unset($item['updated_at']);
				unset($item['links']); // remove user listing
				unset($item['leader']['avatar_urls']);
				
				$item['leaders'] = array_map(function($leader) {
					unset($leader['avatar_urls']);
					return $leader;
				}, $item['leaders']);
				
				return $item;
			}, $groups);
		}
	}
	
	$credentials = json_decode(file_get_contents('../config.json'))->pingboard;
	
	$pingboard = new Pingboard($credentials);
	
	//header('Content-Type: application/json');
	echo $pingboard->getUsersSimple();
	
	//echo json_encode($pingboard->getUsersSimple()->json(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>