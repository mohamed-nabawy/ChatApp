<?php

require_once(__DIR__ . '/../connection.php');

function runReversers() {
	$len = count($GLOBALS['reversers']);

	for ($i = $len - 1; $i >= 0; $i--) {
		$GLOBALS['reversers'][$i]();
	}

	exit();
}

function checkKeywords($value) {
	$allowedKeywords = ['type', 'max', 'not null', 'foreign key', 'auto_increment', 'default', 'primary key'];

	foreach ($value as $k => $v) {
		if (in_array($k, $allowedKeywords) === false && is_int($k) === false) {
			echo "undefined keyword {$k}";
			exit();
		}
		elseif (is_int($k) === true && in_array($v, $allowedKeywords) === false) {
			echo "undefined keyword {$v}";
			exit();
		}
	}
}

class database {
	public static function createDatabase($conn, $databaseName, $rev=0) {
		$create = "create database `{$databaseName}`";
		$r = $conn->query($create);

		if ($r) {
			if ($rev == 0) {
				echo "database {$databaseName} created\n";
			}

			$reverser = function() use($conn, $databaseName) {
				table::dropDatabase($conn, $databaseName, 1);
			};

			array_push($GLOBALS['reversers'], $reverser);
		}
		else {
			if ($rev == 1) {
				echo "stuck in rev\n";
				echo $conn->error;
				exit();
			}

			echo $conn->error;

			runReversers();
		}
	}
	public static function dropDatabase($conn, $databaseName, $rev=0) {
		$drop = "drop database `{$databaseName}`";
		$r = $conn->query($drop);

		if ($r) {
			if ($rev == 0) {
				echo "database {$databaseName} deleted\n";
			}

			$reverser = function() use($conn, $databaseName) {
				table::createDatabase($conn, $databaseName, 1);
			};

			array_push($GLOBALS['reversers'], $reverser);
		}
		else {
			if ($rev == 1) {
				echo "stuck in rev\n";
				echo $conn->error;
				exit();
			}

			echo $conn->error;

			runReversers();
		}
	}

}

class table {
	public static function createTable($conn, $tableName, $object, $rev=0) {
		$create = "create table `$tableName` (";

		$f = [];

		// iterate over object attributes
		foreach ($object as $key => $value) {
			$x = column::constructColumnStmtForCreateTable($conn, $create, $key, $value, $tableName);

			foreach ($x as $k => $v) {
				array_push($f, $v);
			}

			$create .= ",";
		}

		if ($create[(strlen($create) ) - 1] == ',') {
			$create[(strlen($create) ) - 1] = ' ';
		}

		$create .= ");";
		$r = $conn->query($create); // create table

		if ($r) {
			if ($rev == 0) {
				echo "table {$tableName} created\n";
			}

			$reverser = function() use($conn, $tableName) {
				table::dropTable($conn, $tableName, 1);
			};

			array_push($GLOBALS['reversers'], $reverser);

			// add any primary key or foreign key
			foreach ($f as $key => $value) {
				if ( !is_array($value) ) { // value is primary key column
					if ($rev == 0) {
						echo "primary key added on {$value} column\n";
					}
				}
				else {
					$f = $conn->query($value[0]);

					if ($f) {
						if ($rev == 0) {
							echo "foreign key added on {$value[1]} column\n";
						}
					}
					else {
						if ($rev == 1) {
							echo "stuck in rev\n";
							echo $conn->error;
							exit();
						}

						echo $conn->error;

						runReversers();
					}
				}
			}
		}
		else {
			if ($rev == 1) {
				echo "stuck in rev\n";
				echo $conn->error;
				exit();
			}

			echo $conn->error;

			runReversers();
		}
	}

	public static function dropTable($conn, $tableName, $rev=0) {
		$setForeignKeyChecks = "set foreign_key_checks = 0";
		$drop = "drop table `$tableName`";
		$arr = objectTableMapper::getTableColumns($conn, $tableName); // for reverser
		$r = $conn->query($drop);

		if (!$r) {
			if ($rev == 1) {
				echo "stuck in rev\n";
				echo $conn->error;
				exit();
			}

			echo $conn->error;

			runReversers();
		}
		else {
			$arr = objectTableMapper::getTableColumns($conn, $tableName);

			$reverser = function() use($conn, $tableName, $arr) {
				table::createTable($conn, $tableName, $arr, 1);
			};

			array_push($GLOBALS['reversers'], $reverser);

			if ($rev == 0) {
				echo "table {$tableName} dropped\n";
			}
		}
	}

