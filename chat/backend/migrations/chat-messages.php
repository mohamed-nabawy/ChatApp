<?php
	require('ChatApp/chat/backend/models/chat-messages.php');
	require('ChatApp/chat/backend/migration-functions.php');

	syncTableAndClass($conn, "chatmessages", $chatmessages);
		
?>