<?php
  require_once(__DIR__ . '/../connection.php');
  require(__DIR__ . '/times.php');
  require(__DIR__ . '/dates.php');

  function getMessagesBetweenUsersIdsInClass($conn, $firstUserId, $secondUserId, $classId, $offset) {  
    $sql = "select * from `chatmessages` where `sentFrom` in ({$firstUserId}, {$secondUserId}) and `sentTo` in ({$firstUserId},{$secondUserId}) order by `id` desc limit {$offset}, 10"; // can be order in the front end
    $result = $conn->query($sql);
    
    if ($result) {
      $messages = [];

      while ($row = mysqli_fetch_assoc($result)) {
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

  function checkMessageExistence($conn, $messageId) {
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

  };

  // select last message for every chat between current user and other users
  function getLastCurrentUserMessages($conn, $offset) {
    $sql = "select c.id as messageId, c.new, c.`content`, case when `sentFrom` = {$_SESSION['userId']} then `sentTo` else `sentFrom` end as id, users.firstName as firstName, c.`sentFrom`
from (
   select max(id) as maxid
   from `chatmessages` where `sentFrom` = {$_SESSION['userId']} or `sentTo` = {$_SESSION['userId']} group by case when `sentFrom` != {$_SESSION['userId']} then `sentFrom` else `sentTo` end
) as x inner join `chatmessages` as c on c.id = x.maxid inner join users on users.id = case when `sentFrom` = {$_SESSION['userId']} then `sentTo` else `sentFrom` end order by `messageId` desc limit {$offset}, 10";
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

      // foreach ($messages as $key => $value) {
      //   markMessageAsRead($conn, $value['id']);
      // }
      
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

      $sql1 = "select `id` from `notifications` where `type` = 1 and `sentTo` = {$sentTo} and `sentFrom` = {$sentFrom}";

      // $sql1 = "select `messageid` from `lastmessages` inner join `chatmessages` on `chatmessages`.`id` = `lastmessages`.`messageId` where (`chatmessages`.`sentFrom` = {$sentFrom} and `chatmessages`.`sentTo` = {$sentTo}) or (`chatmessages`.`sentFrom` = {$sentTo} and `chatmessages`.`sentTo` = {$sentFrom}) limit 1";

      $stmt1 = $conn->query($sql1);

      if (mysqli_num_rows($stmt1) > 0) {
        $id = mysqli_fetch_assoc($stmt1)['id'];
        $sql = "update `notifications` set `read` = 0 where `id` = " . $id;
        $stmt = $conn->query($sql);

        if ($stmt) {
          //return getMessagesBetweenUsersIdsInClass($conn, $sentFrom, $sentTo, 1, 0);
          return $messageId;
        }
        else {
          return "error: ". $conn->error;
        }

        //   $sql = "update `lastmessages` inner join `chatmessages` set `messageId` = '{$messageId}' where ((`chatmessages`.`sentFrom` = {$sentFrom} and `chatmessages`.`sentTo` = {$sentTo}) or (`chatmessages`.`sentFrom` = {$sentTo} and `chatmessages`.`sentTo` = {$sentFrom})) and `chatmessages`.`id` = `lastmessages`.`messageId`";
        //   $res = $conn->query($sql);

        //   if ($res) {
        //     addOrUpdateMessageNotification($conn, $sentFrom, $sentTo);
        //   }
        //   else {
        //     return "error update last message " . $conn->error;
        //   }
        // }
        // else {
        //   $sql = "insert into `lastmessages` values ({$messageId})";
        //   $res = $conn->query($sql);

        //   if ($res) {
        //     addOrUpdateMessageNotification($conn, $sentFrom, $sentTo);
        //   }
        //   else {
        //     return "error insert last message " . $conn->error;
        //   }
        // }
      }
      else {
        $sql = "insert into `notifications` (`sentFrom`, `sentTo`, `type`, `read`) values ({$sentFrom}, {$sentTo}, 1, 0)";
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

  function addOrUpdateMessageNotification($conn, $sentFrom, $sentTo) {
    $sql = "select * from `notifications` where `sentTo` = {$sentTo} and `sentFrom` = {$sentFrom} and `type` = 1";
    $stmt = $conn->query($sql);

    if (mysqli_num_rows($stmt) == 0) {
      $sql = "insert into `notifications` (`sentFrom`, `sentTo`, `type`) values ({$sentFrom}, {$sentTo}, 1)";
      $stmt = $conn->query($sql);

      if ($stmt) {
        //return "2";
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

  function getMessageNotifications($conn) {
    $sql = "select * from `notifications` where `sentTo` = {$_SESSION['userId']} and `read` = false and `type` = 1";
    $stmt = $conn->query($sql);

    if ($stmt) {
      return mysqli_fetch_all($stmt, MYSQLI_ASSOC);
    }
    else {
      echo "error return messagenotification :", $conn->error;
    }
  }

  function markAllMessageNotificationsAsRead($conn) {
    $sql = "update `notifications` set `read` = true where `sentTo` = " . $_SESSION['userId'] . " and `type` = 1";

    if ($conn->query($sql) === true) {
      return "notifications are read";
    }
    else {
      echo "Error: ", $conn->error;
    }
  }

function markMessageAsReadFromSomeUser($conn, $userId) { // cascaded delete ?? 
    //$conn->query("set foreign_key_checks = 0"); // ????????/
    $sql = "update `chatmessages` set `new` = false where `sentFrom` = " . $userId . " and `sentTo` = {$_SESSION['userId']} and `new` = true";
    $sql1 = "update `notifications` set `read` = true where `sentFrom` = " . $userId . " and `sentTo` = {$_SESSION['userId']} and `type` = 1 and `read` = false";

    if ($conn->query($sql) === TRUE && $conn->query($sql1) === TRUE) {
      return "notification is read and message is seen";
    }
    else {
      echo "Error: ", $conn->error;
    }
  }

  // function markMessageAsReadGenerally($conn, $userId) { // cascaded delete ?? 
  //   //$conn->query("set foreign_key_checks = 0"); // ????????/
  //   $sql = "update `chatmessages` set `new` = false where (`sentTo` = {$_SESSION['userId']} and `sentFrom` = " . $userId . ")";

  //   if ($conn->query($sql) === TRUE) {
  //     return "message is seen";
  //   }
  //   else {
  //     echo "Error: ", $conn->error;
  //   }
  // }

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