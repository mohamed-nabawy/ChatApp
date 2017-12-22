<?php
	require('../models/directories.php');
	require('../migration-functions.php');

	syncTableAndClass($conn, "directories", $directories);
?>