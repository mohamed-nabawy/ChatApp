<?php
//var_dump($_SERVER);
  require('ChatApp/chat/backend/functions.php');
  require('ChatApp/chat/backend/controllers/Dates.php');
  require('ChatApp/chat/backend/validation-functions.php');

  if ( isset($_GET['redirect_to'] ) ) {
    $_POST['redirect_to'] = $_GET['redirect_to'];
  }
  if ( isset($_POST['submit']) ) { // check if the button 's been pressed
    // Process the form
    // validations
    $required_fields = array("email", "password");
    validate_presences($required_fields);
    
    if (empty($errors) ) {
      // Attempt Login
  		$email = $_POST['email'];
  		$password = $_POST['password'];
  		$found_user = attempt_login($conn, $email, $password);
      if ($found_user) {
        // Success
  			// Mark user as logged in
  			$_SESSION["userId"] = $found_user["Id"];
  			$_SESSION["userName"] = $found_user["UserName"];
      
        // record date
        if (!getCurrentDateId($conn) ) {// make the server add it automatically
          addTodayDate($conn, true);
        }
        
        if ( isset($_POST['remember']) ) { // set the cookie to a long date
          setcookie(session_name(), session_id(), time() + 42000000, '/');
        }

        if ( isset($_POST['redirect_to']) ) { // make restrictions on pages that request this page ,otherwise redirect to the same page to cancel his header

        }
        header("Location: "."/ChatApp/chat/index.php");
      }
      else {
        // Failure
        $_SESSION["message"] = "Username/password not found.";
        header("Location: "."/ChatApp/chat/frontend/login.php");
      }
    }
    // if already logged in and called login page
    elseif (isset($_SESSION["userId"]) && isset($_SESSION["userName"]) && isset($_SESSION["roleId"]) ) { // This is probably a GET   request

    } // end: if (isset($_POST['submit']))
  }
?>