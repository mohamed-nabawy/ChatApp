<?php
	require('ChatApp/chat/backend/models/times.php');
	require('ChatApp/chat/backend/migration-functions.php');

	syncTableAndClass($conn, "message", $times);
		
?>