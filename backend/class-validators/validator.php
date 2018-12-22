<?php

require_once(dirname(__DIR__) . '/helpers/session.php');

class Validator {

    public function confirmQuery($result_set) {
      if (!$result_set) {
        die("Database query failed.");
      }
    }

    public function validatePageAccess($permittedLevels, $checklogging = true) {
        $permitted = false;
    
        foreach ($permittedLevels as $key => $value) {
          if ($value == $_SESSION['roleId']) {
            $permitted = true;
          }
        }
    
        if (!$permitted) {
            header("Location: " . '/frontend/login.php');
        }
    }

    public function test_inputs($conn, $array) {
      foreach ($array as $data) {
        $data = trim($data);

        if ( has_presence($data) ) {
          $data = mysqli_real_escape_string($conn, $data); // for sql injection
          $data = htmlspecialchars($data);
        }
        else {
          return false;
        }
      }

      return $array;
    }

    public function fieldname_as_text($fieldname) {
      $fieldname = str_replace("_", " ", $fieldname);
      $fieldname = ucfirst($fieldname);
      return $fieldname;
    }

    // * presence
    // use trim() so empty spaces don't count
    // use === to avoid false positives
    // empty() would consider "0" to be empty
    public function has_presence($value) {
      return isset($value) && $value !== "";
    }

    public function validate_presences($required_fields) {
      $errors = [];

      foreach($required_fields as $field) {
        $value = trim( $_POST[$field] );

        if ( !$this->has_presence($value) ) {
          $errors[$field] = fieldname_as_text($field) . " can't be blank";
        }
      }

      return $errors;
    }

    // * string length
    // max length


    // * inclusion in a set
    public function has_inclusion_in($value, $set) {
      return in_array($value, $set);
    }


  public function checkCSRFToken() {
    if ( isset($_POST['csrf_token']) ) {
        if ( hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']) ) {
            return true;
        }
        else {
            echo 'error';
            
            return false;
        }
    }
    else {
        $data = json_decode( file_get_contents('php://input') );
        $csrf_token = '';

        if ( isset($data->csrf_token) ) {
            $csrf_token = $data->csrf_token;
        }
        else {
            echo "error";

            return false;
        }

        if ( hash_equals($_SESSION['csrf_token'], $csrf_token) ) {
            echo true;

            return true;
        }
        else {
            echo 'error';

            return false;
        }
    }
  }

  public function normalizeString($conn, &...$values) {
    foreach ($values as &$value) {
        $value = trim($value);

        if ($value !== "") {
            $value = str_replace('&', 'and', $value);
            $value = mysqli_real_escape_string($conn, $value);
            $value = htmlspecialchars($value);
        }
        else {
            return false;
        }
    }

    return true;
  }

  public function hasMaxLength($value, $max) {
    return strlen($value) <= $max;
  }

  public function validateMaxLengths($fields_with_max_lengths) {
    // Expects an assoc. array
    foreach ($fields_with_max_lengths as $field => $max) {
        $value = trim($field);

        if ( !has_max_length($value, $max) ) {
            echo "max length exceeded";
            return false;
        }
    }

    return true;
  }

  public function testInt($value) {
    if (filter_var($value, FILTER_VALIDATE_INT) !== null) {
        return true;
    }
  }

  public function testMutipleInts(&...$values) {

    foreach ($values as &$value) {
        if ( !ctype_digit($value) && !( is_int($value) || is_double($value) ) ) {
            return false;
        }
    }

    return true;
  }

  public function checkResult($result) {
    if ( isset($result) ) {
        echo json_encode($result);
    }
  }
}

?>