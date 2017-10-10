<?php

  require('ChatApp/chat/backend/connection.php');

  function getMessagesBetweenUsersIdsInClass($conn,$firstUserId,$secondUserId,$classId)
  {  
    $sql = "select * from chatmessage where FromUserId In({$firstUserId},{$secondUserId}) or ToUserId In({$firstUserId},{$secondUserId}) order by DateId desc"; // can be order in the front end
    $result = $conn->query($sql);
    if ($result)
    {
      $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
      mysqli_free_result($result);
      return $messages;
    }
    else
    {
      echo "Error retrieving Users: ", $conn->error;
    }
  }


function RecieveNewMessageForUserIdInClass($conn,$UserId,$classId)
  {  
    $sql = "select * from chatmessage where ToUserId={$UserId} and New=true"; // can be order in the front end
    $result = $conn->query($sql);
    if ($result)
    {
      $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
      mysqli_free_result($result);
      return $messages;
    }
    else
    {
      echo "Error retrieving Users: ", $conn->error;
    }
  }


  function getMessageById($conn, $id) { // check validations on this
    $sql = "select * from chatmessage where Id = ".$id." limit 1";
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


  function SendMessage($conn, $content, $sentFrom, $sentTo,$classId) {
    //date and time are from now
    $dateId = getCurrentDateId($conn);
    $timeId = getCurrentTimeId($conn);

    $sql = "insert into chatmessage (Content, FromUserId, ToUserId, DateId, TimeId,ClassId) values (?, ?, ?, ?,?,?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("siiiii", $Content, $FromUserId, $ToUserId, $DateId, $TimeId,$ClassId);

    $Content = $content;
    $FromUserId = $sentFrom;
    $ToUserId = $sentTo;
    $DateId = $dateId;
    $TimeId = $timeId;
    $ClassId=$classId;

    if ($stmt->execute() === TRUE) {    
      $messageId = mysqli_insert_id($conn);
      return $messageId;
    }
    else {
      echo "Error: ", $conn->error;
    }
  }


function markMessageAsRead($conn,$id) { // cascaded delete ?? 
    //$conn->query("set foreign_key_checks = 0"); // ????????/
    $sql = "update chatmessage set New =false where Id = ".$id . " limit 1";
    if ($conn->query($sql) === TRUE) {
      return "message is seen";
    }
    else {
      echo "Error: ", $conn->error;
    }
  }


  function deleteMessage($conn, $id) { // cascaded delete ?? 
    //$conn->query("set foreign_key_checks = 0"); // ????????/
    $sql = "delete from chatmessage where Id = ".$id . " limit 1";
    if ($conn->query($sql) === TRUE) {
      return "message deleted successfully";
    }
    else {
      echo "Error: ", $conn->error;
    }
  }

?>