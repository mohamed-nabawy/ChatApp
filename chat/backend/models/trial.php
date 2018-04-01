<?php
	class trial {
		// add fields
		public $date = ['type' => 'date'];
		public $name = ['type' => 'string', 'not null', 'newName' => 'thename'];
		public $anothername = ['type' => 'string', 'not null'];
		
		public $id = ['type' => 'int', 'primary key', 'auto_increment'];
	}

	$trial = new trial();
	$arr['trial'] = $trial;