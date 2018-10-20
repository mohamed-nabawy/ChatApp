<?php
  require(dirname(__DIR__) . '/controllers/chat-messages.php');
  require(dirname(__DIR__) . '/test-request-input.php');
  require_once(dirname(__DIR__) . '/session.php');

  $chatMessageController = new ChatMessageController();

  if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if ( isset($_GET['firstUserId'], $_GET['secondUserId'], $_GET['offset']) && testInt($_GET['firstUserId'], $_GET['secondUserId'], $_GET['offset']) ) {
      checkResult($chatMessageController->getMessagesBetweenUsersIdsInClass($conn, $_GET['firstUserId'], $_GET['secondUserId'], $_GET['classId'], $_GET['offset']) );
    }
    elseif ( isset($_GET['flag'], $_GET['offset']) && $_GET['flag'] == 2 && testInt($_GET['offset']) ) {
      checkResult( $chatMessageController->getLastCurrentUserMessages($conn, $_GET['offset']) );
    }
    elseif ( isset($_GET['flag']) && $_GET['flag'] == 3) {
      checkResult( $chatMessageController->getMessageNotifications($conn) );
    }
    elseif (isset($_GET['messageId']) && testInt($_GET['messageId']) ) {
      $chatMessageController->checkMessageExistence($conn, $_GET['messageId']);
    }
    else {
      if ( $chatMessageController->checkNewMessageForUserIdInClass($conn, $_SESSION['userId'], 1)[0] > 0) {
        checkResult( $chatMessageController->recieveNewMessageForUserIdInClass($conn, $_SESSION['userId'], 1) );
      }
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // decode the json data
    $data = json_decode( file_get_contents('php://input') );
    $result = isset($data->content, $data->sentFrom, $data->sentTo, $data->classId) && 
    testInt($data->sentFrom, $data->sentTo, $data->classId) && normalizeString($conn, $data->content);

    if ($result) {
      echo $chatMessageController->sendMessage($conn, $data->content, $data->sentFrom, $data->sentTo, $data->classId);
    }
    else {
      //echo "error";
    }
  }



  if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    if ( isset($_GET['sentFrom']) && testInt($_GET['sentFrom']) ) {
      $chatMessageController->markMessageAsReadFromSomeUser($conn, $_GET['sentFrom']);
    }
    elseif (isset($_GET['flag']) && $_GET['flag'] == 1) {
      $chatMessageController->markAllMessageNotificationsAsRead($conn);
    }
    
    //$messages = checkNewMessageForUserIdInClass($conn, $_SESSION['userId'], 1)[0];
    // if ($_SESSION['roleId'] == 1)
    // {
    //   // decode the json data
    //   $data = json_decode(file_get_contents('php://input'));
    //   $result = isset($data->RoleId,$data->Id,$data->UserName,$data->FirstName,$data->LastName,$data->Email,$data->PhoneNumber) && test_int($data->RoleId,$data->Id) && normalize_string($conn,$data->UserName,$data->FirstName,$data->LastName) && test_phone($data->PhoneNumber) && test_email($data->Email);
    //   if ($result)
    //   {
    //     normalize_string($conn,$data->Image);
    //     editUser($conn,$data->UserName,$data->FirstName,$data->LastName,$data->Email,$data->Image,$data->PhoneNumber,$data->RoleId,$data->Id);
    //   }
    // }
  }

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

  require(dirname(__DIR__) . '/footer.php');
?>