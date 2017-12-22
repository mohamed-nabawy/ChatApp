<?php
	class users {
		public $id = array("type"=> "int", "primary key", "auto_increment"); // auto_increment // primary key
		public $userName = array("type"=> "string", "max"=> "200", "not null");
		public $firstName = array("type"=> "string", "max"=> "100", "not null");
		public $lastName = array("type"=> "string", "max"=> "100", "not null");
		public $email = array("type"=> "string", "max"=> "200", "not null");
		public $phoneNumber = array("type"=> "string", "max"=> "13", "not null");
		public $dateOfBirth = array("type"=> "date", "not null");
		public $gender = array("type"=> "bool", "not null");
		public $roleId = array("type"=> "int", "not null", "foreign key"=> "roles(id)");
		public $confirmed = array("type"=> "bool", "not null");
		public $passwordHash = array("type"=> "string", "max"=> "100", "not null");
		public $image = array("type"=> "string", "max"=> "300");
	}
	
	$users = new users();
?>