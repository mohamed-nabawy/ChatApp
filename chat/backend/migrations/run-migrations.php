<?php
	require('includes.php');

	foreach ($arr as $key => $value) {
		syncTableAndClass($conn, $key, $value);
	}
?>