	public static function renameTable($conn, $oldTableName, $newTableName, $rev=0) {
		$sql = "rename table `{$oldTableName}` to `{$newTableName}`";
		$res = $conn->query($sql);

		if ($res) {
			$reverser = function() use($conn, $newTableName, $oldTableName) {
				table::renameTable($conn, $newTableName, $oldTableName, 1);
			};

			array_push($GLOBALS['reversers'], $reverser);

			if ($rev == 0) {
				echo "table name changed";
			}
		}
		else {
			if ($rev == 1) {
				echo "stuck in rev\n";
				echo $conn->error;
				exit();
			}

			echo $conn->error;

			runReversers();
		}
	}
};

class column {
	public static function constructColumnStmtForAddColumn($conn, &$statment, &$key, &$value, $tableName) {
		checkKeywords($value);
		$arr = [];
		$statment .= "`$key`";

		if ( isset($value['type']) ) {
			$type = $value['type'];
			$statment .= " {$value['type']}";

			if ( isset($value['max']) ) {

				$allowedTypes = ['int', 'bigint', 'mediumint', 'smallint', 'tinyint', 'char', 'nchar', 'nvarchar', 'varchar', 'text', 'bit', 'enum', 'set'];
				// max is not associated with every datatype
				// so we must check that first
				if (in_array($type, $allowedTypes) === true) {
					$x = $value['max'];
					$statment .= "($x)";
				}
				else {
					echo "no max is set on " . $type . ". we will ignore it\n";
				}
			}
			// varchar must have max so we add default
			elseif ($type == 'varchar') {
				$statment .= "(255)";
			}
		}
		elseif ( isset($value['max']) ) {
			if ($rev == 1) {
				echo "stuck in rev\n";
				echo $conn->error;
				exit();
			}

			echo $conn->error;

			runReversers();
		}
		else {
			if ($rev == 1) {
				echo "stuck in rev\n";
				echo $conn->error;
				exit();
			}

			echo $conn->error;

			runReversers();
		}

		// check if default is set
		if ( isset($value['default']) ) {
			$y = $value['default'];

			if ($value['type'] == 'date' || $value['type'] == 'datetime' || $value['type'] == 'time') {
				$statment .= " default '$y'";
			}
			else {
				$statment .= " default $y";
			}
		}

		// check other paramters
		if ( in_array("primary key", $value) ) {
			$sql = primaryKeyManager::handlePrimaryKeyAddition($conn, $tableName, $key);

			if ($sql) {
				$arr['primary'] = $sql;
			}
		}

		if ( in_array("not null", $value) ) {
			$statment .= " not null";
		}

		if ( in_array("unique", $value) ) {
			$statment .= " unique";
		}

		if ( in_array('auto_increment', $value) ) {
			$statment .= ' auto_increment';
		}

		if ( isset($value["foreign key"]) ) {
			$x = foreignKeyManager::checkForeignKeyExistence($conn, $tableName, $key);

			if ($x === false) { // no foreign key for this column
				$referencedTableName = $value['foreign key']['table'];
				$tableColumn = $value['foreign key']['column'];
				$alter = "alter table `$tableName` add constraint foreign key(`$key`) references `$referencedTableName`(`$tableColumn`)";
				array_push($arr, $alter);
			}
		}

		return $arr;
	}

