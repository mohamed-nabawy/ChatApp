<?php
	class notifications {
		// add fields
		public $id = ['type' => 'int', 'primary key', 'auto_increment'];
		public $type = ['type' => 'int'];
		public $sentFrom = ['type' => 'int', 'foreign key' => 'users(id)'];
		public $sentTo = ['type' => 'int', 'foreign key' => 'users(id)'];
		public $read = ['type' => 'bool', 'default' => 'false'];
	}

	$notifications = new notifications();
	$arr['notifications'] = $notifications;