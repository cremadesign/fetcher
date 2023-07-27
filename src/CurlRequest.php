<?php

	namespace Crema;
	
	class CurlRequest extends \stdClass {
		public function __construct() {
			$headers = [
				"Content-Type" => "application/json"
			];
			
			$this->requestHeadersList = $headers;
			$this->requestHeaders = $this->addRequestHeaders($headers);
			
			return $this;
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
		
		public function getResult() {
			return $this->result;
		}
		
		public function getBody() {
			return $this->body;
		}
		
		public function json() {
			if (isset($this->error))
				return $this->error;
			
			return json_decode($this->body, true);
		}
		
		public function object() {
			if (isset($this->error))
				return $this->error;
			
			return json_decode($this->body);
		}
		
		public function getRequest() {
			return $this->headersToArray($this->request);
		}
		
		public function addRequestHeaders($headers = []) {
			$this->requestHeadersList = array_merge($this->requestHeadersList, $headers);
			
			$this->requestHeaders = array_map(fn($k, $v) =>
				"$k: $v", array_keys($this->requestHeadersList), $this->requestHeadersList);
			
			return $this->requestHeaders;
		}
		
		public function removeRequestHeaders() {
			$this->requestHeaders = [];
			$this->requestHeadersList = [];
		}
		
		// Gets Response Headers
		public function getHeaders() {
			return $this->headersToArray($this->responseHeaders);
		}
		
		public function getRequestHeaders() {
			return $this->requestHeadersList;
		}
		
		public function request($type, $url, $payload = "", $headers = []) {
			$this->ch = curl_init();
			$this->requestHeaders = $this->addRequestHeaders($headers);
			
			$options = [
				CURLOPT_CUSTOMREQUEST => $type,
				CURLOPT_URL => $url,
				CURLOPT_HTTPHEADER => $this->requestHeaders,
				CURLOPT_HEADER => 1,
				CURLINFO_HEADER_OUT => true,
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_VERBOSE => 1
			];
			
			if ($this->str_contains($_SERVER["HTTP_HOST"], '.test')) {
				$options[CURLOPT_SSL_VERIFYPEER] = false;
			}
			
			if ($type == "POST" and $payload) {
				$contentType = $this->requestHeadersList['Content-Type'] ?? false;
				
				// Returns a string containing the JSON representation of the supplied value
				if (! is_scalar($payload) && $contentType == 'application/json') {
					$payload = json_encode((array) $payload);
				}
				
				$options[CURLOPT_POST] = true;
				$options[CURLOPT_POSTFIELDS] = $payload;
			} else {
				unset($options[CURLOPT_POSTFIELDS]);
			}
			
			curl_setopt_array($this->ch, $options);
			
			$result = curl_exec($this->ch);
			$header_size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
			
			$this->request = curl_getinfo($this->ch, CURLINFO_HEADER_OUT);
			$this->result = $result;
			$this->responseHeaders = substr($result, 0, $header_size);
			$this->code = (int)substr($this->responseHeaders, 9, 3);
			$this->body = substr($result, $header_size);
			
			if ($e = curl_error($this->ch)) {
				$this->error = $e;
			}
			
			curl_close($this->ch);
			
			return $this;
		}
		
		public function get($url, $payload = "", $headers = []) {
			if ($payload) {
				$url = "$url?" . http_build_query($payload);
			}
			
			return $this->request("GET", $url, "", $headers);
		}
		
		public function post($url, $payload = "", $headers = []) {
			return $this->request("POST", $url, $payload, $headers);
		}
		
		public function __toString() {
			return $this->body;
		}
	}
	
?>
