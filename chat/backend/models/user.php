<?php
	class user {
		public $Id = array("type"=> "int", "max"=> "11", "primary key", "auto_increment", "not null");
		public $UserName = array("type"=> "string", "max"=> "200", "not null");
		public $FirstName = array("type"=> "string", "max"=> "100", "not null");
		public $LastName = array("type"=> "string", "max"=> "100", "not null");
		public $Email = array("type"=> "string", "max"=> "200", "not null");
		public $PhoneNumber = array("type"=> "string", "max"=> "13", "not null");
		public $DateOfBirth = array("type"=> "date", "not null");
		public $Gender = array("type"=> "bool", "not null");
		public $RoleId = array("type"=> "int", "max"=> "11", "not null");
		public $Confirmed = array("type"=> "bool", "not null");
		public $PasswordHash = array("type"=> "string", "max"=> "100", "not null");
		public $Image = array("type"=> "string", "max"=> "300");
	}
	$user = new user();
?>