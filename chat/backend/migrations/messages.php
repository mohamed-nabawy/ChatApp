<?php
	require('ChatApp/chat/backend/models/messages.php');
	require('ChatApp/chat/backend/migration-functions.php');

	syncTableAndClass($conn, "message", $message);
		
?>