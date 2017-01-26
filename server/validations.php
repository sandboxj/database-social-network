<?php
	  require_once("server/validation_functions.php");
    $errors = array();
    if(isset($_POST["register"])) {
        $username = isset($_POST["username"]) ? trim($_POST["username"]) : "";
        $password = isset($_POST["password"]) ? trim($_POST["password"]) : "";
		$first_name = isset($_POST["first_name"]) ? trim($_POST["first_name"]) : "";
		$last_name = isset($_POST["last_name"]) ? trim($_POST["last_name"]) : "";
        // Check if any fields are blank
        $fields_required = array("username", "password", "first_name", "last_name");
        foreach($fields_required as $field) {
            $value = $_POST[$field];
            if(!has_presence($value)) {
                $errors[$field] = ucfirst($field) . " can't be blank";
            }
        }
        // Check for minimum length
        $min_length = 5;
        $fields_with_min_length = array("username", "password");
         foreach($fields_required as $field) {
            $value = $_POST[$field];
            if(!validate_min_length($value, $min_length)) {
                $errors[$field] = ucfirst($field) . " is too short. Minimum of " . $min_length . " characters are required.";
            }
        }
        if (empty($errors)) {
            // Successful validation
            // Escape strings to avoid SQL injection

            // Insert
            $query = "INSERT INTO User (UserID, Password)";
            $query .= " VALUES ('{$username}', '{$password}')";
            $result = mysqli_query($conn, $query);
            if($result) {
                // Success registration
                redirect_to("server/register_user.php");
            } else {
                // Failure
                die("User creation failed");
            }
        } else {          
            $message = "Registration failed.";
            $message .= form_errors($errors);
        }
    } else {
        $username = "";
		$first_name = "";
		$last_name = "";
        $message = "Please register.";
    }
?>