<?php
// namespace models
	class roles {
		public $id = array("type"=> "int", "auto_increment", "primary key");
		public $name = array("type"=> "string", "max"=> "100", "not null");
	}
	$roles = new roles();
?>