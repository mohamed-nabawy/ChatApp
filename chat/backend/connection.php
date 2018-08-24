<?php
	// make the parameters const so none can play with the connection
	
	if (strpos($_SERVER['REMOTE_ADDR'], '127.0.0') !== false) {
		define("DB_SERVER", "localhost"); 
		define("DB_USER", "root");
		define("DB_PASS", "");
		define("DB_NAME", "chat");

	    $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME); // open connection

	    if ($conn->connect_error) {
	        die("Connection failed: " . $conn->connect_error);
	    }
	    
	    mysqli_set_charset($conn, 'utf8');
	}
	else {
		$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

		$server = $url["host"];
		$username = $url["user"];
		$password = $url["pass"];
		$db = substr($url["path"], 1);

		$conn = new mysqli($server, $username, $password, $db);
	}
?>