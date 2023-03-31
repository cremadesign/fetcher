<?php

	namespace Crema;
	
	class CurlRequest {
		public function __construct() {
			
		}
		
		public function request($type, $url, $payload = "", $token = "") {
			$this->ch = curl_init($url);
			
			curl_setopt_array($this->ch, [
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_HTTPHEADER => [
					"Content-Type: application/json"
				],
				CURLOPT_VERBOSE => 1,
				CURLOPT_HTTP_VERSION => '1.1'
			]);
	
			if (strpos($_SERVER["HTTP_HOST"], '.test') !== false) {
				curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
			}
	
			if ($token) {
				curl_setopt($this->ch, CURLOPT_HTTPHEADER, [
					"Content-Type: application/json",
					"Authorization: Bearer $token"
				]);
			}
	
			if ($type == "POST"){
				curl_setopt($this->ch, CURLOPT_POST, true);
				curl_setopt($this->ch, CURLOPT_POSTFIELDS, $payload);
			}
	
			$result = curl_exec($this->ch);
			curl_close($this->ch);
	
			return json_decode($result, true);
		}
	
		public function get($url, $payload = "", $token = "") {
			if ($payload) {
				$url = "$url?" . http_build_query($payload);
				$payload = "";
			}
	
			return $this->request("GET", $url, $payload, $token);
		}
	
		public function post($url, $payload = "", $token = "") {
			return $this->request("POST", $url, $payload, $token);
		}
	}
	
?>
