<?php
// namespace models
	class message {
		public $id = array("type"=> "int", "max"=> "12", "not null", "auto_increment", "primary key");
		public $content = array("type"=> "text");
		public $sentFrom = array("type"=> "int", "not null");
		public $sentTo = array("type"=> "int", "max"=> "13", "foreign key"=> "user(Id)", "not null");
		public $dateId = array("type"=> "int", "max"=> "12", "foreign key"=> "dates(Id)", "not null");
		public $timeId = array("type"=> "int", "max"=> "12", "foreign key"=> "dates(Id)", "not null");
	}
	$message = new message();
?>