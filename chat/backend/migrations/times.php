<?php
	require('chat/backend/connection.php');

	$create = "create table `times` (
	  `Id` int(11) not null auto_increment primary key,
	  `Time` time not null,
	  unique key `time` (`Time`)
	) engine=InnoDB auto_increment=1441 default charset=utf8 collate=utf8_unicode_ci;";
	$drop = "drop table `chat`.`times`";
	$setForeignKeyChecks = "set foreign_key_checks = 0";

	$val = $conn->query('select 1 from `times` limit 1'); // use this to check existence of table

	if ($val) {
		$conn->query($setForeignKeyChecks);
		$conn->query($drop);
	}
	elseif (!$val) {
		$conn->query($create);
	}
?>