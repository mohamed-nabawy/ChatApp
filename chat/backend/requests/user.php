<?php
  require('chat/backend/controllers/user.php');
  require('chat/backend/test-request-input.php');

  if ($_SERVER['REQUEST_METHOD'] == 'GET')
  {
    //if ($_SESSION['roleId'] == 1) // admin only can call these methods
    //{
      if (isset($_GET['userId']) && test_int($_GET['userId']))
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
      $result = isset($_POST['firstName'], $_POST['lastName'], $_POST['phone'], $_POST['email'], $_POST['DOB'], $_POST['gender']) && normalize_string($conn, $_POST['firstName'], $_POST['lastName']) && test_phone($_POST['phone']) && test_email($_POST['email']) && test_date_of_birth($_POST['DOB']) && test_int($_POST['gender']);
      if ($result)
      {
        normalize_string($conn, $_FILES['file']['name']);
        echo addUser($conn, $_POST['firstName'], $_POST['lastName'], $_FILES['file'], $_POST['email'], $_POST['phone'], $_POST['password'], $_POST['DOB'], $_POST['gender'], 1);
        header("Location: ". "/chat/frontend/login.php");
      }
    //}
  }

  if ($_SERVER['REQUEST_METHOD'] == 'PUT')
  {
    //if ($_SESSION['roleId'] == 1)
    //{
      // decode the json data
      $data = json_decode(file_get_contents('php://input'));
      $result = isset($data->RoleId, $data->Id, $data->UserName, $data->FirstName, $data->LastName, $data->Email, $data->PhoneNumber) && test_int($data->RoleId, $data->Id) && normalize_string($conn, $data->UserName, $data->FirstName, $data->LastName) && test_phone($data->PhoneNumber) && test_email($data->Email);
      if ($result)
      {
        normalize_string($conn, $data->Image);
        editUser($conn, $data->UserName, $data->FirstName, $data->LastName, $data->Email, $data->Image, $data->PhoneNumber, $data->RoleId, $data->Id);
      }
    //}
  }

  if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
  {
    //if ($_SESSION['roleId'] == 1)
    //{
      // decode the json data
      if (isset($_GET['userId']) && test_int($_GET['userId']))
      {
        deleteUser($conn, $_GET['userId']);
      }
    //}
  }

  require('chat/backend/footer.php');
?>