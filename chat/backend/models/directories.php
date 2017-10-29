<?php
	class directories {
		public $id = array("type"=> "int", "max"=> "12", "not null", "auto_increment", "primary key");
		public $name = array("type"=> "text", "not null");
	}
	$directories = new directories();
?>