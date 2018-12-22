<?php

require_once(__DIR__ . '/validator.php');

class UserValidator extends Validator {

    public function islogged_in() {
		return ( isset( $_SESSION['userId'] ) ); // for normal user and fb user check
	}
	
	public function confirm_logged_in() {
		if ( !$this->islogged_in() ) {
            header("Location: " . '/frontend/login.php');
		}
	}

    public function attempt_login($conn, $email, $password) {
        $user = $this->getUserByEmail($conn, $email);

        if ($user) {
            // found user, now check password
            if ($this->passwordCheck($password, $user['passwordHash']) ) {
                // password matches
                return $user;
            }
            else {
                // password does not match
                return false;
            }
        }
        else {
            // user not found
            return false;
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

    public function passwordCheck($password, $existing_hash) {
        // existing hash contains format and salt at start
        $hash = crypt($password, $existing_hash);
    
        if ($hash === $existing_hash) {
          return true;
        }
        else {
          return false;
        }
    }

    public function testDateOfBirth(&$value) {
        if (!$value) {
            return false;
        }
    
        try {
            new \DateTime($value);
            
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }
    
    public function testEmail(&$value) {
        $value = trim($value);

        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function testPhone(&$value) {
        $value = trim($value);
    
        if ( preg_match('/^\d{0,13}$/', $value) ) {
            return true;
        }
    
        return false;
    }

    public function testPassword(&$password) {
        $password = trim($password);
        $x        = preg_match('/^([a-zA-Z\d]){8,}$/', $password);
        $y        = preg_match('/([A-Z])/', $password);
    
        if (!$x || !$y) {
            return false;
        }
    
        return true;
    }
}