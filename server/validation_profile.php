<?php require_once("../server/validation_functions.php"); ?>
<?php
$message = "";
$check = false;
if (isset($_POST["edit_profile"])) {
    $id_toupdate = $_SESSION['UserID'];
    $new_first_name = isset($_POST['first_name']) ? trim($_POST["first_name"]) : "";
    $new_last_name = isset($_POST['last_name']) ? trim($_POST["last_name"]) : "";
    $new_dob = isset($_POST['date_of_birth']) ? trim($_POST["date_of_birth"]) : "";
    $new_location = isset($_POST["location"]) ? trim($_POST["location"]) : null;
    $new_email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    $new_phone = isset($_POST["phone_number"]) ? trim($_POST["phone_number"]) : null;
    $new_interest = trim($_POST["interests"]);

    // Check required fields
    $fields_required = array("first_name", "last_name" ,"email", "date_of_birth");
    validate_presences($fields_required);

    // Check if date is correct
    if (!validateDate($new_dob)) {
        $errors["Dob"] = "Invalid date";
    }
    // Validate phone number
    if (!validatePhone($new_phone)) {
        $errors["Phone"] = "Please input a valid phone number like (07 XXX XXXXXX) (Country Code optional)";
    }


    if (empty($errors)) {
        // All necessary info is entered.           
        // escaping and stripping tags from firstName, lastName, email and location
        $new_email = mysqli_real_escape_string($conn, $new_email);
        $new_email = strip_tags($new_email);
        $new_email = htmlentities($new_email);
        $new_location = mysqli_real_escape_string($conn, $new_location);
        $new_location = strip_tags($new_location);
        $new_location = htmlentities($new_location);
        $new_first_name = mysqli_real_escape_string($conn, $new_first_name);
        $new_first_name = strip_tags($new_first_name);
        $new_first_name = htmlentities($new_first_name);
        $new_last_name = mysqli_real_escape_string($conn, $new_last_name);
        $new_last_name = strip_tags($new_last_name);
        $new_last_name = htmlentities($new_last_name);

        $sql = "UPDATE user
            SET FirstName = '$new_first_name', LastName = '$new_last_name', Email = '$new_email', DateOfBirth = '$new_dob', CurrentLocation = '$new_location', PhoneNumber = '$new_phone', Interest = '$new_interest'
            WHERE UserID = '$id_toupdate';";

            $result = mysqli_query($conn, $sql);
            confirm_query($sql);

            $found_user = find_user_by_id($id_toupdate);
            if ($found_user) {
                    $_SESSION["UserID"] = $found_user["UserID"];
                    $_SESSION["FirstName"] = $found_user["FirstName"];
                    $_SESSION["LastName"] = $found_user["LastName"];
                    $_SESSION["Email"] = $found_user["Email"];
            } else {
                    $message = "Updating Error. Please refresh the page.";
            }
        

    } else {
        $check = true;
        $message .= form_errors($errors);
    }

} elseif(isset($_POST["delete_account"])) {
    $userid = $_SESSION['UserID'];
    $query = "DELETE From User ";
    $query .= "WHERE UserID = '{$userid}' ";
    $result = mysqli_query($conn, $query);
    confirm_query($query);
    $_SESSION['message'] = "Farewell!";
    redirect_to("login.php");
} elseif (isset($_POST["change_password"])) {

    $message = "";
    $new_password = isset($_POST["new_password"]) ? trim($_POST["new_password"]) : "";
    $new_password_confirm = isset($_POST["password_confirm"]) ? trim($_POST["password_confirm"]) : "";

    if ($new_password != $new_password_confirm) {
        $errors["Passwords2"] = "Passwords do not match.";
    }
    // Check for minimum length

        $min_length = 5;
        $fields_with_min_length = array("new_password");
        validate_min_length($fields_with_min_length, $min_length);

        if (empty($errors)) {
            $new_password = mysqli_real_escape_string($conn, $new_password);
            $hashed_password = password_encrypt($new_password);
            $query = "UPDATE user
                      SET Password = '{$hashed_password}'
                      WHERE UserID = '{$_SESSION["UserID"]}'";
            $result = mysqli_query($conn, $query);
            $message = ($result) ? "Password successfully changed" : "Sorry, something went wrong.";
        } else {
            $check = true;
            $message .= form_errors($errors);
        }

} else {
}
