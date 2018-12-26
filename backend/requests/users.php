<?php
  require(dirname(__DIR__) . '/controllers/users.php');
  require(dirname(__DIR__) . '/controllers/chat-messages.php');
  require(dirname(__DIR__) . '/class-validators/user-validator.php');
  require_once(dirname(__DIR__) . '/helpers/connection.php');

  $userValidator = new UserValidator();
  $userController = new UserController(new User(new MyGenerator()));
  $chatMessageController = new ChatMessageController(new ChatMessage(new Time(), new Date()));

  if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      if (isset($_GET['flag']) && $userValidator->testInt($_GET['flag']) && $_GET['flag'] == 1) {
        $users = $_SESSION['chats'];
        
        foreach ($users as $key => $value) {
          $messages = $chatMessageController->getMessagesBetweenUsersIdsInClass($conn, $value->firstUserId, $value->secondUserId, 1, 0);
          $value->messages = $messages;
        }

        $userValidator->checkResult($users);
      }
      elseif (isset($_GET['id']) && $userValidator->testInt($_GET['id']) ) {
        $userValidator->checkResult( $userController->getUserById( $conn, $_GET['id'] ) );
      }
      elseif (isset($_GET['flag']) && $userValidator->testInt($_GET['flag']) && $_GET['flag'] == 3) {
        $userValidator->checkResult( $userController->getCurrentUser($conn) );
      }
      elseif (isset($_GET['flag']) && $userValidator->testInt($_GET['flag']) && $_GET['flag'] == 1) {
        $userValidator->checkResult( $userController->getCurrentUser($conn) );
      }
      else {
        $userValidator->checkResult( $userController->getUsers($conn) );
      }
  }
  elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_GET['flag']) && $_GET['flag'] == 2) {
      $data = json_decode( file_get_contents('php://input') );
      $email = $data->Email;
      echo $userController->checkExistingEmail($conn, $email);
    }
    elseif (isset($_GET['update']) && $_GET['update'] == 1) {
      $data = json_decode( file_get_contents('php://input') );

      if ( isset($data->x1, $data->y1, $data->w, $data->h) && ( ($data->x1 == '' && $data->y1 == '' && $data->w == '' && $data->h == '') || $userValidator->testMutipleInts($data->x1, $data->y1, $data->w, $data->h) ) ) {
          $x1 = $y1 = $w = $h = null;

          if ( ($data->x1 != '' && $data->y1 != '' && $data->w != '' && $data->h != '') ) {
              $x1 = $data->x1;
              $y1 = $data->y1;
              $w  = $data->w;
              $h  = $data->h;
          }

          handlePictureUpdate($conn, $data->Image, $x1, $y1, $w, $h);
      }
    }
    else {
      $result = isset($_POST['firstName'], $_POST['lastName'], $_POST['phone'], $_POST['email'], $_POST['DOB'],
        $_POST['genderId'], $_POST['password']) && $userValidator->normalizeString($conn, $_POST['firstName'],
        $_POST['lastName']) && $userValidator->testPhone($_POST['phone']) && $userValidator->testEmail($_POST['email'])
        && $userValidator->testDateOfBirth($_POST['DOB']) && $userValidator->testInt($_POST['genderId'])
        && $userValidator->testPassword($_POST['password']);

      if ($result) {
        if (isset($_POST['update']) && $_POST['update'] == 1) {
          $x1 = $_POST['x1'];
          $y1 = $_POST['y1'];
          $w  = $_POST['w'];
          $h  = $_POST['h'];

          handlePictureUpdate($conn, $_FILES['image'], $x1, $y1, $w, $h);
          header("Location: " . '/frontend/areas/student/student-profile.php');
        }
        else {
          $result = $result && ($_POST['confirmPassword'] == $_POST['password']);

          if ($result) {
            $x1 = $_POST['x1'];
            $y1 = $_POST['y1'];
            $w  = $_POST['w'];
            $h  = $_POST['h'];
            $userValidator->normalizeString($conn, $_FILES['image']['name']);
            $userId = $userController->addUser($conn, $_POST['firstName'], $_POST['lastName'], $_FILES['image'], $_POST['email'], $_POST['phone'], $_POST['password'], $_POST['DOB'], $_POST['genderId'], 1, $x1, $y1, $w, $h);
            $_SESSION['userId'] = $userId;
            $x = mysqli_fetch_assoc( $conn->query('select `image`, `croppedImage` from `users` where `id` = ' . $userId) );
            $_SESSION['genderId'] = $_POST['genderId'];
            $_SESSION['image'] = $x['image'];
            $_SESSION['email']  = $_POST['email'];
            $_SESSION['croppedImage'] = $x['croppedImage'];
            
            header("Location: " . '/frontend/areas/student/student-profile.php');
          }
          else {
            header("Location: " . '/frontend/register.php');
          }
        }
      }
    }
    // else {
    //   header("Location: " . '/frontend/register.php');
    // }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    if (isset($_GET['open'], $_GET['chatId']) && $userValidator->testInt($_GET['open'], $_GET['chatId']) ) {
      $userController->openOrCloseChat($_GET['chatId'], $_GET['open']);
    }
      // decode the json data
    elseif ( !isset( $_GET['flag'] ) ) {
      $data = json_decode( file_get_contents('php://input') );
      $result = isset($data->RoleId, $data->Id, $data->UserName, $data->FirstName, $data->LastName, $data->Email, $data->PhoneNumber) && testInt($data->RoleId, $data->Id) && normalizeString($conn, $data->UserName, $data->FirstName, $data->LastName) && testPhone($data->PhoneNumber) && testEmail($data->Email);

      if ($result) {
        $userValidator->normalizeString($conn, $data->Image);
        $userController->editUser($conn, $data->UserName, $data->FirstName, $data->LastName, $data->Email, $data->Image, $data->PhoneNumber, $data->RoleId, $data->Id);
      }
    }
    elseif ($_GET['flag'] == 1) {
      $userController->addChatUser( json_decode( file_get_contents('php://input') ) );
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    if (isset($_GET['userId']) && $userValidator->testInt($_GET['userId']) ) {
      $userController->deleteUser($conn, $_GET['userId']);
    }
    elseif (isset($_GET['id']) && $userValidator->testInt($_GET['id']) ) {
      $userController->deleteChat($_GET['id']);
    }
    elseif (isset($_GET['f']) && $_GET['f'] == 1) {
      $_SESSION['imageSet'] = 0;
      $conn->query("update `users` set `imageSet` = 0 where `id` = '{$_SESSION['userId']}'");
      $type = pathinfo($_SESSION['image'], PATHINFO_EXTENSION);
      $imageFileName = dirname(__DIR__, 2) . '/uploads/' . $_SESSION['email'];
      $croppedImageFileName = $imageFileName;

      if ($type == 'jpeg') {
          $imageFileName .= '.jpeg';
          $croppedImageFileName .= '_crop.jpeg';

      }
      else {
          $imageFileName .= '.png';
          $croppedImageFileName .= '_crop.png';
      }

      unlink($croppedImageFileName);
      unlink($imageFileName);

      $_SESSION['image'] = '/uploads/';

      if ($_SESSION['genderId'] == 1) { // male
          $_SESSION['image'] .= 'maleimage.jpeg';
      }
      else {
          $_SESSION['image'] .= 'femaleimage.jpeg';
      }

      $_SESSION['croppedImage'] = $_SESSION['image'];

      $conn->query("update `users` set `image` = '{$_SESSION['image']}', `croppedImage` = '{$_SESSION['croppedImage']}' where `id` = '{$_SESSION['userId']}'");
    }
  }

  require(dirname(__DIR__) . '/helpers/footer.php');
?>