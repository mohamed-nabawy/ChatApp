<?php

$modelName = $argv[2];

if ($argv[1] == 'create') {
	$fh = fopen('models/' . $modelName . ".php", 'w'); // create if not existed
	fwrite($fh, "<?php
	class {$modelName} {
		// add fields
	}

	$" . $modelName . " = new " . $modelName . "();"
	. "\n"
	. '	$arr[' . "'" . $modelName . "'" . "]" . " = " . "$" . $modelName . ";");

	$fh = fopen('migrations/includes.php', 'a');
	fwrite($fh, "\n" . "require('../models/{$modelName}.php');");
}
else if ($argv[1] == 'delete') {
	unlink("models/{$modelName}.php");
	$line = "\n" . "require('../models/{$modelName}.php');";
	$dir = "migrations/includes.php";
	$contents = file_get_contents($dir);
	$contents = str_replace($line, '', $contents);
	file_put_contents($dir, $contents);
}