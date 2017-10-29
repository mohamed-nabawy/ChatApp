<?php
	class classes {
		public $id = array("type"=> "int", "auto_increment", "primary key");
		public $name = array("type"=> "string", "max"=> "100", "not null");
		public $max = array("type"=> "int", "not null");
	}

	$classes = new classes();

?>