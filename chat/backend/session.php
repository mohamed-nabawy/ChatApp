<?php
	if (!session_id()) {
    	session_start();
	}
	
	// to use session to send a message to another page
	function message() {
		if ( isset($_SESSION["message"]) ) {
			$output = "<div class=\"message\">";
			$output .= ($_SESSION["message"]);
			$output .= "</div>";
			
			// clear message after use
			$_SESSION["message"] = null;
			
			return $output;
		}
	}

	// to use session to send a errors messages to another page
	function errors() {
		if ( isset($_SESSION["errors"]) ) {
			$errors = $_SESSION["errors"];
			
			// clear message after use
			$_SESSION["errors"] = null;
			
			return $errors;
		}
	}
?>