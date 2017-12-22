<?php
	class classusers {
		public $userId = array("type"=> "int", "not null", "foreign key"=> "users(id)");
		public $classId = array("type"=> "int", "not null", "foreign key"=> "classes(id)");
	}

	$classusers = new classusers();
?>