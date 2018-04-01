<?php
	class chatmessages {
		public $id = ['type' => 'int', 'primary key', 'auto_increment'];
		public $content = ['type' => 'text'];
		public $sentFrom = ['type' => 'int', 'foreign key' => 'users(id)', 'not null'];
		public $sentTo = ['type' => 'int', 'foreign key' => 'users(id)', 'not null'];
		public $dateId = ['type' => 'int', 'foreign key' => 'dates(id)', 'not null'];
		public $timeId = ['type' => 'int', 'foreign key' => 'times(id)', 'not null'];
		public $classId = ['type' => 'int', 'not null', 'foreign key' => 'classes(id)'];
		public $new = ['type' => 'bool', 'not null'];
	}
	
	$chatmessages = new chatmessages();
	$arr['chatmessages'] = $chatmessages;
?>