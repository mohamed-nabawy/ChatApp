<?php
  require('ChatApp/chat/backend/controllers/chat-messages.php');
  require('ChatApp/chat/backend/test-request-input.php');

  //var_dump($_SERVER);

  if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  
      if (isset($_GET['firstUserId'], $_GET['secondUserId']) && testInt($_GET['firstUserId'], $_GET['secondUserId']) ) {
        checkResult(getMessagesBetweenUsersIdsInClass($conn, $_GET['firstUserId'], $_GET['secondUserId'], $_GET['classId']) );
      }
      else {
        RecieveNewMessageForUserIdInClass($conn, $_GET['userId']);
      }
    
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //if ($_SESSION['roleId'] == 1)
    //echo "string";
    //{
      // decode the json data
      $data = json_decode( file_get_contents('php://input') );
      $result = isset($data->Content, $data->SentFrom, $data->SentTo, $data->ClassId) && normalizeString($data->Content) && testInt($data->SentFrom, $data->SentTo, $data->ClassId);
      if ($result) {
        echo sendMessage($conn, $data->Content, $data->SentFrom, $data->SentTo, $data->ClassId);
      }
      else {
        //echo "error";
      }
    //}
  }

  // if ($_SERVER['REQUEST_METHOD'] == 'PUT')
  // {
  //   if ($_SESSION['roleId'] == 1)
  //   {
  //     // decode the json data
  //     $data = json_decode(file_get_contents('php://input'));
  //     $result = isset($data->RoleId,$data->Id,$data->UserName,$data->FirstName,$data->LastName,$data->Email,$data->PhoneNumber) && test_int($data->RoleId,$data->Id) && normalize_string($conn,$data->UserName,$data->FirstName,$data->LastName) && test_phone($data->PhoneNumber) && test_email($data->Email);
  //     if ($result)
  //     {
  //       normalize_string($conn,$data->Image);
  //       editUser($conn,$data->UserName,$data->FirstName,$data->LastName,$data->Email,$data->Image,$data->PhoneNumber,$data->RoleId,$data->Id);
  //     }
  //   }
  // }

  // if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
  // {
  //   if ($_SESSION['roleId'] == 1)
  //   {
  //     // decode the json data
  //     if (isset($_GET['userId']) && test_int($_GET['userId']))
  //     {
  //       deleteUser($conn,$_GET['userId']);
  //     }
  //   }
  // }

  require('ChatApp/chat/backend/footer.php');
?>