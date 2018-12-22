<?php

class MyGenerator {
    public function __construct() {
        //print "In MyGenerator constructor\n";
    }

    public function __destruct() {
        //print "Destroying " . __CLASS__ . "\n";
    }

    public function form_errors( $errors = [] ) {
        $output = "";

        if ( !empty($errors) ) {
            $output .= "<div class=\"error\"> ";
            $output .= "Please fix the following errors:";
            $output .= "<ul class=\"error\">";

            foreach ($errors as $key => $error) {
                $output .= "<li>";
                $output .= htmlentities($error);
                $output .= "</li>";
            }

            $output .= "</ul>";
            $output .= "</div>";
        }

        return $output;
    }

    public function password_encrypt($password) {
        $hash_format = "$2y$10$";   // Tells PHP to use Blowfish with a "cost" or rounds of 10
        $salt_length = 22; 					// Blowfish salts should be 22-characters or more
        $salt = $this->generate_salt($salt_length);
        $format_and_salt = $hash_format . $salt;
        $hash = crypt($password, $format_and_salt);

        return $hash;
    }

    public function generate_salt($length) {
        // Not 100% unique, not 100% random, but good enough for a salt
        // MD5 returns 32 characters
        $unique_random_string = md5( uniqid(mt_rand(), true) );
        
        // Valid characters for a salt are [a-zA-Z0-9./]
        $base64_string = base64_encode($unique_random_string);
        
        // But not '+' which is valid in base64 encoding
        $modified_base64_string = str_replace('+', '.', $base64_string);
        
        // Truncate string to the correct length
        $salt = substr($modified_base64_string, 0, $length);
        
        return $salt;
    }

    public function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); // remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; // put the length -1 in cache

        for ($i = 0; $i < 10; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n]; // add to the end of the array
        }

        return implode($pass); // turn the array into a string
    }
}

?>