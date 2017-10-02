<?php
	require('chat/backend/connection.php');

	$create = "create table `dates` (
	  `Id` int(11) not null auto_increment primary key,
	  `Date` date not null,
	) engine=InnoDB auto_increment=44 default charset=utf8 collate=utf8_unicode_ci;";
	$drop = "drop table `chat`.`dates`";
	$setForeignKeyChecks = "set foreign_key_checks = 0";

	$val = $conn->query('select 1 from `dates` limit 1'); // use this to check existence of table

	if ($val) {
		$conn->query($setForeignKeyChecks);
		$conn->query($drop);
	}
	elseif (!$val) {
		$conn->query($create);
	}
?>