<?php
	class chatmessages {
		public $id = array("type"=> "int", "not null", "primary key", "auto_increment");
		public $content = array("type"=> "text");
		public $sentFrom = array("type"=> "int", "foreign key"=> "users(id)", "not null");
		public $sentTo = array("type"=> "int", "foreign key"=> "users(id)", "not null");
		public $dateId = array("type"=> "int", "foreign key"=> "dates(id)", "not null");
		public $timeId = array("type"=> "int", "foreign key"=> "times(id)", "not null");
		public $classId = array("type"=> "int", "not null", "foreign key"=> "classes(id)");
		public $new = array("type"=> "bool", "not null");
	}
	
	$chatmessages = new chatmessages();
	$arr['chatmessages'] = $chatmessages;
?>