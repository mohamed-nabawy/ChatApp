<?php
	require('ChatApp/chat/backend/models/classes.php');
	require('ChatApp/chat/backend/migration-functions.php');

	syncTableAndClass($conn, "classes", $classes);
		
?>