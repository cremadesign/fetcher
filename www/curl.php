<?php

	function headersToArray($header_text) {
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
	
	class Fetcher {
		public function __construct() {
			$this->ch = curl_init();
		}
		
		public function stringify($json) {
			return json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		}
		
		public function request($url) {
			$headers = [
				"Content-Type: application/json",
				"X-Custom: something",
				"X-Apple-Tz: 0"
			];
			
			$options = [
				CURLOPT_URL => $url,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_HTTPHEADER => $headers,
				CURLOPT_HEADER => 1,
				CURLINFO_HEADER_OUT => true,
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_VERBOSE => 1
			];
			
			if (strpos($_SERVER["HTTP_HOST"], '.test') !== false) {
				$options[CURLOPT_SSL_VERIFYPEER] = false;
			}
			
			curl_setopt_array($this->ch, $options);
			
			$result = curl_exec($this->ch);
			$header_size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
			$header_string = substr($result, 0, $header_size);
			$body = substr($result, $header_size);
			
			if ($e = curl_error($this->ch)) {
				$this->response = $e;
			} else {
				$this->response = [
					'req' => curl_getinfo($this->ch, CURLINFO_HEADER_OUT),
					'request' => headersToArray(curl_getinfo($this->ch, CURLINFO_HEADER_OUT)),
					'code' => (int)substr($header_string, 9, 3),
					'headers' => headersToArray($header_string),
					'body' => json_decode($body, true)
				];
			}
			
			curl_close($this->ch);
			
			return $this->response;
		}
	}
	
	$fetcher = new Fetcher();
	$response = $fetcher->request("https://dummyjson.com/products/2");
	header('Content-Type: application/json');
	echo $fetcher->stringify($response);

?>
