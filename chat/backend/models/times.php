<?php
	class times {
		public $id = array("type"=> "int", "not null", "primary key", "auto_increment");
		public $time = array("type"=> "time", "not null");
	}
	
	$times = new times();
?>