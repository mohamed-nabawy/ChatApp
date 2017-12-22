<?php
	require('../models/class-users.php');
	require('../migration-functions.php');

	syncTableAndClass($conn, "classusers", $classusers);
		
?>