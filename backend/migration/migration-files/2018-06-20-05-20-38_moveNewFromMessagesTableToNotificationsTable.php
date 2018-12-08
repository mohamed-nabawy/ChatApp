<?php
	require_once(__DIR__ . '/../migration-classes.php');
	require_once(__DIR__ . '/../migrator.php');

	$migrator = new migrator();

	$up = function($conn) {
		column::dropColumn($conn, 'chatmessages', 'new');
		column::addColumn($conn, 'notifications', 'new', ['type' => 'tinyint', 'default' => 0]);
	};

	$down = function($conn) {
		column::dropColumn($conn, 'notifications', 'new');
		column::addColumn($conn, 'chatmessages', 'new', ['type' => 'tinyint', 'default' => 0]);
	};
	