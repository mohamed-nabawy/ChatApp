<?php
	require('ChatApp/chat/backend/models/roles.php');
	require('ChatApp/chat/backend/migration-functions.php');

	syncTableAndClass($conn, "roles", $roles);
		
?>