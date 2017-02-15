<?php require_once("../server/validation_functions.php"); ?>
<?php
if (isset($_POST["register"])) {
    $user_email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : "";
    $first_name = isset($_POST["first_name"]) ? trim($_POST["first_name"]) : "";
    $last_name = isset($_POST["last_name"]) ? trim($_POST["last_name"]) : "";
    $date_of_birth = isset($_POST["date_of_birth"]) ? $_POST["date_of_birth"] : date("Y-m-d"); // date format in MySQL
    // Check if any fields are blank
    $fields_required = array("password", "email", "first_name", "last_name", "date_of_birth", "gender");
    validate_presences($fields_required);
    // Check for minimum length
    $min_length = 5;
    $fields_with_min_length = array("password");
    validate_min_length($fields_with_min_length, $min_length);
    // Check if email already exists
    $query = "SELECT Email FROM User ";
    $query .= "WHERE Email = '{$user_email}' ";
    $result = mysqli_query($conn, $query);
    confirm_query($result);
    if (mysqli_num_rows($result)>0) {
        $errors["Email"] = "Email already exists.";
    }
    mysqli_free_result($result);

    if (empty($errors)) {
        // Successful validation
        // Escape strings to avoid SQL injection
        $password = mysqli_real_escape_string($conn, $password);
        $user_email = mysqli_real_escape_string($conn, $user_email);
        $first_name = mysqli_real_escape_string($conn, $first_name);
        $last_name = mysqli_real_escape_string($conn, $last_name);
        $date_of_birth = mysqli_real_escape_string($conn, $date_of_birth);

        // Encrypt password
        $hashed_password = password_encrypt($password);
        // Generate datetime
        $date_joined = date('Y-m-d H:i:s');
        // Enumerate gender
        $ismale = ($_POST["gender"]==="male") ? 1 : 0;

        // Insert
        $query = "INSERT INTO User (Password, Email, FirstName, LastName, DateJoined, Dob, Gender) ";
        $query .= "VALUES (";
        $query .= "'{$hashed_password}', '{$user_email}', '{$first_name}', '{$last_name}', '{$date_joined}', '{$date_of_birth}', '{$ismale}'";
        $query .= ")";
        $result = mysqli_query($conn, $query);
        if ($result) {
            // Success registration
            $_SESSION["message"] = "User successfully created.";
            redirect_to("login.php");
        } else {
            // Failure
            $_SESSION["message"] = "User creation failed. Some information was not valid.";
            redirect_to("register_form.php");
        }
    } else {
        $message = "Registration failed.";
        $message .= form_errors($errors);
    }
} else {
    $user_email = "";
    $first_name = "";
    $last_name = "";
    $date_of_birth = date("Y-m-d");
    $message = "Please register.";
}
