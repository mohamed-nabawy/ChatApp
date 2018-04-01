<?php
	class directoryroles {
		public $dirId = ['type' => 'int', 'not null', 'foreign key' => 'directories(id)'];
		public $roleId = ['type' => 'int', 'foreign key' => 'roles(id)'];
	}
	
	$directoryroles = new directoryroles();
	$arr['directoryroles'] = $directoryroles;
?>