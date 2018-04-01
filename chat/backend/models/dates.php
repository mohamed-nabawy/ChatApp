<?php
	class dates {
		public $id = ['type' => 'int', 'auto_increment', 'primary key'];
		public $date = ['type' => 'date', 'not null'];
	}
	
	$dates = new dates();
	$arr['dates'] = $dates;
?>