<?php
require('ChatApp/chat/backend/controllers/users.php');
  require('ChatApp/chat/backend/test-request-input.php');

  //echo "string";
//var_dump($_SESSION["chats"]);
  foreach ($_SESSION["chats"] as $key => $value) {
  	var_dump($value->id);
  }
//echo deleteChat(3);

?>