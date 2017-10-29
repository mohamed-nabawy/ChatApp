<?php
	require('ChatApp/chat/backend/models/directory-roles.php');
	require('ChatApp/chat/backend/migration-functions.php');

	syncTableAndClass($conn, "directoryroles", $directoryroles);
		
?>