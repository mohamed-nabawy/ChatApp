<?php
	require('ChatApp/chat/backend/models/users.php');
	require('ChatApp/chat/backend/migration-functions.php');

	syncTableAndClass($conn, "users", $users);
		
?>