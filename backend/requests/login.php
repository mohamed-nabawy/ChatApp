<?php
  require_once(dirname(__DIR__) . '/classes/dates.php');
  require_once(dirname(__DIR__) . '/class-validators/user-validator.php');
  require_once(dirname(__DIR__) . '/helpers/connection.php');
  //require(dirname(__DIR__) . '/helpers/session.php');

  $date = new Date();
  $userValidator = new UserValidator();

  var_dump($_POST);

  if ( isset($_GET['redirect_to']) ) {
    $_POST['redirect_to'] = $_GET['redirect_to'];
  }
  if ( isset($_POST['submit']) ) { // check if the button 's been pressed
    // Process the form
    // validations
    $required_fields = array("email", "password");
    $errors = $userValidator->validate_presences($required_fields);
    
    if (empty($errors) ) {
      // Attempt Login
  		$email = $_POST['email'];
  		$password = $_POST['password'];
      $found_user = $userValidator->attempt_login($conn, $email, $password);
      
      if ($found_user) {
        // Success
  			// Mark user as logged in
  			$_SESSION['userId'] = $found_user['id'];
  			//$_SESSION['userName'] = $found_user['userName'];
        $_SESSION['roleId'] = $found_user['roleId'];
        $_SESSION['chats'] = array(); // empty array of chats
        $_SESSION['image'] = $found_user['image'];
        $_SESSION['croppedImage'] = $found_user['croppedImage'];
        $_SESSION['email'] = $found_user['email'];
        $_SESSION['genderId'] = $found_user['genderId'];
      
        // record date
        if (!$date->getCurrentDateId($conn) ) { // make the server add it automatically
          $date->addTodayDate($conn, true);
        }
        
        if ( isset($_POST['remember']) ) { // set the cookie to a long date
          setcookie(session_name(), session_id(), time() + 42000000, '/');
        }

        if ( isset($_POST['redirect_to']) ) { // make restrictions on pages that request this page ,otherwise redirect to the same page to cancel his header

        }
        if ($_SESSION['roleId'] == 1) {
          header("Location: " . "/frontend/areas/student/student-profile.php");
        }
      }
      else {
        // Failure

        $_SESSION['message'] = "Username/password not found.";
        header("Location: " . "/frontend/login.php");
      }
    }
    // if already logged in and called login page
    elseif (isset($_SESSION['userId']) && isset($_SESSION['userName']) && isset($_SESSION['roleId']) ) { // This is probably a GET   request

    } // end: if (isset($_POST['submit']))
  }

  require(dirname(__DIR__) . '/helpers/footer.php');
?>