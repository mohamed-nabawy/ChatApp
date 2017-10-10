<?php
  require('ChatApp/chat/backend/controllers/user.php');
  require('ChatApp/chat/backend/test-request-input.php');

  if ($_SERVER['REQUEST_METHOD'] == 'GET')
  {
    //if ($_SESSION['roleId'] == 1) // admin only can call these methods
    //{
      if (isset($_GET['userId']) && testInt($_GET['userId']))
      {
        checkResult(getUserById($conn, $_GET['userId']));
      }
      else
      {
        checkResult(getUsers($conn));
      }
    //}
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    //echo "1";
    //var_dump($_SERVER);
    //if ($_SESSION['roleId'] == 1)
    //{
      // decode the json data
      //$data = json_decode(file_get_contents('php://input'));
      $result = isset($_POST['firstName'], $_POST['lastName'], $_POST['phone'], $_POST['email'], $_POST['DOB'], $_POST['gender']) && normalizeString($conn, $_POST['firstName'], $_POST['lastName']) && testPhone($_POST['phone']) && testEmail($_POST['email']) && testDateOfBirth($_POST['DOB']) && testInt($_POST['gender']);
      //var_dump($result);
      if ($result)
      {
        normalizeString($conn, $_FILES['image']['name']);
        echo addUser($conn, $_POST['firstName'], $_POST['lastName'], $_FILES['image'], $_POST['email'], $_POST['phone'], $_POST['password'], $_POST['DOB'], $_POST['gender'], 1);
        header("Location: ". "/ChatApp/chat/index.php");
      }
    //}
  }

  if ($_SERVER['REQUEST_METHOD'] == 'PUT')
  {
    //if ($_SESSION['roleId'] == 1)
    //{
      // decode the json data
      $data = json_decode(file_get_contents('php://input'));
      $result = isset($data->RoleId, $data->Id, $data->UserName, $data->FirstName, $data->LastName, $data->Email, $data->PhoneNumber) && testInt($data->RoleId, $data->Id) && normalizeString($conn, $data->UserName, $data->FirstName, $data->LastName) && testPhone($data->PhoneNumber) && testEmail($data->Email);
      if ($result)
      {
        normalizeString($conn, $data->Image);
        editUser($conn, $data->UserName, $data->FirstName, $data->LastName, $data->Email, $data->Image, $data->PhoneNumber, $data->RoleId, $data->Id);
      }
    //}
  }

  if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
  {
    //if ($_SESSION['roleId'] == 1)
    //{
      // decode the json data
      if (isset($_GET['userId']) && testInt($_GET['userId']))
      {
        deleteUser($conn, $_GET['userId']);
      }
    //}
  }

  require('ChatApp/chat/backend/footer.php');
?>