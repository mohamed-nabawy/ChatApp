<?php

  require('chat/backend/functions.php');

  // function getMessages($conn)
  // {  
  //   $sql = "select * from User";
  //   $result = $conn->query($sql);
  //   if ($result)
  //   {
  //     $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
  //     mysqli_free_result($result);
  //     return $users;
  //   }
  //   else
  //   {
  //     echo "Error retrieving Users: ", $conn->error;
  //   }
  // }

  function getMessageById($conn, $id) { // check validations on this
    $sql = "select * from message where Id = ".$id." limit 1";
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

  function addMessage($conn, $content, $sentFrom, $sentTo) {
    $sql = "insert into message (Content, SentFrom, SentTo, DateId, TimeId) values (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("siiii", $Content, $SentFrom, $SentTo, $DateId, $TimeId);

    $Content = $content;
    $SentFrom = $sentFrom;
    $SentTo = $sentTo;
    $DateId = $dateId;
    $TimeId = $timeId;

    if ($stmt->execute() === TRUE) {    
      $messageId = mysqli_insert_id($conn);
      return $messageId;
    }
    else {
      echo "Error: ", $conn->error;
    }
  }

  function editMessage($conn, $content, $id) {
    $sql = "update message set Content = (?) where Id = (?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("si", $Content, $Id);

    $Content = $content;
    $Id = $id;

    if ($stmt->execute() === TRUE) {
      echo "message updated successfully";
    }
    else {
      echo "Error: ", $conn->error;
    }
  }

  function deleteMessage($conn, $id) { // cascaded delete ?? 
    //$conn->query("set foreign_key_checks = 0"); // ????????/
    $sql = "delete from message where Id = ".$id . " limit 1";
    if ($conn->query($sql) === TRUE) {
      return "message deleted successfully";
    }
    else {
      echo "Error: ", $conn->error;
    }
  }

?>