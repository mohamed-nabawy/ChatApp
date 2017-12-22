<?php
	class directoryroles {
		public $dirId = array("type"=> "int", "not null", "foreign key"=> "directories(id)");
		public $roleId = array("type"=> "int", "foreign key"=> "roles(id)");
	}
	
	$directoryroles = new directoryroles();
?>