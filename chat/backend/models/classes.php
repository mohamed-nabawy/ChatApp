<?php
	class classes {
		public $id = ['type' => 'int', 'auto_increment', 'primary key'];
		public $name = ['type' => 'string', 'max' => '100', 'not null'];
		public $max = ['type' => 'int', 'not null'];
	}

	$classes = new classes();
	$arr['classes'] = $classes;
?>