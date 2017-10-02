<?php
	require('chat/backend/connection.php');

	$create = "create table `chat`.`message` (
		`Id` int(11) not null auto_increment primary key,
		`Content` text not null,
		`SentFrom` int(11) not null,
		`SentTo` int(11) not null,
		`DateId` int(11) not null,
		`TimeId` int not null,
		foreign key(SentFrom) references `user`(Id),
		foreign key(SentTo) references `user`(Id),
		foreign key(DateId) references `Date`(Id),
		foreign key(TimeId) references `Time`(Id)
		) engine = InnoDB; ";
	$drop = "drop table `chat`.`message`";
	$setForeignKeyChecks = "set foreign_key_checks = 0";

	$val = $conn->query('select 1 from `message` limit 1'); // use this to check existence of table

	if ($val) {
		$conn->query($setForeignKeyChecks);
		$conn->query($drop);
	}
	elseif (!$val) {
		$conn->query($create);
	}
?>