	public static function constructColumnStmtForCreateTable($conn, &$statment, &$key, &$value, $tableName) {
		checkKeywords($value);
		$arr = [];
		$statment .= "`$key`";
		$allowedKeywords = ['type', 'max', 'foreign key', 'auto_increment', 'default', 'primary key'];

		if ( isset($value['type']) ) {
			$type = $value['type'];
			$statment .= " {$value['type']}";

			if ( isset($value['max']) ) {

				$allowedTypesToHaveMax = ['int', 'bigint', 'mediumint', 'smallint', 'tinyint', 'char', 'nchar', 'nvarchar', 'varchar', 'text', 'bit', 'enum', 'set'];
				// max is not associated with every datatype
				// so we must check that first
				if (in_array($type, $allowedTypesToHaveMax) === true) {
					$x = $value['max'];
					$statment .= "($x)";
				}
				else {
					echo "no max is set on " . $type . ". we will ignore it\n";
				}
			}
			// varchar must have max so we add default
			elseif ($type == 'varchar') {
				$statment .= "(255)";
			}
		}
		elseif ( isset($value['max']) ) {
			echo "error: can't have max without determining type\n";

			if ($rev == 1) {
				echo "stuck in rev\n";
				exit();
			}

			runReversers();
		}
		else {
			echo "error: you must specify type\n";

			if ($rev == 1) {
				echo "stuck in rev\n";
				exit();
			}

			runReversers();
		}

		// check if default is set
		if ( isset($value['default']) ) {
			$y = $value['default'];

			if ($value['type'] == 'date' || $value['type'] == 'datetime' || $value['type'] == 'time') {
				$statment .= " default '$y'";
			}
			else {
				$statment .= " default $y";
			}
		}

		// check other paramters
		if ( in_array("primary key", $value) ) {
			$statment .= " primary key";
			$arr['primary'] = $key;
		}

		if ( in_array("not null", $value) ) {
			$statment .= " not null";
		}

		if ( in_array("unique", $value) ) {
			$statment .= " unique";
		}

		if ( in_array('auto_increment', $value) ) {
			$statment .= ' auto_increment';
		}

		$alter = "";
		if ( isset($value["foreign key"]) ) {
			$x = foreignKeyManager::checkForeignKeyExistence($conn, $tableName, $key);

			if ($x === false) { // no foreign key for this column
				$referencedTableName = $value['foreign key']['table'];
				$tableColumn = $value['foreign key']['column'];
				$alter = "alter table `$tableName` add constraint foreign key(`$key`) references `$referencedTableName`(`$tableColumn`)";
				array_push($arr, [$alter, $key]);
			}
		}

		return $arr;
	}

	public static function addColumn($conn, $tableName, $key, $arr, $rev=0) {
		$alter = "alter table `$tableName` add column ";
		$arr = column::constructColumnStmtForAddColumn($conn, $alter, $key, $arr, $tableName);
		$res = $conn->query($alter);

		if (!$res) {
			if ($rev == 1) {
				echo "stuck in rev\n";
				echo $conn->error;
				exit();
			}

			echo $conn->error;

			runReversers();
		}
		else {
			$reverser = function() use($conn, $tableName, $key) {
				column::dropColumn($conn, $tableName, $key, 1);
			};

			array_push($GLOBALS['reversers'], $reverser);

			if ($rev == 0) {
				echo "column {$key} added\n";
			}
		}
		foreach ($arr as $key => $value) {
			if ( !is_array($value) ) {
				$r = $conn->query($value);

				if ($r) {
					if ($rev == 0) {
						echo "primary key added on {$key} column\n";
					}
				}
				else {
					if ($rev == 1) {
						echo "stuck in rev\n";
						echo $conn->error;
						exit();
					}

					echo $conn->error;

					runReversers();
				}
			}
			else {
				$r = $conn->query($value);

				if ($r) {
					if ($rev == 0) {
						echo "foreign key added to {$key} column\n";
					}
				}
				else {
					if ($rev == 1) {
						echo "stuck in rev\n";
						echo $conn->error;
						exit();
					}

					echo $conn->error;

					runReversers();
				}
				
			}
		}
	}

	public static function dropColumn($conn, $tableName, $key, $rev=0) {
		$arr = objectTableMapper::getTableColumns($conn, $tableName)[$key];
		foreignKeyManager::dropForeignKey($conn, $tableName, $key); // if existed
		$alter = "alter table `$tableName` drop column `$key`";
		$result = $conn->query($alter);

		if ($result) {
			$reverser = function() use($conn, $tableName, $key, $arr) {
				column::addColumn($conn, $tableName, $key, $arr, 1);
			};

			array_push($GLOBALS['reversers'], $reverser);

			if ($rev == 0) {
				echo "column {$key} deleted\n";
			}
		}
		else {
			if ($rev == 1) {
				echo "stuck in rev\n";
				echo $conn->error;
				exit();
			}

			echo $conn->error;

			runReversers();
		}
	}

