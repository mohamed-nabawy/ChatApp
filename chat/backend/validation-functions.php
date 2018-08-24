<?php
  $errors = [];

  function test_inputs($conn, $array) {
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

  function fieldname_as_text($fieldname) {
    $fieldname = str_replace("_", " ", $fieldname);
    $fieldname = ucfirst($fieldname);
    return $fieldname;
  }

  // * presence
  // use trim() so empty spaces don't count
  // use === to avoid false positives
  // empty() would consider "0" to be empty
  function has_presence($value) {
  	return isset($value) && $value !== "";
  }

  function validate_presences($required_fields) {
    global $errors;

    foreach($required_fields as $field) {
      $value = trim( $_POST[$field] );

    	if ( !has_presence($value) ) {
    		$errors[$field] = fieldname_as_text($field) . " can't be blank";
    	}
    }
  }

  // * string length
  // max length


  // * inclusion in a set
  function has_inclusion_in($value, $set) {
  	return in_array($value, $set);
  }

?>