<?php
	class times {
		public $id = ['type' => 'int', 'not null', 'primary key', 'auto_increment'];
		public $time = ['type' => 'time', 'not null'];
	}
	
	$times = new times();
	$arr['times'] = $times;
?>