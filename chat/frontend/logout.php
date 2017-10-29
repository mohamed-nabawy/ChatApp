<?php
	require('ChatApp/chat/backend/functions.php');
	//require('fbConfig.php');
 

	// v1: simple logout
	// session_start();
	//$_SESSION["user_id"] = null;
	//$_SESSION["user_name"] = null;
	//$_SESSION["customer_id"] = null;

	// Include FB config file

	// Remove access token from session
	//unset($_SESSION['facebook_access_token']);

	// v2: destroy session
	// assumes nothing else in session to keep
	$_SESSION = array();
	if ( isset( $_COOKIE[session_name()] ) ) {
	  setcookie(session_name(), null, time()-42000, '/');
	}
	session_destroy();
	redirect_to("login.php");
?>