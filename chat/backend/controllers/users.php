<?php

  require('ChatApp/chat/backend/functions.php');
  require('ChatApp/chat/backend/image-handle.php');

  function getUsers($conn) { // in the same class
    $sql = "select * from `users` where `id` != ".$_SESSION['userId'];
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

  function addChatUser($user) {   //add user to open chats
    array_push($_SESSION["chats"], $user);
  }

  function deleteChat($id) {   // close the window
    foreach ($_SESSION["chats"] as $key => $value) {
      if ($value->id == $id) {
        array_splice($_SESSION["chats"], $key, 1);
        break;
      }
    }
  }

  function getUserById($conn, $id) { // check validations on this
    $sql = "select * from `users` where id = ".$id." limit 1";
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

  function changeActiveUser($user) {
    $_SESSION["active"] = $user;
  }

  function getCurrentUser($conn) {
    $sql = "select * from `users` where id = ".$_SESSION["userId"]." limit 1";
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

  function addUser($conn, $firstName, $lastName, $image, $email, $phoneNumber, $password, $dateOfBirth, $gender, $roleId) {
    $sql = "insert into `users` (userName, firstName, lastName, image, email, phoneNumber, passwordHash, dateOfBirth, gender, roleId) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("ssssssssii", $UserName, $FirstName, $LastName, $Image, $Email, $PhoneNumber, $PasswordHash, $DateOfBirth, $Gender, $RoleId);
    
    $UserName = $email;
    $FirstName = $firstName;
    $LastName = $lastName;

    if (isset($image) && $image['name'] != "") {
      $Image = addImageFile($image, $email);
    }
    else {
      if ($gender == 0) {
        $Image = '/ChatApp/chat/backend/uploads/maleimage.jpg';
      }
      elseif ($gender == 1) {
        $Image = '/ChatApp/chat/backend/uploads/femaleimage.jpg';
      }
    }

    $Email = $email;
    $PhoneNumber = $phoneNumber;
    $PasswordHash = password_encrypt($password);
    $RoleId = $roleId;
    $DateOfBirth = $dateOfBirth;
    $Gender = $gender;

    if ($stmt->execute() === TRUE) {
      $user_id = mysqli_insert_id($conn);
      
      // Success
      // Mark user as logged in
      $_SESSION["userId"] = $user_id;
      $_SESSION["userName"] = $email;
      $_SESSION["roleId"] = $roleId;
      $_SESSION["chats"] = [];
      header("Location: ". "/ChatApp/chat/frontend/areas/student/student-profile.php");
      return $user_id;
    }
    else {
      echo "Error: ", $conn->error;
      //return false;
    }
  }

  function editUser($conn, $userName, $firstName, $lastName, $email, $image, $phoneNumber, $roleId, $id) {
    $userUserName = mysqli_fetch_assoc($conn->query("select `userName` from `users` where `id` = ".$id) )['userName'];
    
    if (!($userUserName == $userName) && checkExistingUserName($conn,$userName, true) )  {
      echo "existing username or email";
      return;
    }
    else {
      $result = $conn->query("select `image` from `users` where `id` = ".$id);
      $userImage = mysqli_fetch_assoc($result)['image'];
      mysqli_free_result($result);
      $sql = "update `users` set `userName` = (?), `firstName` = (?) , `lastName` = (?) , `email` = (?) , `image` = (?) , `phoneNumber` = (?) , `roleId` = (?) where `id` = (?)"; 
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssssssii", $UserName, $FirstName, $LastName, $Email, $Image, $PhoneNumber, $RoleId, $Id);
      $UserName = $userName;
      $FirstName = $firstName;
      $LastName = $lastName;
      $Email = $email;
      // if (isset($image) && $image != $userImage)
      // {
      //   $Image = editImage($image, $userImage, $userName);
      // }
      // else
      // {
      //   $Image = $image;
      // }
      $PhoneNumber = $phoneNumber;
      $RoleId = $roleId;
      $Id = $id;

      if ($stmt->execute() === TRUE) {
        echo "User updated successfully";
      }
      else {
        echo "Error: ", $conn->error;
      }
    }
  }

  // function updateUserPasswordByEmail($conn,$password,$email)
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

  // function updateUserPasswordById($conn,$password,$id)
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

  function activateUser($conn, $id) { // check validations on this
    $sql = "update `users` set `confirmed` = true where `id` = (?)"; 
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $Id);
    $Id = $id;
    if ($stmt->execute() === TRUE) {
      return true;
    }
    else {
      echo "Error: ", $conn->error;
    }
  }

  function checkExistingUserName($conn, $userName, $register_edit) {
    $UserName = mysqli_real_escape_string($conn, $userName);
    $sql = "select count(*) from `users` where `userName` = '{$UserName}'";
    $result = $conn->query($sql);
    if ($result) {
      $result = mysqli_fetch_array($result, MYSQLI_NUM); 
      //mysqli_free_result($result);
      $result = (int)$result[0];
      if ($result > 0) { // if he wnats to change the mail and not keeping the old
      
        //echo "existing username" 
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
  function checkExistingEmail($conn, $email) { // problem if he wants to edit his info cause' of his email
    $email = trim($email);
    $Email = mysqli_real_escape_string($conn, $email);
    $sql = "select count(*) from `users` where `email` = '{$Email}'";
    $result = $conn->query($sql);
    if ($result) {
      $result = mysqli_fetch_array($result, MYSQLI_NUM);
      $result = (int)$result[0];
      if ($result > 0) { // if he wants to change the mail and not keeping the old
        //echo "existing email";
        return true; // exist
      }
      else {
        return false;//not exist
      }
      mysqli_free_result($result);
    }
    else {
      echo "Error: ", $conn->error;
    }
  }

  function deleteUser($conn, $id) { // cascaded delete ??
    //$conn->query("set foreign_key_checks = 0"); // ????????/
    $sql = "delete from `users` where `id` = ".$id . " limit 1";
    if ($conn->query($sql) === TRUE) {
      echo "User deleted successfully";
    }
    else {
      echo "Error: ", $conn->error;
    }
  }

?>