	// update every value for that column
	public static function modifyColumn($conn, $tableName, $key, $column, $rev=0) {
		checkKeywords($column);
		$arr = objectTableMapper::getTableColumns($conn, $tableName);
		$f = 0;

		if (array_key_exists('not null', $column) && $column['not null'] === false) {
			if ( in_array('primary key', $arr[$key]) ) {
				echo "error, can't set null on primary";

				if ($rev == 1) {
					echo "stuck in rev\n";
					exit();
				}

				runReversers();
			}
		}

		$columnAttrs = $arr[$key];
		$alter = "alter table `$tableName` modify column `$key`";
		// some errors to handle before moving on
		// auto_increment and default
		if (key_exists('default', $column) === true && key_exists('auto_increment', $column) === true && $column['default'] === true && $column['auto_increment'] === true) {
			echo "can't add default and auto_increment at the same time\n";

			if ($rev == 1) {
				echo "stuck in rev\n";
				exit();
			}

			runReversers();
		}

		if (isset($column['auto_increment']) && $column['auto_increment'] !== true && $column['auto_increment'] !== false) {

			//if ($rev == 0) {
			echo "error, invalid value for auto_increment. only true and false are valid";

			if ($rev == 1) {
				echo "stuck in rev\n";
				exit();
			}
			//}

			runReversers();
		}

		$type = 0;

		if (array_key_exists('type', $column) === true) {
			$type = $column['type'];
			$alter .= " {$type}";

			if (array_key_exists('max', $column) === true) {
				$alter .= "({$column['max']})";
			}
			elseif (array_key_exists('max', $columnAttrs) === true) {
				$alter .= "({$columnAttrs['max']})";
			}

		}
		else {
			$type = $columnAttrs['type'];
			$alter .= " {$type}";

			if (array_key_exists('max', $column) === true) {
				$alter .= "({$column['max']})";
			}
			elseif (array_key_exists('max', $columnAttrs) === true) {
				$alter .= "({$columnAttrs['max']})";
			}
		}

		if ( array_key_exists('primary key', $column) ) {
			if ($column['primary key'] === false) {
				if ( array_key_exists('foreign key', $arr[$key]) ) {
					foreignKeyManager::dropForeignKey($conn, $tableName, $key, 1);

					$y = $arr[$key]['foreign key'];

					$reverser = function() use($conn, $tableName, $key, $arr) {
						column::addForeignKey($conn, $tableName, $key, $y['table'], $y['column'], 1);
					};

					array_push($GLOBALS['reversers'], $reverser);
				}

				primaryKeyManager::dropPrimaryKey($conn, $tableName, $key);

				$reverser = function() use($conn, $tableName, $key) {
					column::addPrimaryKey($conn, $tableName, $key, 1);
				};

				array_push($GLOBALS['reversers'], $reverser);

				if ( array_key_exists('foreign key', $arr[$key]) ) {
					$x = $arr[$key]['foreign key'];

					foreignKeyManager::addForeignKey($conn, $tableName, $key, $x['table'], $x['column'], 1);

					$reverser = function() use($conn, $tableName, $key) {
						column::dropForeignKey($conn, $tableName, $key, 1);
					};

					array_push($GLOBALS['reversers'], $reverser);
				}
			}
			elseif ($column['primary key'] === true) {
				primaryKeyManager::addPrimaryKey($conn, $tableName, $key);

				$reverser = function() use($conn, $tableName, $key) {
					column::dropPrimaryKey($conn, $tableName, $key, 1);
				};

				array_push($GLOBALS['reversers'], $reverser);
			}
			else {
				echo "primary key must be true or false\n";

				if ($f == 1) {
					$conn->query('set foreign_key_checks=1');
				}

				if ($rev == 1) {
					echo "stuck in rev\n";
					exit();
				}

				runReversers();
			}
		}

		if (array_key_exists('unique', $column) === true) {
			$uni = "select * from `information_schema`.`table_constraints` where `constraint_type` = 'unique' and `CONSTRAINT_NAME` = '$key' and `table_name` = '$tableName'";
			$r = $conn->query($uni);
			$r1 = mysqli_fetch_array($r, MYSQLI_ASSOC);

			if (!$r) {
				if ($f == 1) {
					$conn->query('set foreign_key_checks=1');
				}

				if ($rev == 1) {
					echo "stuck in rev\n";
					echo $conn->error;
					exit();
				}

				echo $conn->error;

				runReversers();
			}

			if ($column['unique'] === false) {
				$res = $conn->query("alter table `$tableName` drop index `$key`");

				if (!$res) {
					echo "index not found on {$key} column\n";

					if ($f == 1) {
						$conn->query('set foreign_key_checks=1');
					}

					if ($rev == 1) {
						echo "stuck in rev\n";
						echo $conn->error;
						exit();
					}

					echo $conn->error;

					runReversers();
				}
				else {
					$reverser = function() use($conn, $tableName, $key) {
						$conn->query("alter table `$tableName` add index `$key`");
					};

					array_push($GLOBALS['reversers'], $reverser);

					echo "unique dropped from {$key} column\n";
				}
			}
			elseif ($r1 !== null && $column['unique'] === true) {
				echo "unique already set on column {$key}\n";

				if ($f == 1) {
					$conn->query('set foreign_key_checks=1');
				}

				if ($rev == 1) {
					echo "stuck in rev\n";
					exit();
				}

				runReversers();
			}
			elseif ($r1 === null) {
				$s = "alter table `$tableName` add unique(`$key`)";
				$r = $conn->query($s);

				if (!$r) {
					if ($f == 1) {
						$conn->query('set foreign_key_checks=1');
					}

					if ($rev == 1) {
						echo "stuck in rev\n";
						echo $conn->error;
						exit();
					}

					echo $conn->error;

					runReversers();
				}
				else {
					$reverser = function() use($conn, $tableName, $key) {
						$conn->query("alter table `$tableName` drop index `$key`");
					};

					array_push($GLOBALS['reversers'], $reverser);

					echo "unique added to {$key} column\n";
				}
			}
		}

		if (array_key_exists('auto_increment', $column) ) {
			if (isset($columnAttrs['foreign key'])) {
				$conn->query('set foreign_key_checks=0');
				$f = 1;
			}
		}

		// 2- not null
		if ( array_key_exists('not null', $column) ) {
			if ($column['not null'] === true) {
				$alter .= " not null";
			}
			elseif ($column['not null'] === false) {
				$alter .= " null";
			}
			else {
				echo "not null must be either true or false\n";

				if ($f == 1) {
					$conn->query('set foreign_key_checks=1');
				}

				if ($rev == 1) {
					echo "stuck in rev\n";
					exit();
				}

				runReversers();
			}
		}
		elseif (in_array('not null', $columnAttrs) === true) {
			$alter .= " not null";
		}


		if (array_key_exists('default', $column) === true && $column['default'] === false
		&& array_key_exists('auto_increment', $column) === true && $column['auto_increment'] === false) {

		}
		// elseif (array_key_exists('auto_increment', $column) === true && $column['auto_increment'] === false) {
		// 	if (isset($columnAttrs['foreign key'])) {
		// 		$conn->query('set foreign_key_checks=0');
		// 		$f = 1;
		// 	}
		// }
		elseif (array_key_exists('default', $column) === false && array_key_exists('auto_increment', $column) === false && array_key_exists('default', $columnAttrs) == true && $columnAttrs['default'] != null) {
			$alter .= " default " . $columnAttrs['default'];
		}
		elseif (array_key_exists('default', $column) === true && $column['default'] !== false) {
			if (array_key_exists('auto_increment', $column) === true && $column['auto_increment'] === false) {
				if ($type == 'date' || $type == 'time' || $type == 'datetime') {
					$alter .= " default '{$column['default']}'";
				}
				else {
					$alter .= " default " . $column['default'];
				}
			}
			elseif (in_array('auto_increment', $columnAttrs) === true) {
				echo "remove auto_increment first\n";

				if ($f == 1) {
					$conn->query('set foreign_key_checks=1');
				}

				if ($rev == 1) {
					echo "stuck in rev\n";
					exit();
				}

				runReversers();
			}
			else {
				if ($type == 'date' || $type == 'time' || $type == 'datetime') {
					$alter .= " default '{$column['default']}'";
				}
				else {
					$alter .= " default " . $column['default'];
				}
			}
		}
		elseif (array_key_exists('auto_increment', $column) === true && $column['auto_increment'] === true) {
			if (array_key_exists('default', $column) === true && $column['default'] === false) {
				$alter .= " auto_increment";
			}
			elseif (array_key_exists('default', $columnAttrs) === true) {
				echo "remove default first\n";

				if ($f == 1) {
					$conn->query('set foreign_key_checks=1');
				}

				if ($rev == 1) {
					echo "stuck in rev\n";
					exit();
				}

				runReversers();
			}
			else {
				$alter .= " auto_increment";
			}
		}

		if ( isset($column['foreign key']) ) {
			$x = $column['foreign key'];

			if ($x === false) {
				foreignKeyManager::dropForeignKey($conn, $tableName, $key);

				$y = $arr[$key]['foreign key'];

				$reverser = function() use($conn, $tableName, $key, $arr) {
					column::addForeignKey($conn, $tableName, $key, $y['table'], $y['column'], 1);
				};

				array_push($GLOBALS['reversers'], $reverser);
			}
			else {
				// foreign key changed
				foreignKeyManager::dropForeignKey($conn, $tableName, $key);

				$y = $arr[$key]['foreign key'];

				$reverser = function() use($conn, $tableName, $key, $arr) {
					column::addForeignKey($conn, $tableName, $key, $y['table'], $y['column'], 1);
				};

				array_push($GLOBALS['reversers'], $reverser);

				foreignKeyManager::addForeignKey($conn, $tableName, $key, $x['table'], $x['column']);

				$reverser = function() use($conn, $tableName, $key, $arr) {
					column::dropForeignKey($conn, $tableName, $key, 1);
				};

				array_push($GLOBALS['reversers'], $reverser);
			}
		}

		$r = $conn->query($alter);

		if ($f == 1) {
			$conn->query('set foreign_key_checks=1');
			//$f = 0;
		}

		if (!$r) {
			if ($rev == 1) {
				echo "stuck in rev\n";
				echo $conn->error;
				exit();
			}

			echo $conn->error;

			runReversers();
		}
		else {
			$reverser = function() use($conn, $tableName, $key, $columnAttrs) {
				column::modifyColumn($conn, $tableName, $key, $columnAttrs, 1);
			};

			array_push($GLOBALS['reversers'], $reverser);

			if ($rev == 0) {
				echo "column {$key} modified\n";
			}
		}
	}

