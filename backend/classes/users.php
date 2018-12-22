<?php

require(__DIR__ . '/image-handler.php');
require(__DIR__ . '/mail-sender.php');
require(dirname(__DIR__) . '/helpers/generator.php');

class User {
  private $generator = NULL;

  public function __construct(MyGenerator $generator) {
    $this->generator = $generator;
  }

  public function getUsers($conn) { // in the same class
    $sql = "select * from `users` where `id` != " . $_SESSION['userId'];
    $result = $conn->query($sql);

    if ($result) {
      $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
      mysqli_free_result($result);

      return $users;
    }
    else {
      echo "Error retrieving Users: ", $conn->error;
    }
  }

  public function addChatUser($user) { //add user to open chats
    if (count($_SESSION['chats']) < 3) {
      array_push($_SESSION['chats'], $user);
    }
    else {
      array_splice($_SESSION['chats'], 2, 0, [$user]);
    }
  }

  public function deleteChat($id) { // close the window
    foreach ($_SESSION['chats'] as $key => $value) {
      if ($value->secondUserId == $id) {
        array_splice($_SESSION['chats'], $key, 1);

        break;
      }
    }
  }

  public function getUserById($conn, $id) { // check validations on this
    $sql = "select * from `users` where `id` = " . $id . " limit 1";
    $result = $conn->query($sql);

    if ($result) {
      $user = mysqli_fetch_assoc($result);
      mysqli_free_result($result);

      return $user;
    }
    else {
      echo "Error retrieving User: ", $conn->error;
    }
  }

  public function openOrCloseChat($chatId, $open) {
    foreach ($_SESSION['chats'] as $key => &$value) {
      if ($value->secondUserId == $chatId) {
        $value->open = $open;

        break;
      }
    }
  }

  public function getCurrentUser($conn) {
    $sql = "select * from `users` where `id` = " . $_SESSION['userId'] . " limit 1";
    $result = $conn->query($sql);

    if ($result) {
      $user = mysqli_fetch_assoc($result);
      mysqli_free_result($result);

      return $user;
    }
    else {
      echo "Error retrieving User: ", $conn->error;
    } 
  }

  public function addUser($conn, $firstName, $lastName, $image, $email, $phoneNumber, $password, $dateOfBirth, $genderId, $roleId, $x1 = null, $y1 = null, $w = null, $h = null) {
    $x = $this->checkExistingEmail($conn, $email);

    //$generator = new Generator();

    if ($x) {
      return "email already existed";
    }

    $sql = "insert into `users` (firstName, lastName, image, email, phoneNumber, passwordHash, dateOfBirth, genderId, roleId, croppedImage, imageSet) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $pass =  $this->generator->password_encrypt($password);
    // $pass =  '23';
    $stmt->bind_param("sssssssiisi", $firstName, $lastName, $image, $email, $phoneNumber, $pass, $dateOfBirth, $genderId, $roleId, $croppedImage, $imageSet);

    if ($image != null && $image['size'] != 0) {
      $imageRes = addImageFile($image, $email, $x1, $y1, $w, $h);
      $image = $imageRes[0];
      $croppedImage = $imageRes[1];
      $_SESSION['imageSet'] = 1;
      $imageSet = 1;
    }
    else {
      $_SESSION['imageSet'] = 0;
      $imageSet = 0;
      $image = '/uploads/';

      if ($genderId == 1) { // male
        $image .= 'maleimage.jpeg';
      }
      else if ($genderId == 2) {
        $image .= 'femaleimage.jpeg';
      }
      else {
        echo "error";
        return;
      }

      $croppedImage = $image;
    }

    if ($stmt->execute() === TRUE) {
      $user_id = mysqli_insert_id($conn);
      
      // Success
      // Mark user as logged in
      $_SESSION['userId'] = $user_id;
      $_SESSION['roleId'] = $roleId;
      $_SESSION['chats'] = [];

      sendMail($email, $user_id, $phoneNumber);
      
      return $user_id;
    }
    else {
      echo "Error: ", $conn->error;
    }
  }

  public function editUser($conn, $userName, $firstName, $lastName, $email, $image, $phoneNumber, $roleId, $id) {
    $userUserName = mysqli_fetch_assoc( $conn->query("select `userName` from `users` where `id` = " . $id) )['userName'];
    
    if ( !($userUserName == $userName) && $this->checkExistingUserName($conn, $userName, true) )  {
      echo "existing username or email";

      return;
    }
    else {
      $result = $conn->query("select `image` from `users` where `id` = " . $id);
      $userImage = mysqli_fetch_assoc($result)['image'];
      mysqli_free_result($result);
      $sql = "update `users` set `userName` = (?), `firstName` = (?) , `lastName` = (?) , `email` = (?) , `image` = (?) , `phoneNumber` = (?) , `roleId` = (?) where `id` = (?)"; 
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssssssii", $userName, $firstName, $lastName, $email, $Image, $phoneNumber, $roleId, $id);

      if ($stmt->execute() === TRUE) {
        echo "User updated successfully";
      }
      else {
        echo "Error: ", $conn->error;
      }
    }
  }

  public function activateUser($conn, $id) { // check validations on this
    $sql = "update `users` set `confirmed` = true where `id` = (?)"; 
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute() === TRUE) {
      return true;
    }
    else {
      echo "Error: ", $conn->error;
    }
  }

  public function checkExistingUserName($conn, $userName, $register_edit) {
    $UserName = mysqli_real_escape_string($conn, $userName);
    $sql = "select count(*) from `users` where `userName` = '{$UserName}'";
    $result = $conn->query($sql);

    if ($result) {
      $result = mysqli_fetch_array($result, MYSQLI_NUM); 
      $result = (int)$result[0];

      if ($result > 0) { // if he wnats to change the mail and not keeping the old
        return true; // exist
      }
      else {
        return false;//not exist
      }
    }
    else {
      echo "Error: ", $conn->error;
    }
  }
    
  // need to know if he's entering the same email or not as the condition will differ
  public function checkExistingEmail($conn, $email) { // problem if he wants to edit his info cause' of his email
    $email = trim($email);
    $Email = mysqli_real_escape_string($conn, $email);
    $sql = "select count(*) from `users` where `email` = '{$Email}'";
    $result = $conn->query($sql);

    if ($result) {
      $result = mysqli_fetch_array($result, MYSQLI_NUM);
      $result = (int)$result[0];

      if ($result > 0) { // if he wants to change the mail and not keeping the old
        return true; // exist
      }
      else {
        return false; //not exist
      }

      mysqli_free_result($result);
    }
    else {
      echo "Error: ", $conn->error;
    }
  }

  public function deleteUser($conn, $id) { // cascaded delete ??
    $sql = "delete from `users` where `id` = " . $id . " limit 1";

    if ($conn->query($sql) === TRUE) {
      echo "User deleted successfully";
    }
    else {
      echo "Error: ", $conn->error;
    }
  }

  public function getUserByEmail($conn, $email) {
    if ( !isset($email) ) {
      echo "Error: User Email is not set";
      
      return;
    }
    else {
      $safe_email = mysqli_real_escape_string($conn, $email);
      $query  = "SELECT * ";
      $query .= "FROM `users` ";
      $query .= "WHERE `email` = '{$safe_email}' ";
      $query .= "LIMIT 1";
      $user_set = mysqli_query($conn, $query);
      //confirmQuery($user_set);

      if ( $user = mysqli_fetch_assoc($user_set) ) {
        return $user;
      }
      else {
        return null;
      }
    }
  }
}