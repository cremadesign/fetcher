<?php

	namespace Crema;
	
	class CurlRequest {
		public function __construct() {
			$this->ch = curl_init();
		}
		
		public function stringify($json) {
			return json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		}
		
		private function str_contains($haystack, $needle) {
			return (strpos($haystack, $needle)) ? true : false;
		}
		
		private function headersToArray($header_text) {
			$headers = [];
			$header_lines = array_filter(explode("\r\n", $header_text));
		
			foreach ($header_lines as $i => $line) {
				if ($i > 0) {
					list ($key, $value) = explode(': ', $line);
					$headers[$key] = $value;
				}
			}
			
			return $headers;
		}
		
		public function getHeaders() {
			return headersToArray($this->headers);
		}
		
		public function getErrors() {
			if (isset($this->error))
				return $this->error;
			
			return false;
		}
		
		public function getCode() {
			if (isset($this->error))
				return $this->error;
			
			return $this->code;
		}
		
		public function json() {
			if (isset($this->error))
				return $this->error;
			
			return json_decode($this->body, true);
		}
		
		public function getRequest() {
			return headersToArray($this->request);
		}
		
		public function setRequestHeaders($headers = []) {
			$this->requestHeaders = [];
			$this->headers = array_merge([
				"Content-Type" => "application/json"
			], $headers);
			
			foreach($this->headers as $key=>$val){
				$this->requestHeaders[] = "$key: $val";
			}
			
			return $this->requestHeaders;
		}
		
		public function getRequestHeaders() {
			return $this->requestHeaders;
		}
		
		public function request($type, $url, $payload = "", $headers = []) {
			$headers = $this->setRequestHeaders($headers);
			
			$options = [
				CURLOPT_CUSTOMREQUEST => $type,
				CURLOPT_URL => $url,
				CURLOPT_HTTP_VERSION => '1.1',
				CURLOPT_HTTPHEADER => $headers,
				CURLOPT_HEADER => 1,
				CURLINFO_HEADER_OUT => true,
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_VERBOSE => 1
			];
			
			if ($this->str_contains($_SERVER["HTTP_HOST"], '.test')) {
				$options[CURLOPT_SSL_VERIFYPEER] = false;
			}
			
			if ($payload) {
				$options[CURLOPT_POSTFIELDS] = json_encode($payload);
			}
			
			curl_setopt_array($this->ch, $options);
			
			$result = curl_exec($this->ch);
			$header_size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
			
			if ($e = curl_error($this->ch)) {
				$this->error = $e;
			} else {
				$this->request = curl_getinfo($this->ch, CURLINFO_HEADER_OUT);
				$this->headers = substr($result, 0, $header_size);
				$this->code = (int)substr($this->headers, 9, 3);
				$this->body = substr($result, $header_size);
			}
			
			curl_close($this->ch);
			
			return $this;
		}
		
		public function get($url, $payload = "") {
			if ($payload) {
				$url = "$url?" . http_build_query($payload);
			}
			
			return $this->request("GET", $url, "");
		}
		
		public function post($url, $payload = "") {
			return $this->request("POST", $url, $payload);
		}
	}
	
?>
