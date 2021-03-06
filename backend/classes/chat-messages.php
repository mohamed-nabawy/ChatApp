<?php

require_once(dirname(__DIR__) . '/helpers/connection.php');
require __DIR__ . '/dates.php';
require __DIR__ . '/times.php';

class ChatMessage {

  private $time = NULL;
  private $date = NULL;

  public function __construct(Time $time, Date $date) {
    $this->date = $date;
    $this->time = $time;
  }

  public function getMessagesBetweenUsersIdsInClass($conn, $firstUserId, $secondUserId, $classId, $offset) {  
    $sql = "select * from `chatmessages` where `sentFrom` in ({$firstUserId}, {$secondUserId}) and `sentTo` in ({$firstUserId},{$secondUserId}) order by `id` desc limit {$offset}, 10"; // can be order in the front end
    $result = $conn->query($sql);
    
    if ($result) {
      $messages = [];

      while ( $row = mysqli_fetch_assoc($result) ) {
        $row['id'] = (int)($row['id']);
        array_push($messages, $row);
      }

      mysqli_free_result($result);

      // mark new messages as old
      // foreach ($messages as $key => $value) {
      //   markMessageAsRead($conn, $value['id']);
      // }
     
      return $messages;
    }
    else {
      echo "Error retrieving chat messages: ", $conn->error;
    }
  }

  public function checkMessageExistence($conn, $messageId) {
    $sql = "select * from `chatmessages` where (`chatmessages`.`sentFrom` = {$_SESSION['userId']} or `chatmessages`.`sentTo` = {$_SESSION['userId']}) and `chatmessages`.`id` = {$messageId}";
    $result = $conn->query($sql);
    
    if ($result) {
      if (mysqli_num_rows($result) > 0) {
        echo 1;
      }
      else {
        echo 0;
      }
    }
    else {
      echo "Error retrieving chat messages: ", $conn->error;
    }

  }

  // select last message for every chat between current user and other users
  public function getLastCurrentUserMessages($conn, $offset) {
    $sql = "select c.id as messageId, n.new as new, c.`content`, case when c.`sentFrom` = {$_SESSION['userId']} then c.`sentTo` else c.`sentFrom` end as id, users.firstName as firstName, c.`sentFrom`, c.`sentTo`
from (
   select max(id) as maxid
   from `chatmessages` where `sentFrom` = {$_SESSION['userId']} or `sentTo` = {$_SESSION['userId']} group by case when `sentFrom` != {$_SESSION['userId']} then `sentFrom` else `sentTo` end
) as x inner join `chatmessages` as c on c.id = x.maxid inner join users on users.id = case when c.`sentFrom` = {$_SESSION['userId']} then c.`sentTo` else c.`sentFrom` end inner join `notifications` as n on (n.sentTo = c.sentTo and n.sentFrom = c.sentFrom) order by `messageId` desc limit {$offset}, 10";
    $result = $conn->query($sql);

    if ($result) {
      $messages = [];

      while ( $row = mysqli_fetch_assoc($result) ) {
        $row['messageId'] = (int)($row['messageId']);
        array_push($messages, $row);
      }

      mysqli_free_result($result);

      return $messages;
    }
    else {
      echo "Error retrieving chat messages: ", $conn->error;
    }
  }

  public function recieveNewMessageForUserIdInClass($conn, $UserId, $classId) {
    $sql = "select * from `chatmessages` where `chatmessages`.`sentTo` = {$UserId} "; // can be order in the front end
    $result = $conn->query($sql);

    if ($result) {
      $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
      mysqli_free_result($result);

      // foreach ($messages as $key => $value) {
      //   markMessageAsRead($conn, $value['id']);
      // }
      
      return $messages;
    }
    else {
      echo "Error retrieving chat messages: ", $conn->error;
    }
  }

