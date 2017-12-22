<?php
	require('../models/dates.php');
	require('../migration-functions.php');

	syncTableAndClass($conn, "message", $dates);
?>