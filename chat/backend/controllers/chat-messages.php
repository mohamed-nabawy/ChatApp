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

      //mark new messages as old
      foreach ($messages as $key => $value) {
        markMessageAsRead($conn, $value['id']);
      }
     
      return $messages;
    }
    else {
      echo "Error retrieving chat messages: ", $conn->error;
    }
  }


  function recieveNewMessageForUserIdInClass($conn, $UserId, $classId) {
    $sql = "select * from `chatmessages` where `sentTo` = {$UserId} and `new` = true"; // can be order in the front end
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
    $sql = "select * from `chatmessages` where `id` = ".$id." limit 1";
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
    // date and time are from now
    $dateId = getCurrentDateId($conn);
    $timeId = getCurrentTimeId($conn);

    echo "date: ". $dateId;

    $sql = "insert into `chatmessages` (content, sentFrom, sentTo, dateId, timeId, classId) values (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiiii", $content, $sentFrom, $sentTo, $dateId, $timeId, $classId);

    if ($stmt->execute() === TRUE) {    
      $messageId = mysqli_insert_id($conn);
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