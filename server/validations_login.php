<?php require_once("../server/validation_functions.php");?>
<?php
if (isset($_POST["login"])) {
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : "";
    // Check if any fields are blank
    $fields_required = array("email", "password");
    validate_presences($fields_required);

    if (empty($errors)) {
        // Attempt login
        $found_user = attempt_login($email, $password);
        if ($found_user) {
            $_SESSION["UserID"] = $found_user["UserID"];
            $_SESSION["FirstName"] = $found_user["FirstName"];
            $_SESSION["LastName"] = $found_user["LastName"];
            $_SESSION["DateOfBirth"] = $found_user["DateOfBirth"];
            redirect_to("blog.php");
        } else {
            $message = "Username/Password not found.";
        }
    } else {
        $message = "Username/Password not found.";
    }
} else {
    $email = "";
    $message = "<b>Please enter your login details.</b>";
}
