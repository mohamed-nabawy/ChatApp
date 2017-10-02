<?php
	class user {
		public $Id = array("type"=> "int","max"=> "11");
		public $UserName = array("type"=> "string","max"=> "200");
		public $FirstName = array("type"=> "string","max"=> "100");
		public $LastName = array("type"=> "string","max"=> "100");
		public $Email = array("type"=> "string","max"=> "200");
		public $PhoneNumber = array("type"=> "string","max"=> "13");
		public $DateOfBirth = array("type"=> "date");
		public $Gender = array("type"=> "bool");
		public $RoleId = array("type"=> "int","max"=> "11");
		public $Confirmed = array("type"=> "bool");
		public $PasswordHash = array("type"=> "string","max"=> "100");
		public $Image = array("type"=> "string","max"=> "300");
	}
	//$user = new user();
?>