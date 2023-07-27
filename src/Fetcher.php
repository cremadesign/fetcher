<?php

	namespace Crema;
	
	// Simple replacement for file_get_contents that bypasses SSL on localhost
	class Fetcher extends \stdClass {
		public function request($url, $json = true) {
			if (strpos($_SERVER["HTTP_HOST"], '.test') !== false) {
				$context = stream_context_create([
					'ssl' => [
						'verify_peer' => false
					]
				]);
		
				$contents = file_get_contents($url, FILE_TEXT, $context);
			} else {
				$contents = file_get_contents($url);
			}
		
			if ($json) return json_decode($contents, true);
			return $contents;
			
			return $json ? json_decode($contents, true) : $contents;
		}
	}
	
?>
