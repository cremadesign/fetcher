<?php

	define("JSON_PRETTIER", JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);

	function dump($input) {
		$type = gettype($input);
		
		if ($type == "object" or $type == "array") {
			header('Content-Type: application/json');
			echo json_encode((array) $input, JSON_PRETTIER);
		} else {
			echo $input;
		}
	}

?>