	public static function renameColumn($conn, $tableName, $oldColumnName, $newColumnName, $rev=0) {
		$arr = objectTableMapper::getTableColumns($conn, $tableName);
		$columnAttrs = $arr[$oldColumnName];
		$alter = "alter table `$tableName` change column `$oldColumnName` `$newColumnName`";

		$alter .= " {$columnAttrs['type']}"; // type
		if (isset($columnAttrs['max'])) {
			$alter .= "({$columnAttrs['max']})"; // max
		}

		if (in_array('not null', $columnAttrs) === true) {
			$alter .= " not null";
		}

		if (in_array('auto_increment', $columnAttrs) === true) {
			$alter .= " auto_increment";
		}

		if (array_key_exists('default', $columnAttrs) === true) {
			$alter .= " default '{$columnAttrs['default']}'";
		}

		$r = $conn->query($alter);

		if (!$r) {
			if ($rev == 1) {
				echo "stuck in rev\n";
				echo $conn->error;
				exit();
			}

			echo $conn->error;

			runReversers();
		}
		else {
			$reverser = function() use($conn, $tableName, $newColumnName, $oldColumnName) {
				column::renameColumn($conn, $tableName, $newColumnName, $oldColumnName, 1);
			};

			array_push($GLOBALS['reversers'], $reverser);

			if ($rev == 0) {
				echo "column " . $oldColumnName . " is renamed to " . $newColumnName . "\n";
			}
		}
	}
};

