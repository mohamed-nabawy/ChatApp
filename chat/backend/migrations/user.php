<?php
	require('chat/backend/connection.php');
	require('chat/backend/models/user.php');

	function getUserFields($conn) {
		$result = $conn->query("show columns from user");
		// while ($row = mysqli_fetch_assoc($result) ) {
		// 	print_r($row);
		// }
		return $result;
	}

	function compareTableWithClass($conn) {
		while ($row = mysqli_fetch_assoc( getUserFields($conn) ) ) {
			// comparison goes here
		}
	}

	function createUser() {
		$val = $conn->query('select 1 from `user` limit 1'); // use this to check existence of table

		if (!$val) {
			$conn->query($create);
		}
	}

	function DeleteUser() {
		$val = $conn->query('select 1 from `user` limit 1'); // use this to check existence of table
		$setForeignKeyChecks = "set foreign_key_checks = 0";

		if ($val) {
			$conn->query($setForeignKeyChecks);
			$conn->query($drop);
		}
	}

	getUserFields($conn);

	// $create = "create table `chat`.`user` (
	// 	`Id` int(11) not null auto_increment primary key,
	// 	`UserName` varchar(100) not null,
	// 	`FirstName` varchar(100) not null,
	// 	`LastName` varchar(100) not null,
	// 	`Email` varchar(200) not null,
	// 	`PhoneNumber` varchar(13) not null,
	// 	`RoleId` int(11) not null,
	// 	`Confirmed` boolean not null default false,
	// 	`Gender` boolean not null default 1,
	// 	`DateOfBirth` date not null default '2017-01-01',
	// 	`PasswordHash` varchar(100) collate utf8_unicode_ci not null,
	// 	`Image` varchar(300) not null default 'Backend/Uploads/myimage.jpg'
	// 	) engine = InnoDB; ";
	// $drop = "drop table `chat`.`user`";
?>