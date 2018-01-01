<?php
  require('../controllers/chat-messages.php');
  require('../test-request-input.php');
  require_once('../session.php');

  //var_dump($_SERVER);

  if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ( isset($_GET['firstUserId'], $_GET['secondUserId'], $_GET['offset']) && testInt($_GET['firstUserId'], $_GET['secondUserId'], $_GET['offset']) ) {
      checkResult(getMessagesBetweenUsersIdsInClass($conn, $_GET['firstUserId'], $_GET['secondUserId'], $_GET['classId'], $_GET['offset']) );
    } else {
      if ( checkNewMessageForUserIdInClass($conn, $_SESSION['userId'], 1)[0] > 0)
        //echo( checkNewMessageForUserIdInClass($conn, $_SESSION['userId'], 1)[0] );
        checkResult( recieveNewMessageForUserIdInClass($conn, $_SESSION['userId'], 1) );
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // decode the json data
    $data = json_decode( file_get_contents('php://input') );
    $result = isset($data->content, $data->sentFrom, $data->sentTo, $data->classId) && 
    testInt($data->sentFrom, $data->sentTo, $data->classId);

    if ($result) {
      echo sendMessage($conn, $data->content, $data->sentFrom, $data->sentTo, $data->classId);
    }
    else {
      //echo "error";
    }
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

  require('../footer.php');
?>