  public function checkNewMessageForUserIdInClass($conn, $UserId, $classId) {
    $sql = "select count(*) from `notifications` where `notifications`.`new` = 1 and `notifications`.`type` = 1 and `notifications`.`sentTo` = {$UserId}"; // can be order in the front end
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

  public function getMessageById($conn, $id) { // check validations on this
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

  public function sendMessage($conn, $content, $sentFrom, $sentTo, $classId) {

    //$time = new Time();
    //$date = new Date();
    // date and time are from now
    $dateId = $this->date->getCurrentDateId($conn);

    if ($dateId === false) {
      $today = date("Y-m-d");
      $this->date->addDate($conn, $today);
      $dateId = $this->date->getCurrentDateId($conn);
    }

    $timeId = $this->time->getCurrentTimeId($conn);
    $messageId = 0;

    $sql = "insert into `chatmessages` (content, sentFrom, sentTo, dateId, timeId, classId) values (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiiii", $content, $sentFrom, $sentTo, $dateId, $timeId, $classId);

    if ($stmt->execute() === TRUE) {
      $messageId = mysqli_insert_id($conn);
      $sql1 = "select `id` from `notifications` where `type` = 1 and `sentTo` = {$sentTo} and `sentFrom` = {$sentFrom}";

      $stmt1 = $conn->query($sql1);

      if (mysqli_num_rows($stmt1) > 0) {
        $id = mysqli_fetch_assoc($stmt1)['id'];
        $sql = "update `notifications` set `read` = 0, `new` = 1 where `id` = " . $id;
        $stmt = $conn->query($sql);

        if ($stmt) {
          return $messageId;
        }
        else {
          return "error: ". $conn->error;
        }
      }
      else {
        $sql = "insert into `notifications` (`sentFrom`, `sentTo`, `type`, `read`, `new`) values ({$sentFrom}, {$sentTo}, 1, 0, 1)";
        $stmt = $conn->query($sql);

        if ($stmt) {
          return $messageId;
        }
        else {
          return "error ". $conn->error;
        }
      }
    }
    else {
      return "Error: " . $conn->error;
    }    
  }

  public function addOrUpdateMessageNotification($conn, $sentFrom, $sentTo) {
    $sql = "select * from `notifications` where `sentTo` = {$sentTo} and `sentFrom` = {$sentFrom} and `type` = 1";
    $stmt = $conn->query($sql);

    if (mysqli_num_rows($stmt) == 0) {
      $sql = "insert into `notifications` (`sentFrom`, `sentTo`, `type`) values ({$sentFrom}, {$sentTo}, 1)";
      $stmt = $conn->query($sql);

      if ($stmt) {
      }
      else {
        return "error add messagenotification : " . $conn->error;
      }
    }
    else {
      $sql = "update `notifications` set `read` = 0 where `sentTo` = {$sentTo} and `sentFrom` = {$sentFrom} and `type` = 1";
      $stmt = $conn->query($sql);

      if ($stmt) {
        return "3";
      }
      else {
        return "error update messagenotification : " . $conn->error;
      }
    }
  }

  public function getMessageNotifications($conn) {
    $sql = "select * from `notifications` where `sentTo` = {$_SESSION['userId']} and `read` = 0 and `type` = 1";
    $stmt = $conn->query($sql);

    if ($stmt) {
      return mysqli_fetch_all($stmt, MYSQLI_ASSOC);
    }
    else {
      echo "error return messagenotification :", $conn->error;
    }
  }

  public function markAllMessageNotificationsAsRead($conn) {
    $sql = "update `notifications` set `read` = 1 where `sentTo` = " . $_SESSION['userId'] . " and `type` = 1";

    if ($conn->query($sql) === true) {
      return "notifications are read";
    }
    else {
      echo "Error: ", $conn->error;
    }
  }

  public function markMessageAsReadFromSomeUser($conn, $userId) { // cascaded delete ?? 
    $sql = "update `notifications` set `new` = 0, `read` = 1 where `sentFrom` = " . $userId . " and `sentTo` = {$_SESSION['userId']} and `new` = true and `type` = 1";

    if ($conn->query($sql) === TRUE) {
      return "notification is read and message is seen";
    }
    else {
      echo "Error: ", $conn->error;
    }
  }

  public function markMessageAsRead($conn, $id) { // cascaded delete ?? 
    $sql = "update `chatmessages` set `new` = 0 where `id` = " . $id . " limit 1";

    if ($conn->query($sql) === TRUE) {
      return "message is seen";
    }
    else {
      echo "Error: ", $conn->error;
    }
  }

  public function deleteMessage($conn, $id) { // cascaded delete ?? 
    $sql = "delete from `chatmessages` where `id` = " . $id . " limit 1";

    if ($conn->query($sql) === TRUE) {
      return "message deleted successfully";
    }
    else {
      echo "Error: ", $conn->error;
    }
  }
}