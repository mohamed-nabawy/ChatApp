<?php


require('../connection.php');
require('migration-classes.php');

// // $q = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'user' and table_schema = 'cafetria'";
// // $q1 = "SELECT * FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'customer' and table_schema = 'cafetria'";

// // if (!$conn->query($q1)) {
// // 	echo $conn->error;
// // }

// // $result = $conn->query($q);

// // while ( $row = mysqli_fetch_assoc($result) ) {
// // 	echo $row['DATA_TYPE'] . "\n";
// // }
// // var_dump(mysqli_fetch_all($conn->query($q1), MYSQLI_ASSOC));


// // $arr = objectTableMapper::getTableColumns($conn, 'order')['Id'];
// // var_dump($arr);
// $x1 = 6;
// $x2 = 5;

// class x {
// public static function f1($x1, $x2) {
// 	echo $x1 . $x2;
// }


// public static function f2($x1, $x2) {
// 	echo $x2 . $x1;
// }
// }

// $x = function() use($x1, $x2) {
// 	x::f1($x1, $x2);
// };

// $y = function() use($x1, $x2) {
// 	x::f2($x1, $x2);
// };
// global $arr;
// $arr = [$x];
// array_push($GLOBALS['arr'], $y);

// function runReversers() {
// 	$len = count($GLOBALS['arr']);

// 	for ($i = $len - 1; $i >= 0; $i--) {

// 		$GLOBALS['arr'][$i]();
// 		exit();
// 	}
// }

// runReversers();

// // foreach ($GLOBALS['arr'] as $key => $value) {
// // 	exit();
// // 	$value();

// // }

// // $sql = "ALTER TABLE `try` ADD CONSTRAINT FOREIGN KEY (`userId`) REFERENCES `user`(`Id`);";
// // 		$res = $conn->query($sql);

// // 		var_dump($res);



var_dump(objectTableMapper::getTableColumns($conn, 'users'));