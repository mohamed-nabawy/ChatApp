<?php
	class roles {
		public $id = ['type' => 'int', 'auto_increment', 'primary key'];
		public $name = ['type' => 'string', 'max' => '100', 'not null'];
	}

	$roles = new roles();
	$arr['roles'] = $roles;
?>