<?php
	class trial1 {
		public $id = ['type' => 'int', 'primary key', 'auto_increment'];
		public $name = ['type' => 'string', 'not null'];
		public $anothername = ['type' => 'string', 'not null'];
	}
	
	$trial1 = new trial1();
	$arr['trial1'] = $trial1;
?>