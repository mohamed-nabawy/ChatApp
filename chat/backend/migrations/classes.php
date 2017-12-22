<?php
	require('../models/classes.php');
	require('../migration-functions.php');

	syncTableAndClass($conn, "classes", $classes);
?>