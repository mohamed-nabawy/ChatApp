<?php
  require('ChatApp/chat/backend/controllers/messages.php');
  require('ChatApp/chat/backend/test-request-input.php');

  if ($_SERVER['REQUEST_METHOD'] == 'GET')
  {
  
      if (isset($_GET['firstUserId'],$_GET['secondUserId']) && test_int($_GET['firstUserId'],$_GET['secondUserId']))
      {
        checkResult(getMessagesBetweenUsersIdsInClass($conn,$_GET['firstUserId'],$_GET['secondUserId'] ,$_GET['classId']));
      }
      else
      {
        RecieveNewMessageForUserIdInClass($conn,$_GET['UserId']);
      }
    
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    //if ($_SESSION['roleId'] == 1)
    //{
      // decode the json data
      $data = json_decode(file_get_contents('php://input'));
      $result = isset($data->Content, $data->SentFrom, $data->SentTo,$data->ClassId) && normalize_string($data->Content) && test_int($data->SentFrom, $data->SentTo,$data->ClassId);
      if ($result)
      {
        echo SendMessage($conn, $data->Content, $data->SentFrom, $data->SentTo,$data->ClassId );
      }
    //}
  }

  // if ($_SERVER['REQUEST_METHOD'] == 'PUT')
  // {
  //   if ($_SESSION['roleId'] == 1)
  //   {
  //     // decode the json data
  //     $data = json_decode(file_get_contents('php://input'));
  //     $result = isset($data->RoleId,$data->Id,$data->UserName,$data->FirstName,$data->LastName,$data->Email,$data->PhoneNumber) && test_int($data->RoleId,$data->Id) && normalize_string($conn,$data->UserName,$data->FirstName,$data->LastName) && test_phone($data->PhoneNumber) && test_email($data->Email);
  //     if ($result)
  //     {
  //       normalize_string($conn,$data->Image);
  //       editUser($conn,$data->UserName,$data->FirstName,$data->LastName,$data->Email,$data->Image,$data->PhoneNumber,$data->RoleId,$data->Id);
  //     }
  //   }
  // }

  // if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
  // {
  //   if ($_SESSION['roleId'] == 1)
  //   {
  //     // decode the json data
  //     if (isset($_GET['userId']) && test_int($_GET['userId']))
  //     {
  //       deleteUser($conn,$_GET['userId']);
  //     }
  //   }
  // }

  require('chat/backend/footer.php');
?>