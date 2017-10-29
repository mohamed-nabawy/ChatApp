<?php
  require('ChatApp/chat/backend/controllers/users.php');
  require('ChatApp/chat/backend/test-request-input.php');

  if ($_SERVER['REQUEST_METHOD'] == 'GET')
  {
    //if ($_SESSION['roleId'] == 1) // admin only can call these methods
    //{
      if (isset($_GET['flag']) && testInt($_GET['flag']) && $_GET['flag'] == 1)
      {
        checkResult($_SESSION["chats"] );
      }
      elseif (isset($_GET['flag']) && testInt($_GET['flag']) && $_GET['flag'] == 2)
      {
        checkResult($_SESSION["active"] );
      }
      elseif (isset($_GET['id']) && testInt($_GET['id']) )
      {
        checkResult( getUserById($conn, $_GET['id']) );
      }
      elseif (isset($_GET["flag"]) && testInt($_GET["flag"]) && $_GET["flag"] == 3) {
        checkResult( getCurrentUser($conn) );
      }
      else
      {
        checkResult( getUsers($conn) );
      }
    //}
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    //echo "1";
    //if ($_SESSION['roleId'] == 1)
    //{
      // decode the json data
      //$data = json_decode(file_get_contents('php://input'));
    
      $result = isset($_POST['firstName'], $_POST['lastName'], $_POST['phone'], $_POST['email'], $_POST['DOB'], $_POST['gender'], $_POST['password']) && normalizeString($conn, $_POST['firstName'], $_POST['lastName']) && testPhone($_POST['phone']) && testEmail($_POST['email']) && testDateOfBirth($_POST['DOB']) && testInt($_POST['gender']) && testPassword($_POST['password']);
      //var_dump($result);
      if ($result)
      {
        echo "2";
        normalizeString($conn, $_FILES['image']['name']);
        echo addUser($conn, $_POST['firstName'], $_POST['lastName'], $_FILES['image'], $_POST['email'], $_POST['phone'], $_POST['password'], $_POST['DOB'], $_POST['gender'], 1);
        //header("Location: ". "/ChatApp/chat/frontend/areas/student/student-profile.php");
      }
      else {
        //echo "3";
        header("Location: ". "/ChatApp/chat/frontend/register.php");
      }
    //}
  }

  if ($_SERVER['REQUEST_METHOD'] == 'PUT')
  {
    //if ($_SESSION['roleId'] == 1)
    //{
      // decode the json data
    if (!isset($_GET["flag"]) )
    {
      $data = json_decode(file_get_contents('php://input') );
      $result = isset($data->RoleId, $data->Id, $data->UserName, $data->FirstName, $data->LastName, $data->Email, $data->PhoneNumber) && testInt($data->RoleId, $data->Id) && normalizeString($conn, $data->UserName, $data->FirstName, $data->LastName) && testPhone($data->PhoneNumber) && testEmail($data->Email);
      if ($result)
      {
        normalizeString($conn, $data->Image);
        editUser($conn, $data->UserName, $data->FirstName, $data->LastName, $data->Email, $data->Image, $data->PhoneNumber, $data->RoleId, $data->Id);
      }
    }
    elseif ($_GET["flag"] == 1) {
      addChatUser( json_decode( file_get_contents('php://input') ) );
    }
    elseif ($_GET["flag"] == 2) {
      changeActiveUser( json_decode( file_get_contents('php://input') ) );
    }
    //}
  }

  if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    //if ($_SESSION['roleId'] == 1)
    //{
      // decode the json data
      // var_dump($_SERVER);
      if (isset($_GET['userId']) && testInt($_GET['userId']) ) {
        deleteUser($conn, $_GET['userId']);
      }

      elseif (isset($_GET["id"]) && testInt($_GET["id"]) ) {
        deleteChat($_GET["id"]);
      }
    //}
  }

  require('ChatApp/chat/backend/footer.php');
?>