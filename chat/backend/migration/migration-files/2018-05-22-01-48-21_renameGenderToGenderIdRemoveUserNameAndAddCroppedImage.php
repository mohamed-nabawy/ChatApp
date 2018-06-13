<?php
	require_once(__DIR__ . '/../migration-classes.php');
	require_once(__DIR__ . '/../migrator.php');

	$migrator = new migrator();

	$up = function($conn) {
		column::renameColumn($conn, 'users', 'gender', 'genderId');
		column::modifyColumn($conn, 'users', 'genderId', ['max' => 2, 'default' => 1]);
		column::dropColumn($conn, 'users', 'userName');
		column::addColumn($conn, 'users', 'croppedImage', ['type' => 'varchar', 'max' => 300]);
	};

	$down = function($conn) {
		column::dropColumn($conn, 'users', 'croppedImage');
		column::addColumn($conn, 'users', 'userName', ['type' => 'varchar', 'max' => 100]);
		column::modifyColumn($conn, 'users', 'genderId', ['max' => 1, 'default' => 0]);
		column::renameColumn($conn, 'users', 'genderId', 'gender');
	};
	