class primaryKeyManager {
	public static function handlePrimaryKeyAddition($conn, $tableName, $key) { // when add table
		// check if there is no primary key in this table first
		// check if not table
		if ($conn->query("select 1 from `$tableName` limit 1") === false || mysqli_fetch_assoc( $conn->query("select 1 from `$tableName` limit 1") )['num_rows'] == 0) {
				$sql = "ALTER TABLE `$tableName` ADD CONSTRAINT PRIMARY KEY (`$key`);";

				return $sql;
			}
			else {
				$pr = "SHOW INDEXES FROM `$tableName` WHERE Key_name = 'PRIMARY'";
				$pr1 = $conn->query($pr);

				if (!$pr1) {
					if ($rev == 1) {
						echo "stuck in rev\n";
						echo $conn->error;
						exit();
					}

					echo $conn->error;

					runReversers();
				}
			}
	}

	public static function addPrimaryKey($conn, $tableName, $key, $rev=0) { // when update column
		$sql = "ALTER TABLE `$tableName` ADD CONSTRAINT PRIMARY KEY (`$key`);";
		$res = $conn->query($sql);

		if ($res) {
			$reverser = function() use($conn, $tableName, $key) {
				column::dropPrimaryKey($conn, $tableName, $key, 1);
			};

			array_push($GLOBALS['reversers'], $reverser);

			if ($rev == 0) {
				echo "primary key added\n";
			}
		}
		else {
			if ($rev == 1) {
				echo "stuck in rev\n";
				echo $conn->error;
				exit();
			}

			echo $conn->error;

			runReversers();
		}
	}

