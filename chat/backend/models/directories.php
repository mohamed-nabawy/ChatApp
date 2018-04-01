<?php
	class directories {
		public $id = ['type' => 'int', 'max' => '12', 'not null', 'auto_increment', 'primary key'];
		public $name = ['type' => 'text', 'not null'];
	}
	
	$directories = new directories();
	$arr['directories'] = $directories;
?>