<?php
	require('../models/chat-messages.php');
	require('../migration-functions.php');

	syncTableAndClass($conn, "chatmessages", $chatmessages);
?>