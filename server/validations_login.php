<?php
if (isset($_POST["login"])) {
    $username = isset($_POST["username"]) ? trim($_POST["username"]) : "";
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : "";
    // Check if any fields are blank
    $fields_required = array("username", "password");
    validate_presences($fields_required);

    if (empty($errors)) {
        // Attempt login
        $found_user = attempt_login($username, $password);
        if ($found_user) {
            $_SESSION["UserID"] = $found_user["UserID"];
            $_SESSION["FirstName"] = $found_user["FirstName"];
            redirect_to("blog.php");
        } else {
            $message = "Username/Password not found.";
        }
    } else {
        $message = "Username/Password not found.";
    }
} else {
    $username = "";
    $message = "<b>Please enter your login details.</b>";
}

