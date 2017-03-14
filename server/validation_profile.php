<?php require_once("../server/validation_functions.php"); ?>
<?php
if (isset($_POST["edit_profile"])) {
    $id_toupdate = $_SESSION['UserID'];
    $new_dob = isset($_POST['date_of_birth']) ? trim($_POST["date_of_birth"]) : "";
    $new_location = isset($_POST["location"]) ? trim($_POST["location"]) : null;
    $new_email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    $new_phone = isset($_POST["phone_number"]) ? trim($_POST["phone_number"]) : null;

    // escaping email and location
    $new_email = mysqli_real_escape_string($conn, $new_email);
    $new_email = strip_tags($new_email);
    $new_email = htmlentities($new_email);
    $new_location = mysqli_real_escape_string($conn, $new_location);
    $new_location = strip_tags($new_location);
    $new_location = htmlentities($new_location);


    // Regex check for the phone number
    $pattern = "/^(\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3}$/";
    $match = preg_match($pattern, $new_phone);

    $fields_required = array("email", "date_of_birth");
    validate_presences($fields_required);

    if (empty($errors)) {
        // All necessary info is entered.
        if (validateDate($new_dob)) {
            if ($match != false) {
                $sql = "UPDATE user
                    SET Email = '$new_email', DateOfBirth = '$new_dob', CurrentLocation = '$new_location', PhoneNumber = '$new_phone'
                    WHERE UserID = '$id_toupdate';";

                $result = mysqli_query($conn, $sql);
                confirm_query($sql);
            } else {
                $_SESSION['message'] = "Please enter a valid Phone Number like +44 7222 555 555.";
            }
        } else {
            $_SESSION['message'] = "Please enter a valid Date of Birth.";
        }

    } else {
        $_SESSION['message'] = "Please do not leave Email or Date of Birth blank!";
    }

} else {
    // Do nothing.
}
