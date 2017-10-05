<?php
	// make the parameters const so none can play with the connection
	define("DB_SERVER", "localhost"); 
	define("DB_USER", "root");
	define("DB_PASS", "");
	define("DB_NAME", "chatapp");

    $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME); // open connection

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    mysqli_set_charset($conn, 'utf8');
?>