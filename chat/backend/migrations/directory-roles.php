<?php
	require('../models/directory-roles.php');
	require('../migration-functions.php');

	syncTableAndClass($conn, "directoryroles", $directoryroles);
?>