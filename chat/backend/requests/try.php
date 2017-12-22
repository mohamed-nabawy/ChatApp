<?php
require('../controllers/users.php');
require('../test-request-input.php');

//echo "string";
//var_dump($_SESSION["chats"]);

foreach ($_SESSION['chats'] as $key => $value) {
	var_dump($value->id);
}

//echo deleteChat(3);

?>