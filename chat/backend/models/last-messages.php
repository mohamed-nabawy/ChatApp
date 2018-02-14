<?php

	class lastmessages {
		public $messageId = ["type" => "int", "foreign key" => "chatmessages(id)", "not null", "primary key", "auto_increment"];
	}

	$lastmessages = new lastmessages();
	$arr['lastmessages'] = $lastmessages;

?>