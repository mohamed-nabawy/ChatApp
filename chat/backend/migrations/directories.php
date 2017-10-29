<?php
	require('ChatApp/chat/backend/models/directories.php');
	require('ChatApp/chat/backend/migration-functions.php');

	syncTableAndClass($conn, "directories", $directories);
		
?>