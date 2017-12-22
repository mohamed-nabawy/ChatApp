<?php
	class dates {
		public $id = array("type"=> "int", "auto_increment", "primary key");
		public $date = array("type"=> "date", "not null");
	}
	
	$dates = new dates();
?>