<?php
	require('ChatApp/chat/backend/models/dates.php');
	require('ChatApp/chat/backend/migration-functions.php');

	syncTableAndClass($conn, "message", $dates);
		
?>