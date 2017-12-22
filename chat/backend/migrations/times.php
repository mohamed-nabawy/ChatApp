<?php
	require('../models/times.php');
	require('../migration-functions.php');

	syncTableAndClass($conn, "message", $times);
?>