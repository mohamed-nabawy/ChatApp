<?php
  require('../connection.php');
  require('times.php');
  require('dates.php');

  function getMessagesBetweenUsersIdsInClass($conn, $firstUserId, $secondUserId, $classId, $offset) {  
    $sql = "select * from `chatmessages` where `sentFrom` in ({$firstUserId}, {$secondUserId}) and `sentTo` in ({$firstUserId},{$secondUserId}) order by id desc limit {$offset}, 10"; // can be order in the front end
    $result = $conn->query($sql);
    
    if ($result) {
      $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
      mysqli_free_result($result);

      // mark new messages as old
      foreach ($messages as $key => $value) {
        markMessageAsRead($conn, $value['id']);
      }
     
      return $messages;
    }
    else {
      echo "Error retrieving chat messages: ", $conn->error;
    }
  }

  // select last message for every chat between current user and other users
  function getLastCurrentUserMessages($conn) {
    $sql = "select `chatmessages`.`content`, `chatmessages`.`id`, `users`.* from `chatmessages` inner join `lastmessages` on `chatmessages`.`id` = `lastmessages`.`messageId` inner join `users` where (`users`.`id` = `chatmessages`.`sentTo` or `users`.`id` = `chatmessages`.`sentTo`) and `users`.`id` != {$_SESSION['userId']} and `sentFrom` = {$_SESSION['userId']} or `sentTo` = {$_SESSION['userId']} order by `chatmessages`.`id` desc";
    $result = $conn->query($sql);

    if ($result) {
      $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
      mysqli_free_result($result);

      return $messages;
    }
    else {
      echo "Error retrieving chat messages: ", $conn->error;
    }
  }

  function recieveNewMessageForUserIdInClass($conn, $UserId, $classId) {
    $sql = "select * from `chatmessages` where `sentTo` = {$UserId} and `new` = 1"; // can be order in the front end
    $result = $conn->query($sql);

    if ($result) {
      $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
      mysqli_free_result($result);

      foreach ($messages as $key => $value) {
        markMessageAsRead($conn, $value['id']);
      }
      
      return $messages;
    }
    else {
      echo "Error retrieving chat messages: ", $conn->error;
    }
  }

  function checkNewMessageForUserIdInClass($conn, $UserId, $classId) {
    $sql = "select count(*) from `chatmessages` where `sentTo` = {$UserId} and `new` = true"; // can be order in the front end
    $result = $conn->query($sql);

    if ($result) {
      $messages = mysqli_fetch_row($result);
      mysqli_free_result($result);
      return $messages;
    }
    else {
      echo "Error retrieving chat messages: ", $conn->error;
    }
  }

  function getMessageById($conn, $id) { // check validations on this
    $sql = "select * from `chatmessages` where `id` = " . $id . " limit 1";
    $result = $conn->query($sql);

    if ($result) {
      $message = mysqli_fetch_assoc($result);
      mysqli_free_result($result);
      return $message;
    }
    else {
      echo "Error retrieving message: ", $conn->error;
    }
  }

  function sendMessage($conn, $content, $sentFrom, $sentTo, $classId) {
    // date and time are from no
    $dateId = getCurrentDateId($conn);
    $timeId = getCurrentTimeId($conn);
    $messageId = 0;

    $sql = "insert into `chatmessages` (content, sentFrom, sentTo, dateId, timeId, classId) values (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiiii", $content, $sentFrom, $sentTo, $dateId, $timeId, $classId);

    if ($stmt->execute() === TRUE) {    
      $messageId = mysqli_insert_id($conn);

      $sql1 = "select `id` from `chatmessages` where (`sentFrom` = {$sentFrom} and `sentTo` = {$sentTo}) or (`sentFrom` = {$sentTo} and `sentTo` = {$sentFrom}) limit 1";

      $stmt1 = $conn->query($sql1);

      if ($stmt1) {

        if (mysqli_num_rows($stmt1) > 0) {
          $sql = "update `lastmessages` inner join `chatmessages` set `messageId` = '{$messageId}' where ((`chatmessages`.`sentFrom` = {$sentFrom} and `chatmessages`.`sentTo` = {$sentTo}) or (`chatmessages`.`sentFrom` = {$sentTo} and `chatmessages`.`sentTo` = {$sentFrom})) and `chatmessages`.`id` = `lastmessages`.`messageId`";
          $res = $conn->query($sql);

          if ($res) {
            return 1;
          }
          else {
            echo "error update last message ", $conn->error;
          }
        }
        else {
          $sql = "insert into `lastmessages` values ({$messageId})";
          $res = $conn->query($sql);

          if ($res) {
            return 1;
          }
          else {
            echo "error insert last message ", $conn->error;
          }
        }
      }
      else {
        echo "error ", $conn->error;
      }

      return $messageId;
    }
    else {
      echo "Error: ", $conn->error;
    }

    
  }


function markMessageAsRead($conn, $id) { // cascaded delete ?? 
    //$conn->query("set foreign_key_checks = 0"); // ????????/
    $sql = "update `chatmessages` set `new` = false where `id` = " . $id . " limit 1";

    if ($conn->query($sql) === TRUE) {
      return "message is seen";
    }
    else {
      echo "Error: ", $conn->error;
    }
  }


  function deleteMessage($conn, $id) { // cascaded delete ?? 
    //$conn->query("set foreign_key_checks = 0"); // ????????/
    $sql = "delete from `chatmessages` where `id` = " . $id . " limit 1";

    if ($conn->query($sql) === TRUE) {
      return "message deleted successfully";
    }
    else {
      echo "Error: ", $conn->error;
    }
  }
?>