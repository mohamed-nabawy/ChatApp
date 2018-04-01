<?php
	//require('../migrations/migration-functions.php');
	function constructModelObjectFromTableFields($conn, $tableName) {
		$result = getTableFields($conn, $tableName);
		$arr = array();
		$flag = 0;

		while ( $row = mysqli_fetch_assoc($result) ) {
			$arr[$row['Field']] = array();

			if (strpos($row['Type'], "int") !== false && strpos($row['Type'], "tiny") === false) {
				$arr[$row['Field']]['type'] = "int";
			}
			elseif (strpos($row['Type'], "varchar") !== false) {
				$arr[$row['Field']]['type'] = "string";
			}
			elseif (strpos($row['Type'], "date") !== false) {
				$arr[$row['Field']]['type'] = "date";
			}
			elseif (strpos($row['Type'], "time") !== false) {
				$arr[$row['Field']]['type'] = "time";
			}
			elseif (strpos($row['Type'], "tinyint") !== false) {
				$arr[$row['Field']]['type'] = "bool";
			}
			elseif (strpos($row['Type'], "text") !== false) {
				$arr[$row['Field']]['type'] = "text";
			}

			if (strpos($row['Type'], "(") !== false && strpos($row['Type'], "tinyint") === false) {
				$start = strpos($row['Type'], "(");
				$end = strpos($row['Type'], ")");
				$length = $end - $start;
				$max = substr($row['Type'], $start + 1, $length - 1);
				$arr[$row['Field']]['max'] = $max;
			}

			if ($row['Null'] == "NO") {
				array_push($arr[$row['Field']], "not null");
			}

			if ($row['Key'] == "PRI") {
				array_push($arr[$row['Field']], "primary key");
			}
			elseif ($row['Key'] == "UNI") {
				array_push($arr[$row['Field']], "unique");
			}
			elseif ($row['Key'] == "MUL") {
				$foriegnKeys = getAllForeignKeys($conn, $tableName);

				while ($row1 = mysqli_fetch_assoc($foriegnKeys) ) {
					if ($row1['column_name'] == $row['Field']) {
						$foreignTable = $row1['foreign_table'];
						$foreignColumn = $row1['foreign_column'];
						$arr[$row['Field']]['foreign key'] = "$foreignTable($foreignColumn)";
						break;
					}
				}
			}

			if ($row['Default'] !== null && $row['Default'] !== "") {
				$x = $row['Default'];
				array_push($arr[$row['Field']], "default $x");
			}

			if ($row['Extra'] !== null && $row['Extra'] !== "") {
				$x = $row['Extra'];
				array_push($arr[$row['Field']], $x);
			}
		}

		return $arr;
	}

	function updateTableName($conn, $oldTableName, $newTableName) {
		$sql = "rename table {$oldTableName} to {$newTableName}";
		$res = $conn->query($sql);

		if ($res) {
			echo "table name changed";
		}
		else {
			echo "error : ", $conn->error;
		}
	}

	function updateColumnName($conn, $table, $oldColumnName, $newColumnName) {
		var_dump($table);
		// $line = "\n" . "require('../models/{$table}.php');";
		// $dir = "../migrations/includes.php";
		// $contents = file_get_contents($dir);
		// $contents = str_replace($line, '', $contents);
		// file_put_contents($dir, $contents);
		//$line = "\n" . "require('../models/{$table}.php');";
		//$dir = "../models/{$table}.php";
		//$n = explode("\n", $dir);

		// foreach($n as $line) {  
		//     if ( 0 === strpos($line, "public ${$oldColumnName}") ) {
		//         // do whatever you want with the line
		        
		//     }
		// }
		// $contents = file_get_contents($dir);
		// $contents = str_replace($line, '', $contents);
		// file_put_contents($dir, $contents);

		$arr = constructModelObjectFromTableFields($conn, $table);

		foreach ($arr as $key => $value) {
			var_dump($key);
			var_dump($value);
			echo "\n";
			if ($key == $oldColumnName) {
				$stat = "alter table {$table} change {$oldColumnName} {$newColumnName}";

				//echo "1";
				$maxtype = 0;

				foreach ($value as $key1 => $value1) {
					//$v = '';

					if ( ( ( $key1 === 'max' && key_exists('type', $value) ) || ( $key1 === 'type' && key_exists('max', $value) ) || ($key1 === 'type') ) && ($maxtype == 0) ) {
						echo $maxtype;
						$maxtype = 1;

						$th = '';
						//$stat .= "(" . $value1 . ")";
						if ( $key1 === 'max' || key_exists('max', $value) ) {
							if ($key1 === 'max') {
								if ($value['type'] == 'string') {
									//$th = 'varchar';
									$stat .= " varchar" . "(" . $value1 . ")";
								}
								else {
									$stat .= " " . $value['type'] . "(" . $value1 . ")";
								}
							}
							else {
								//$th = 
								if ($value1 == 'string') {
									$stat .= " varchar" . "(" . $value['max'] . ")";
								}
								else {
									$stat .= " " . $value1 . "(" . $value['max'] . ")";
								}
							}
						}
						elseif ($key1 === 'type') {
							if ($value1 == 'string') {
								$stat .= " varchar(255)"; 
							}
							elseif ($value1 == 'int') {
								$stat .= " int(11)";
							}
						}
						// elseif (condition) {
						// 	# code...
						// }
					}
					// elseif ($key1 == 'type') {
					// 	$stat .= $value1;
					// }
					elseif (! ( ( $key1 === 'max' && key_exists('type', $value) ) || ( $key1 === 'type' && key_exists('max', $value) ) || ($key1 === 'type') )) {
						if ($value1 == 'string' && key_exists('max', $value)) {
							$stat .= " varchar(" . $value['max'] . ")";
						}
						elseif ($value1 == 'string' && !key_exists('max', $value)) {
							$stat .= " varchar(255)";
						}
						elseif ($value1 == 'int' && key_exists('max', $value)) {
							$stat .= " int(" . $value['max'] . ")";
						}
						elseif ($value1 == 'int' && !key_exists('max', $value)) {
							$stat .= " int(11)";
						}
						else {
							$stat .= " {$value1} ";
						}
					}
				}

				echo $stat . "\n";

				if ($conn->query($stat)) {
					echo "success";
				}
				else {
					echo "error : ", $conn->error;
				}

				return;
			}
		}
	}

	//updateColumnName($conn, "trial", "thename", "name");