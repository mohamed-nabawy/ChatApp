<?php
	$arr = [];

	require('../models/chat-messages.php');
	require('../models/class-users.php');
	require('../models/classes.php');
	require('../models/dates.php');
	require('../models/directories.php');
	require('../models/directory-roles.php');
	require('../models/roles.php');
	require('../models/times.php');
	require('../models/users.php');
	require('../models/last-messages.php');
	require('migration-functions.php');

	foreach ($arr as $key => $value) {
		syncTableAndClass($conn, $key, $value);
	}
?>