	public static function dropPrimaryKey($conn, $tableName, $key, $rev=0) {
		$sql = "SELECT * FROM `information_schema`.`KEY_COLUMN_USAGE` WHERE `REFERENCED_TABLE_NAME` = '$tableName' AND `REFERENCED_COLUMN_NAME` = '$key'";
		$r = mysqli_fetch_assoc( $conn->query($sql) );

		if ( !($r !== null) ) {
			$c = "alter table `$tableName` drop primary key";
			$z = $conn->query($c);

			if ($z) {
				$reverser = function() use($conn, $tableName, $key) {
					column::addPrimaryKey($conn, $tableName, $key, 1);
				};

				array_push($GLOBALS['reversers'], $reverser);

				if ($rev == 0) {
					echo "primary key dropped from {$key} column\n";
				}
			}
			else {
				if ($rev == 1) {
					echo "stuck in rev\n";
					echo $conn->error;
					exit();
				}

				echo "sdsdsd";

				echo $conn->error;

				runReversers();
			}
		}
		else {
			echo "primary key can't be dropped because it's referenced from another table\n";

			if ($rev == 1) {
				echo "stuck in rev\n";
				echo $conn->error;
				exit();
			}

			runReversers();
		}
	}
};

class foreignKeyManager {
	public static function checkForeignKeyExistence($conn, $tableName, $key) {
		$sql = "select `constraint_name` from `INFORMATION_SCHEMA`.`KEY_COLUMN_USAGE` where `column_name` = '$key' and `table_name` = '$tableName' and REFERENCED_TABLE_NAME != 'NULL'";
		$s = $conn->query($sql);		
		$r = mysqli_fetch_all($s, MYSQLI_ASSOC);

		if (count($r) > 0) {
			return $r[0]['constraint_name'];
		}

		return false;
	}

	public static function getReferencedTableAndColumn($conn, $tableName, $key) {
		$q1 = "SELECT * FROM `INFORMATION_SCHEMA`.`KEY_COLUMN_USAGE` WHERE `TABLE_NAME` = '$tableName' and `COLUMN_NAME` = '{$key}'";
		$x = $conn->query($q1);

		if ($x) {
			$y = mysqli_fetch_all($x, MYSQLI_ASSOC);
			$ref = [];

			foreach ($y as $key => $value) {
				if ($value['REFERENCED_TABLE_NAME'] != null) {
					$ref = $value;
					break;
				}
			}
			
			if (count($y) > 0 && count($ref) > 0) {
				return [$ref["REFERENCED_TABLE_NAME"], $ref["REFERENCED_COLUMN_NAME"], $ref['CONSTRAINT_NAME']];
			}
			else {
				return [];
			}
		}
		else {
			if ($rev == 1) {
				echo "stuck in rev\n";
				echo $conn->error;
				exit();
			}

			echo $conn->error;

			runReversers();
		}
	}

	public static function addForeignKey($conn, $tableName, $column, $referencedTable, $referencedColumn, $rev=0) {
		$sql = "ALTER TABLE `$tableName` ADD CONSTRAINT FOREIGN KEY (`$column`) REFERENCES `$referencedTable`(`$referencedColumn`);";
		$conn->query('set foreign_key_checks = 0'); // look for better way
		$res = $conn->query($sql);

		if ($res) {
			$reverser = function() use($conn, $tableName, $column) {
				foreignKeyManager::dropForeignKey($conn, $tableName, $column, 1);
			};

			array_push($GLOBALS['reversers'], $reverser);


			if ($rev == 0) {
				echo "foreign key added\n";
			}
		}
		else {
			if ($rev == 1) {
				echo "stuck in rev\n";
				echo $conn->error;
				exit();
			}

			var_dump($sql);
			echo $conn->error;

			runReversers();
		}
	}

