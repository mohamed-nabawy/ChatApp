<?php
  require(dirname(__DIR__) . '/controllers/chat-messages.php');
  require(dirname(__DIR__) . '/class-validators/message-validator.php');
  require_once(dirname(__DIR__) . '/helpers/session.php');

  $messageValidator = new MessageValidator();
  $chatMessageController = new ChatMessageController(new ChatMessage(new Time(), new Date()));

  if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if ( isset($_GET['firstUserId'], $_GET['secondUserId'], $_GET['offset']) && $messageValidator->testInt($_GET['firstUserId'], $_GET['secondUserId'], $_GET['offset']) ) {
      $messageValidator->checkResult($chatMessageController->getMessagesBetweenUsersIdsInClass($conn, $_GET['firstUserId'], $_GET['secondUserId'], $_GET['classId'], $_GET['offset']) );
    }
    elseif ( isset($_GET['flag'], $_GET['offset']) && $_GET['flag'] == 2 && $messageValidator->testInt($_GET['offset']) ) {
      $messageValidator->checkResult( $chatMessageController->getLastCurrentUserMessages($conn, $_GET['offset']) );
    }
    elseif ( isset($_GET['flag']) && $_GET['flag'] == 3) {
      $messageValidator->checkResult( $chatMessageController->getMessageNotifications($conn) );
    }
    elseif (isset($_GET['messageId']) && $messageValidator->testInt($_GET['messageId']) ) {
      $chatMessageController->checkMessageExistence($conn, $_GET['messageId']);
    }
    elseif (isset($_GET['flag']) && $messageValidator->testInt($_GET['flag']) && $_GET['flag'] == 1) {
      $users = $_SESSION['chats'];
      
      foreach ($users as $key => $value) {
        $messages = $chatMessageController->getMessagesBetweenUsersIdsInClass($conn, $value->firstUserId, $value->secondUserId, 1, 0);
        $value->messages = $messages;
      }

      $messageValidator->checkResult($users);
    }
    else {
      if ( $chatMessageController->checkNewMessageForUserIdInClass($conn, $_SESSION['userId'], 1)[0] > 0) {
        $messageValidator->checkResult( $chatMessageController->recieveNewMessageForUserIdInClass($conn, $_SESSION['userId'], 1) );
      }
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // decode the json data
    $data = json_decode( file_get_contents('php://input') );
    $result = isset($data->content, $data->sentFrom, $data->sentTo, $data->classId) && 
    $messageValidator->testInt($data->sentFrom, $data->sentTo, $data->classId) && $messageValidator->normalizeString($conn, $data->content);

    if ($result) {
      echo $chatMessageController->sendMessage($conn, $data->content, $data->sentFrom, $data->sentTo, $data->classId);
    }
    else {
      //echo "error";
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    if ( isset($_GET['sentFrom']) && $messageValidator->testInt($_GET['sentFrom']) ) {
      $chatMessageController->markMessageAsReadFromSomeUser($conn, $_GET['sentFrom']);
    }
    elseif (isset($_GET['flag']) && $_GET['flag'] == 1) {
      $chatMessageController->markAllMessageNotificationsAsRead($conn);
    }
  }
  
  require(dirname(__DIR__) . '/helpers/footer.php');
?>