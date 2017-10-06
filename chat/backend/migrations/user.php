<?php
	require('ChatApp/chat/backend/models/user.php');
	require('ChatApp/chat/backend/migration-functions.php');

	syncTableAndClass($conn, "user", $user);
		
?>