	public static function dropForeignKey($conn, $tableName, $key, $rev=0) {
		$foreign = foreignKeyManager::checkForeignKeyExistence($conn, $tableName, $key);

		if ($foreign) {
			$referencedTableNameAndColumnName = foreignKeyManager::getReferencedTableAndColumn($conn, $tableName, $key);
			$referencedTableName = $referencedTableNameAndColumnName[0];
			$referencedColumnName = $referencedTableNameAndColumnName[1];
			$sql = "alter table `$tableName` drop foreign key `{$foreign}`;";
			$r = $conn->query($sql);

			if ($r) {
				$reverser = function() use($conn, $tableName, $key, $referencedTableName, $referencedColumnName) {
					foreignKeyManager::addForeignKey($conn, $tableName, $key, $referencedTableName, $referencedColumnName, 1);
				};

				array_push($GLOBALS['reversers'], $reverser);

				if ($rev == 0) {
					echo "foreign key dropped\n";
				}
			}
			else {
				if ($rev == 1) {
					echo "stuck in rev\n";
					echo $conn->error;
					exit();
				}

				echo $conn->error;

				runReversers();
			}
		}
	}
};

class indexManager {
	public static function createIndex($conn, $tableName, $index, $columnToIndexTo, $rev=0) {
		$sql = "ALTER TABLE `$tableName` ADD INDEX `$index` (`$columnToIndexTo` ASC);";
		$res = $conn->query($sql);

		if ($res) {
			$reverser = function() use($conn, $tableName, $index) {
				$conn->query("ALTER TABLE `$tableName` DROP INDEX `$index`");
			};

			array_push($GLOBALS['reversers'], $reverser);

			if ($rev == 0) {
				echo "index added\n";
			}
		}
		else {
			if ($rev == 1) {
				echo "stuck in rev\n";
				echo $conn->error;
				exit();
			}

			echo $conn->error;

			runReversers();
		}
	}

	public static function dropIndex($conn, $tableName, $index, $rev=0) {
		$sql = "ALTER TABLE `$tableName` DROP INDEX `$index`";
		$res = $conn->query($sql);

		if ($res) {
			$reverser = function($conn, $tableName, $index) {
				$conn->query("ALTER TABLE `$tableName` ADD INDEX `$index`");
			};

			array_push($GLOBALS['reversers'], $reverser);

			if ($rev == 0) {
				echo "index dropped\n";
			}
		}
		else {
			if ($rev == 1) {
				echo "stuck in rev\n";
				echo $conn->error;
				exit();
			}

			echo $conn->error;

			runReversers();
		}
	}
};

class objectTableMapper {
	public static function getTableColumns($conn, $tableName) {
		$x = DB_NAME;
		$result = $conn->query("SELECT * FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME` = '{$tableName}' and `table_schema` = '{$x}'");
		$arr = [];
		$flag = 0;

		while ( $row = mysqli_fetch_assoc($result) ) {
			$arr[$row['COLUMN_NAME']]['type'] = $row['DATA_TYPE'];

			if ($row['CHARACTER_MAXIMUM_LENGTH'] !== null) {
				$arr[$row['COLUMN_NAME']]['max'] = $row['CHARACTER_MAXIMUM_LENGTH'];
			}
			elseif ($row['DATA_TYPE'] == 'decimal') {
				$arr[$row['COLUMN_NAME']]['max'] = $row['NUMERIC_PRECISION'] . "," . $row['NUMERIC_SCALE'];
			}

			if ($row['IS_NULLABLE'] == "NO") {
				array_push($arr[$row['COLUMN_NAME']], "not null");
			}

			if ($row['COLUMN_KEY'] == "PRI") {
				array_push($arr[$row['COLUMN_NAME']], "primary key");
			}
			elseif ($row['COLUMN_KEY'] == "UNI") {
				array_push($arr[$row['COLUMN_NAME']], "unique");
			}

			if ($row['COLUMN_DEFAULT'] !== null) {
				$x = $row['COLUMN_DEFAULT'];
				$arr[$row['COLUMN_NAME']]['default'] = "$x";
			}

			if ($row['EXTRA'] != null) {
				$x = $row['EXTRA'];
				array_push($arr[$row['COLUMN_NAME']], $x);
			}

			$f = foreignKeyManager::getReferencedTableAndColumn($conn, $tableName, $row['COLUMN_NAME']);

			if (count($f) > 0) {
				$arr[$row['COLUMN_NAME']]['foreign key']['table'] = $f[0];
				$arr[$row['COLUMN_NAME']]['foreign key']['column'] = $f[1];
			}
		}
		
		return $arr;
	}
};