<?php
	class messagenotification {
		// add fields
		public $sentFrom = ['type' => 'int', 'foreign key' => 'users(id)'];
		public $sentTo = ['type' => 'int', 'foreign key' => 'users(id)'];
		public $read = ['type' => 'bool',  'default' => 'false'];

	}

	$messagenotification = new messagenotification();
	$arr['messagenotification'] = $messagenotification;