<?php
	require('../connection.php');
	require('../columnnames/naming-functions.php');

	function getTableFields($conn, $tableName) {
		$result = $conn->query("show columns from `$tableName`");

		return $result;
	}

	function createTableIfNotExisted($conn, $tableName, $object) {
		$val = $conn->query("select 1 from `$tableName` limit 1"); // use this to check existence of table

		if (!$val) { // table doesn't exist so we will create it from the model
			// iterate over object attributes
			$create = "create table `$tableName` (";

			foreach ($object as $key => $value) {
				addOrUpdateField($conn, $create, $key, $value, 6, $tableName);
				$create .= ",";
			}

			if ($create[(strlen($create) ) - 1] == ',') {
				$create[(strlen($create) ) - 1] = ' ';
			}

			$create .= ");";
			$r = $conn->query($create);

			if ($r) {
				echo "table created";
			}
			else {
				echo "error: ", $conn->error;
			}

			exit();
		}
	}

	function addOrUpdateField($conn, &$statment, &$key, &$value, $flag = null, $tableName = null) {
		if ($flag === 2) {
			dropPrimaryKeyIfExisted($conn, $tableName);
		}

		$statment .= "`$key`";
		// check type

		$type = $value['type'];

		if ( $type == "string" && !isset($value['max']) ) {
			$statment .= " varchar(255)";
		}
		elseif ($type == 'string') {
			$statment .= " varchar";
		}
		elseif ($type == "int") {
			$statment .= " int";
		}
		elseif ($type == "bool") {
			$statment .= " bool";
		}
		elseif ($type == "date") {
			$statment .= " date";
		}
		elseif ($type == "text") {
			$statment .= " text";
		}

		// check if max is set
		if ( isset($value['max']) ) {
			$x = $value['max'];
			$statment .= "($x)";
		}

		// check if default is set
		if ( isset($value['default']) ) {
			$y = $value['default'];
			$statment .= " default $y";
		}

		if ( isset($value['newName']) ) {
			$fh = fopen('../columnnames/newcolumnnames.php', 'a');
			//$var1 = "$sql";
			//$conn1 = "$conn";
			fwrite($fh, "\n" . "$" . "sql = " . '"' . "insert into columnnames (`oldColumnName`, `newColumnName`, `tableName`) values ('{$key}', '{$new}', '{$tableName}')" . '";' . "\n" . "$" . "conn" . "->query(" . "$" . "sql);");
			$dir = "../models/{$tableName}.php";
			$n = explode("\n", $dir);

			foreach($n as $line) {
				echo "hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh";
				echo strpos($line, "public ${$key}");
			    if ( strpos($line, "public ${$key}") > -1) {
			    	echo "rgogkpfdokgpodfkgpokdfgp";
			        // do whatever you want with the line
			        var_dump(str_replace($key, $new, $line));
			        str_replace(", " . "'" . "newName" . "'" . " =>" . "'" . $new . "'", "", $line);
			    }
			}
			$contents = file_get_contents($dir);
			$contents = str_replace($line, '', $contents);
			file_put_contents($dir, $contents);


			$new = $value['newName'];
			updateColumnName($conn, $tableName, $key, $new);
			


		}

		// check other paramters
		if ( in_array("primary key", $value) && ($flag === null || $flag === 6) ) {
			if ($conn->query("select 1 from `$tableName` limit 1") === false || mysqli_fetch_assoc( $conn->query("select 1 from `$tableName` limit 1") )['num_rows'] == 0) {
				$statment .= " primary key";
			}
			else {
				$pr = "SHOW INDEXES FROM `$tableName` WHERE Key_name = 'PRIMARY'";
				$pr1 = $conn->query($pr);

				if (!$pr1) {
					echo "error: ", $conn->error;
				}
			}
		}

		if ( in_array("not null", $value) ) {
			$statment .= " not null";
		}

		if ( in_array("auto_increment", $value) && $flag !== 3) {
			$statment .= " auto_increment";
		}

		if ( in_array("unique", $value) ) {
			$statment .= " unique";
		}

		if ( isset($value["foreign key"]) && ($flag === null || $flag === 6) ) {
			$tableNameAndColumn = $value['foreign key'];
			$statment .= ",";
			$statment .= "constraint `$key` foreign key($key) references $tableNameAndColumn";
		}

		$conn->query("set foreign_key_checks = 0");
		
		if ($flag === 3) {
			$sql = "SELECT * FROM `information_schema`.`KEY_COLUMN_USAGE` WHERE `REFERENCED_TABLE_NAME` = '$tableName' AND `REFERENCED_COLUMN_NAME` = '$key'";
			$r = $conn->query($sql);

			if ($r) {
				echo "can't remove primary key because it's referenced from another table";
			}
			else {
				dropPrimaryKeyIfExisted($conn, $tableName);
			}
		}
		else {
			if ($flag !== 6) {
				$result = $conn->query($statment);

				if ($result) {

				}
				else {
					echo $statment;
					print_r(3);
					echo "error: ", $conn->error, "\n";
				}
			}
		}
	}

	function DeleteTable($conn, $tableName) {
		$val = $conn->query("select 1 from `$tableName` limit 1"); // use this to check existence of table

		if ($val) {
			$setForeignKeyChecks = "set foreign_key_checks = 0";
			$drop = "drop table `$tableName`";
			$conn->query($setForeignKeyChecks);
			$conn->query($drop);
		}
	}

	// function constructModelObjectFromTableFields($conn, $tableName) {
	// 	$result = getTableFields($conn, $tableName);
	// 	$arr = array();
	// 	$flag = 0;

	// 	while ( $row = mysqli_fetch_assoc($result) ) {
	// 		$arr[$row['Field']] = array();

	// 		if (strpos($row['Type'], "int") !== false && strpos($row['Type'], "tiny") === false) {
	// 			$arr[$row['Field']]['type'] = "int";
	// 		}
	// 		elseif (strpos($row['Type'], "varchar") !== false) {
	// 			$arr[$row['Field']]['type'] = "string";
	// 		}
	// 		elseif (strpos($row['Type'], "date") !== false) {
	// 			$arr[$row['Field']]['type'] = "date";
	// 		}
	// 		elseif (strpos($row['Type'], "time") !== false) {
	// 			$arr[$row['Field']]['type'] = "time";
	// 		}
	// 		elseif (strpos($row['Type'], "tinyint") !== false) {
	// 			$arr[$row['Field']]['type'] = "bool";
	// 		}
	// 		elseif (strpos($row['Type'], "text") !== false) {
	// 			$arr[$row['Field']]['type'] = "text";
	// 		}

	// 		if (strpos($row['Type'], "(") !== false && strpos($row['Type'], "tinyint") === false) {
	// 			$start = strpos($row['Type'], "(");
	// 			$end = strpos($row['Type'], ")");
	// 			$length = $end - $start;
	// 			$max = substr($row['Type'], $start + 1, $length - 1);
	// 			$arr[$row['Field']]['max'] = $max;
	// 		}

	// 		if ($row['Null'] == "NO") {
	// 			array_push($arr[$row['Field']], "not null");
	// 		}

	// 		if ($row['Key'] == "PRI") {
	// 			array_push($arr[$row['Field']], "primary key");
	// 		}
	// 		elseif ($row['Key'] == "UNI") {
	// 			array_push($arr[$row['Field']], "unique");
	// 		}
	// 		elseif ($row['Key'] == "MUL") {
	// 			$foriegnKeys = getAllForeignKeys($conn, $tableName);

	// 			while ($row1 = mysqli_fetch_assoc($foriegnKeys) ) {
	// 				if ($row1['column_name'] == $row['Field']) {
	// 					$foreignTable = $row1['foreign_table'];
	// 					$foreignColumn = $row1['foreign_column'];
	// 					$arr[$row['Field']]['foreign key'] = "$foreignTable($foreignColumn)";
	// 					break;
	// 				}
	// 			}
	// 		}

	// 		if ($row['Default'] !== null && $row['Default'] !== "") {
	// 			$x = $row['Default'];
	// 			array_push($arr[$row['Field']], "default $x");
	// 		}

	// 		if ($row['Extra'] !== null && $row['Extra'] !== "") {
	// 			$x = $row['Extra'];
	// 			array_push($arr[$row['Field']], $x);
	// 		}
	// 	}

	// 	return $arr;
	// }

	function getAllForeignKeys($conn, $tableName) {
		$foreignKeysStatment = "SELECT `column_name`, `constraint_name`, `referenced_table_schema` AS foreign_db, `referenced_table_name` AS foreign_table, `referenced_column_name`  AS foreign_column FROM `information_schema`.`KEY_COLUMN_USAGE` WHERE `constraint_schema` = SCHEMA() AND `table_name` = '$tableName' AND `referenced_column_name` IS NOT NULL ORDER BY `column_name`;";
		$res = $conn->query($foreignKeysStatment);

		if ($res) {
		}
		else {
			print_r(6);
			echo "error: ", $conn->error;
		}

		return $res;
	}

	function addFieldToTableIfNotExisted($conn, $tableName, $object) {
		$tableFields = constructModelObjectFromTableFields($conn, $tableName);
		$object = (array)$object;

		foreach ($object as $key => $value) {
			if (!array_key_exists($key, $tableFields) ) {
				echo "1";
				$alter = "alter table `$tableName` add column ";
				addOrUpdateField($conn, $alter, $key, $object[$key], 1);
				$r = false;

				if (isset($object[$key]['foreign key']) ) {
					$v = $object[$key]['foreign key'];
					$conn->query("set foreign_key_checks = 0");
					$r = $conn->query("alter table `$tableName` add constraint $key foreign key ($key) references $v");
				}
			}
		}
	}

	function deleteFieldIfExisted($conn, $tableName, $object) {
		$tableFields = constructModelObjectFromTableFields($conn, $tableName, $object);
		$object = (array)$object;

		foreach ($tableFields as $key => $value) {
			if (!array_key_exists($key, $object) ) {
				$alter = "alter table `$tableName` drop column `$key`";

				if (isset($tableFields[$key]["foreign key"]) ) {
					$foreignKeys = getAllForeignKeys($conn, $tableName);

					foreach ($foreignKeys as $value) {
						$x = $value['column_name'];
						$y = $value['constraint_name'];

						if ($x == $key) { // column_name equals the current field
							$r1 = $conn->query("alter table `$tableName` drop foreign key `$y`");
							$r2 = $conn->query("alter table `$tableName` drop index `$key`");

							if ($r1 && $r2) {
								echo "foreign key dropped\n";
							}
							else {
								print_r(9);
								echo "error: ", $conn->error, "\n";
							}
						}
					}
				}

				$result = $conn->query($alter);

				if ($result) {
					echo "column deleted\n";
				}
				else {
					print_r(10);
					echo "error: ", $conn->error, "\n";
				}
			}
		}
	}

	function addDefaultMax(&$object) {
		foreach ($object as &$value) {
			if (!isset($value['max']) && $value['type'] == "int") {
				$value['max'] = "11";
			}
		}
	}

	function dropPrimaryKeyIfExisted($conn, $tableName, $key = null) {
		$pr = "SHOW INDEXES FROM `$tableName` WHERE `Key_name` = 'PRIMARY'";
		$sql = "SELECT * FROM `information_schema`.`KEY_COLUMN_USAGE` WHERE `REFERENCED_TABLE_NAME` = '$tableName' AND `REFERENCED_COLUMN_NAME` = '$key'";
		$r = mysqli_fetch_assoc( $conn->query($sql) );
		$x = mysqli_fetch_assoc( $conn->query($pr) );

		if ( ($x && $x !== null) && !($r !== null) ) {
			$c = "alter table `$tableName` drop primary key";
			$z = $conn->query($c);

			if ($z) {
			}
			else {
				print_r(5);
				echo "error: ", $conn->error, "\n";
			}
		}
		else {
			echo "primary key can't be dropped because it's referenced from another table or it's not existed";
		}
	}

	function handlePrimaryKeyAndAutoIncrement($conn, $key, $value, $tableName, $f = null) {
		if ($f === 1) { // $v is primary key
			echo "error: auto_increment should be set on a primary key";
		}
		elseif ($f === 2) { // $v is auto increment
	 		$alter = "alter table `$tableName` modify column ";
			addOrUpdateField($conn, $alter, $key, $value, 1);
		}
		elseif ($f === 3) {
			$alter = "alter table `$tableName` modify column ";
			addOrUpdateField($conn, $alter, $key, $value, 1);
			dropPrimaryKeyIfExisted($conn, $tableName);
		}
	}

	function updateFieldIfNeeded($conn, $tableName, $object) {
		$conn->query("set foreign_key_checks = 0");
		$tableFields = constructModelObjectFromTableFields($conn, $tableName);
		$object = (array)$object;
		addDefaultMax($object);
		$f = 1;

		foreach ($object as $key => $value) {
			if ( key_exists($key, $tableFields) ) {
				foreach ($object[$key] as $k => $v) {
					if (!in_array($v, $tableFields[$key])  ) {
						$f = $k;

						if ($v === "primary key" || ($v === "auto_increment" && in_array("primary key", $object[$key]) ) ) {
							$alter3 = "alter table `$tableName` modify column ";
							addOrUpdateField($conn, $alter3, $key, $object[$key], null, $tableName);
						}
						elseif ($v === "auto_increment" && !in_array("primary key", $object[$key]) && in_array("primary key", $tableFields[$key]) ) {
							echo "error: auto_increment must be associated with primary key";
						}
						elseif ($k !== "foreign key") {
							$alter = "alter table `$tableName` modify column ";
							addOrUpdateField($conn, $alter, $key, $object[$key], 1, $tableName); // changed 25/3/2018
						}
						else {
							if ( key_exists($k, $tableFields[$key]) ) {
								$foreignKeys = getAllForeignKeys($conn, $tableName);

								foreach ($foreignKeys as $value) {
									$x = $value['column_name'];
									$y = $value['constraint_name'];

									if ($x == $key) { // column_name equals the current field
										$r1 = $conn->query("alter table `$tableName` drop foreign key `$y`");
										$r2 = $conn->query("alter table `$tableName` drop index `$key`");

										if ($r1 && $r2) {
											echo "foreign key dropped\n";
										}
										else {
											echo "error: ", $conn->error, "\n";
										}
									}
								}
							}

							$conn->query("set foreign_key_checks = 0");
							$y = "SELECT * FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME ='$key'";
							$x = $conn->query($y);

							if ( mysqli_num_rows($x) == 0 ) {
								$r = $conn->query("alter table `$tableName` add constraint `$key` foreign key ($key) references $v");

								if ($r) {
									echo "foreign key added\n";
								}
								else {
									echo "error: ", $conn->error, "\n";
								}
							}
						}
					}

					$tableFields = constructModelObjectFromTableFields($conn, $tableName);
				}
				
				foreach ($tableFields[$key] as $k => $v) {
					if ( !in_array($v, $object[$key]) || ( !key_exists($k, $object[$key]) && !is_int($k) ) ) {
						if ($v === "primary key" || $v === "auto_increment") {
							if ( $v === "primary key" && in_array("auto_increment", $tableFields[$key]) && in_array("auto_increment", $object[$key]) ) {
								handlePrimaryKeyAndAutoIncrement($conn, $key, $object[$key], $tableName, 1);
							}
							elseif ( $v === "primary key" && in_array("auto_increment", $tableFields[$key]) && !in_array("auto_increment", $object[$key]) ) {
								handlePrimaryKeyAndAutoIncrement($conn, $key, $object[$key], $tableName, 3);
							}
							elseif ( $v === "auto_increment" && in_array("primary key", $tableFields[$key]) ) {
								handlePrimaryKeyAndAutoIncrement($conn, $key, $object[$key], $tableName, 2);
							}
							elseif ( $v === "auto_increment" && !in_array("primary key", $tableFields[$key]) ) {

							}
							elseif ( $v === "primary key" && !in_array("auto_increment", $tableFields[$key]) ) {
								dropPrimaryKeyIfExisted($conn, $tableName, $key);
							}
						}						
						elseif ($k === "foreign key") {
							$conn->query("set foreign_key_checks = 0");
							$foreignKeys = getAllForeignKeys($conn, $tableName);

							foreach ($foreignKeys as $value) {
								$x = $value['column_name'];
								$y = $value['constraint_name'];

								if ($x == $key) { // column_name equals the current field
									$r = $conn->query("alter table `$tableName` drop foreign key `$y`");
									$r1 = $conn->query("alter table `$tableName` drop index `$key`");

									if ($r && $r1) {
										print_r(13);
										echo "foreign key dropped\n";
									}
									else {
										print_r(14);
										echo "error: ", $conn->error, "\n";
									}
								}
							}
						}
						elseif ( in_array("not null", $object[$key]) && !in_array("primary key", $object[$key]) && in_array("primary key", $tableFields[$key]) ) {
						}
						else {
							if ( $f !== $k && ( in_array("not null", $object[$key]) && in_array("primary key", $tableFields[$key]) ) || ( $f !== $k && !in_array("not null", $object[$key]) && !in_array("primary key", $tableFields[$key]) ) ) { // primary key must be not null
								$alter = "alter table `$tableName` modify column ";
								addOrUpdateField($conn, $alter, $key, $object[$key], 1);
							}
						}	
					}
					
					$tableFields = constructModelObjectFromTableFields($conn, $tableName);
				}
			}
		}
	}

	function syncTableAndClass($conn, $tableName, $object) {
		createTableIfNotExisted($conn, $tableName, $object);

		addFieldToTableIfNotExisted($conn, $tableName, $object);

		deleteFieldIfExisted($conn, $tableName, $object);

		updateFieldIfNeeded($conn, $tableName, $object);
	}
?>