<?php
	require('ChatApp/chat/backend/models/message.php');
	require('ChatApp/chat/backend/migration-functions.php');

	syncTableAndClass($conn, "message", $message);
		
?>