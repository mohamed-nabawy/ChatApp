<?php
  require(dirname(__DIR__) . '/classes/users.php');

  class UserController {

    public function getUsers($conn) { // in the same class
      $user = new User();
      // $sql = "select * from `users` where `id` != " . $_SESSION['userId'];
      // $result = $conn->query($sql);

      // if ($result) {
      //   $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
      //   mysqli_free_result($result);
      //   return $users;
      // }
      // else {
      //   echo "Error retrieving Users: ", $conn->error;
      // }
      return $user->getUsers($conn);
    }

    public function addChatUser($user) { //add user to open chats
      $user1 = new User();
      // if (count($_SESSION['chats']) < 3) {
      //   array_push($_SESSION['chats'], $user);
      // }
      // else {
      //   array_splice($_SESSION['chats'], 2, 0, [$user]);
      // }
      return $user1->addChatUser($user);
    }

    public function deleteChat($id) { // close the window
      $user = new User();
      // foreach ($_SESSION['chats'] as $key => $value) {
      //   if ($value->secondUserId == $id) {
      //     array_splice($_SESSION['chats'], $key, 1);
      //     break;
      //   }
      // }
      return $user->deleteChat($id);

    }

    public function getUserById($conn, $id) { // check validations on this
      $user = new User();
      // $sql = "select * from `users` where `id` = " . $id . " limit 1";
      // $result = $conn->query($sql);

      // if ($result) {
      //   $user = mysqli_fetch_assoc($result);
      //   mysqli_free_result($result);
      //   return $user;
      // }
      // else {
      //   echo "Error retrieving User: ", $conn->error;
      // }
      return $user->getUserById($conn, $id);
    }

    public function openOrCloseChat($chatId, $open) {
      $user = new User();
      // foreach ($_SESSION['chats'] as $key => &$value) {
      //   if ($value->secondUserId == $chatId) {
      //     $value->open = $open;
      //     break;
      //   }
      // }
      return $user->openOrCloseChat($chatId, $open);
    }

    public function getCurrentUser($conn) {
      $user = new User();
      // $sql = "select * from `users` where `id` = " . $_SESSION['userId'] . " limit 1";
      // $result = $conn->query($sql);

      // if ($result) {
      //   $user = mysqli_fetch_assoc($result);
      //   mysqli_free_result($result);
      //   return $user;
      // }
      // else {
      //   echo "Error retrieving User: ", $conn->error;
      // } 
      return $user->getCurrentUser($conn);
    }

    public function addUser($conn, $firstName, $lastName, $image, $email, $phoneNumber, $password, $dateOfBirth, $genderId, $roleId, $x1 = null, $y1 = null, $w = null, $h = null) {
      $user = new User();
      // $x = checkExistingEmail($conn, $email);

      // if ($x) {
      //   return "email already existed";
      // }

      // $sql = "insert into `users` (firstName, lastName, image, email, phoneNumber, passwordHash, dateOfBirth, genderId, roleId, croppedImage, imageSet) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      // $stmt = $conn->prepare($sql);
      // $pass =  password_encrypt($password);
      // $stmt->bind_param("sssssssiisi", $firstName, $lastName, $image, $email, $phoneNumber, $pass, $dateOfBirth, $genderId, $roleId, $croppedImage, $imageSet);

      // if ($image != null && $image['size'] != 0) {
      //   $imageRes = addImageFile($image, $email, $x1, $y1, $w, $h);
      //   $image = $imageRes[0];
      //   $croppedImage = $imageRes[1];
      //   $_SESSION['imageSet'] = 1;
      //   $imageSet = 1;
      // }
      // else {
      //   $_SESSION['imageSet'] = 0;
      //   $imageSet = 0;
      //   $image = '/uploads/';

      //   if ($genderId == 1) { // male
      //     $image .= 'maleimage.jpeg';
      //   }
      //   else if ($genderId == 2) {
      //     $image .= 'femaleimage.jpeg';
      //   }
      //   else {
      //     echo "error";
      //     return;
      //   }

      //   $croppedImage = $image;
      // }

      // if ($stmt->execute() === TRUE) {
      //   $user_id = mysqli_insert_id($conn);
        
      //   // Success
      //   // Mark user as logged in
      //   $_SESSION['userId'] = $user_id;
      //   $_SESSION['roleId'] = $roleId;
      //   $_SESSION['chats'] = [];

      //   sendMail($email, $user_id, $phoneNumber);
      //   //header("Location: ". "../../frontend/areas/student/student-profile.php");
        
      //   return $user_id;
      // }
      // else {
      //   echo "Error: ", $conn->error;
      //   //return false;
      // }
      return $user->addUser($conn, $firstName, $lastName, $image, $email, $phoneNumber, $password, $dateOfBirth, $genderId, $roleId, $x1 = null, $y1 = null, $w = null, $h = null);
    }

    public function editUser($conn, $userName, $firstName, $lastName, $email, $image, $phoneNumber, $roleId, $id) {
      $user = new User();
      // $userUserName = mysqli_fetch_assoc( $conn->query("select `userName` from `users` where `id` = " . $id) )['userName'];
      
      // if ( !($userUserName == $userName) && checkExistingUserName($conn, $userName, true) )  {
      //   echo "existing username or email";
      //   return;
      // }
      // else {
      //   $result = $conn->query("select `image` from `users` where `id` = " . $id);
      //   $userImage = mysqli_fetch_assoc($result)['image'];
      //   mysqli_free_result($result);
      //   $sql = "update `users` set `userName` = (?), `firstName` = (?) , `lastName` = (?) , `email` = (?) , `image` = (?) , `phoneNumber` = (?) , `roleId` = (?) where `id` = (?)"; 
      //   $stmt = $conn->prepare($sql);
      //   $stmt->bind_param("ssssssii", $userName, $firstName, $lastName, $email, $Image, $phoneNumber, $roleId, $id);

      //   // if (isset($image) && $image != $userImage)
      //   // {
      //   //   $Image = editImage($image, $userImage, $userName);
      //   // }
      //   // else
      //   // {
      //   //   $Image = $image;
      //   // }

      //   if ($stmt->execute() === TRUE) {
      //     echo "User updated successfully";
      //   }
      //   else {
      //     echo "Error: ", $conn->error;
      //   }
      // }
      return $user->editUser($conn, $userName, $firstName, $lastName, $email, $image, $phoneNumber, $roleId, $id);
    }

    // public function updateUserPasswordByEmail($conn,$password,$email)
    // {
    //   if (!isset($password) || !isset($email)) 
    //   {
    //     //echo "User password is empty !";
    //     return;
    //   }
    //   else
    //   {
    //     $sql = "update User set PasswordHash = (?) where Email = (?)"; 
    //     $stmt = $conn->prepare($sql);
    //     $stmt->bind_param("ss",$Password,$Email);
    //     $Email = $email;
    //     $Password = $password;
    //     if ($stmt->execute() === TRUE)
    //     {
    //       return "User updated successfully";
    //     }
    //     else
    //     {
    //       echo "Error: ".$conn->error;
    //     }
    //   }
    // }

    // public function updateUserPasswordById($conn,$password,$id)
    // {
    //    if (!isset($password) || !isset($id)) 
    //   {
    //     //echo "User password is empty !";
    //     return;
    //   }
    //   else
    //   {
    //     $sql = "update User set PasswordHash = (?) where Id = (?)"; 
    //     $stmt = $conn->prepare($sql);
    //     $stmt->bind_param("si",$Password,$Id);
    //     $Id = $id;
    //     $Password = $password;
    //     if ($stmt->execute() === TRUE)
    //     {
    //       return "User updated successfully";
    //     }
    //     else
    //     {
    //       echo "Error: ".$conn->error;
    //     }
    //   }
    // }

    public function activateUser($conn, $id) { // check validations on this
      $user = new User();
      // $sql = "update `users` set `confirmed` = true where `id` = (?)"; 
      // $stmt = $conn->prepare($sql);
      // $stmt->bind_param("i", $id);
      
      // if ($stmt->execute() === TRUE) {
      //   return true;
      // }
      // else {
      //   echo "Error: ", $conn->error;
      // }
      return $user->activateUser($conn, $id);
    }

    public function checkExistingUserName($conn, $userName, $register_edit) {
      $user = new User();
      // $UserName = mysqli_real_escape_string($conn, $userName);
      // $sql = "select count(*) from `users` where `userName` = '{$UserName}'";
      // $result = $conn->query($sql);

      // if ($result) {
      //   $result = mysqli_fetch_array($result, MYSQLI_NUM); 
      //   //mysqli_free_result($result);
      //   $result = (int)$result[0];
      //   if ($result > 0) { // if he wnats to change the mail and not keeping the old
        
      //     //echo "existing username" 
      //     return true; // exist
      //   }
      //   else {
      //     return false;//not exist
      //   }
      // }
      // else {
      //   echo "Error: ", $conn->error;
      // }
      return $user->checkExistingUserName($conn, $userName, $register_edit);
    }
      
    // need to know if he's entering the same email or not as the condition will differ
    public function checkExistingEmail($conn, $email) { // problem if he wants to edit his info cause' of his email
      $user = new User();
      // $email = trim($email);
      // $Email = mysqli_real_escape_string($conn, $email);
      // $sql = "select count(*) from `users` where `email` = '{$Email}'";
      // $result = $conn->query($sql);

      // if ($result) {
      //   $result = mysqli_fetch_array($result, MYSQLI_NUM);
      //   $result = (int)$result[0];

      //   if ($result > 0) { // if he wants to change the mail and not keeping the old
      //     //echo "existing email";
      //     return true; // exist
      //   }
      //   else {
      //     return false; //not exist
      //   }

      //   mysqli_free_result($result);
      // }
      // else {
      //   echo "Error: ", $conn->error;
      // }
      return $user->checkExistingEmail($conn, $email);
    }

    public function deleteUser($conn, $id) { // cascaded delete ??
      $user = new User();
      // //$conn->query("set foreign_key_checks = 0"); // ????????/
      // $sql = "delete from `users` where `id` = " . $id . " limit 1";

      // if ($conn->query($sql) === TRUE) {
      //   echo "User deleted successfully";
      // }
      // else {
      //   echo "Error: ", $conn->error;
      // }
      return $user->deleteUser($conn, $id);
    }
  }
?>