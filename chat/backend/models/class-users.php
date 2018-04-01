<?php
	class classusers {
		public $userId = ['type' => 'int', 'not null', 'foreign key' => 'users(id)'];
		public $classId = ['type' => 'int', 'not null', 'foreign key' => 'classes(id)'];
	}

	$classusers = new classusers();
	$arr['classusers'] = $classusers;
?>