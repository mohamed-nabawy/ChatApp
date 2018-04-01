<?php
	class users {
		public $id = ['type' => 'int', 'primary key', 'auto_increment']; // auto_increment // primary key
		public $userName = ['type' => 'string', 'max' => '200', 'not null'];
		public $firstName = ['type' => 'string', 'max' => '100', 'not null'];
		public $lastName = ['type' => 'string', 'max' => '100', 'not null'];
		public $email = ['type' => 'string', 'max' => '200', 'not null'];
		public $phoneNumber = ['type' => 'string', 'max' => '13', 'not null'];
		public $dateOfBirth = ['type' => 'date', 'not null'];
		public $gender = ['type' => 'bool', 'not null'];
		public $roleId = ['type' => 'int', 'not null', 'foreign key' => 'roles(id)'];
		public $confirmed = ['type' => 'bool', 'not null'];
		public $passwordHash = ['type' => 'string', 'max' => '100', 'not null'];
		public $image = ['type' => 'string', 'max' => '300'];
	}
	
	$users = new users();
	$arr['users'] = $users;
?>