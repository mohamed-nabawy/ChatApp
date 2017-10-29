<?php
	require('ChatApp/chat/backend/models/class-users.php');
	require('ChatApp/chat/backend/migration-functions.php');

	syncTableAndClass($conn, "classusers", $classusers);
		
?>