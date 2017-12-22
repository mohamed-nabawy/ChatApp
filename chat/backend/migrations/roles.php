<?php
	require('../models/roles.php');
	require('../migration-functions.php');

	syncTableAndClass($conn, "roles", $roles);
?>