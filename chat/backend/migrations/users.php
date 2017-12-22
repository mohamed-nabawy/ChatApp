<?php
	require('../models/users.php');
	require('../migration-functions.php');

	syncTableAndClass($conn, "users", $users);	
?>