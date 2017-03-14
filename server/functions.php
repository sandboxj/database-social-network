<?php
function redirect_to($new_location)
{
    header("Location: " . $new_location);
    exit;
}

function confirm_query($result_set)
{
    if (!$result_set) {
        die("Database query failed.");
    }
}

function password_encrypt($password)
{
    // Encrypt password
    $hash_format = "$2y$10$"; // Tells php to use Blowfish encryption with a "cost" of 10
    $salt_length = "22"; // Blowfish should be 22 characters or more
    $salt = generate_salt($salt_length);
    $format_salt = $hash_format . $salt;
    $hash = crypt($password, $format_salt);
    return $hash;
}

function generate_salt($length)
{
    $unique_random_string = md5(uniqid(mt_rand(), true)); // true for added string length
    $base64_string = base64_encode($unique_random_string); // return valid chars for $salt step 1
    $mod_base64_string = str_replace("+", ".", $base64_string); // step 2, repalce + with .
    $salt = substr($mod_base64_string, 0, $length); // truncate string to correct length
    return $salt;
}

function password_check($password, $existing_hash)
{
    // existing hash contains format and salt
    $hash = crypt($password, $existing_hash);
    if ($hash === $existing_hash) {
        return true;
    } else {
        return false;
    }
}

function attempt_login($email, $password)
{
    $user = find_user_by_email($email, $password);
    if ($user) {
        // if user matches check password
        if (password_check($password, $user["Password"])) {
            return $user;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function find_user_by_email($email)
{
    global $conn;
    $safe_email = mysqli_real_escape_string($conn, $email);

    $query = "SELECT * FROM User ";
    $query .= "WHERE ";
    $query .= "Email = '{$safe_email}' ";
    $query .= "LIMIT 1";
    $user_set = mysqli_query($conn, $query);
    confirm_query($user_set);
    if ($user = mysqli_fetch_assoc($user_set)) {
        mysqli_free_result($user_set);
        return $user;
    } else {
        return null;
    }
}

function find_user_by_id($id)
{
    global $conn;
    $safe_email = mysqli_real_escape_string($conn, $id);

    $query = "SELECT * FROM User ";
    $query .= "WHERE ";
    $query .= "UserID = '{$id}' ";
    $query .= "LIMIT 1";
    $user_set = mysqli_query($conn, $query);
    confirm_query($user_set);
    if ($user = mysqli_fetch_assoc($user_set)) {
        mysqli_free_result($user_set);
        return $user;
    } else {
        return null;
    }
}

function logged_in()
{
    return isset($_SESSION["UserID"]);
}

function confirm_logged_in()
{
    if (!logged_in()) {
        redirect_to("login.php");
    }
}

function print_access_selector() {
    echo ("<select name='access'>
                <option value='0'>Only me</option>
                <option selected value='1'>Friends</option>
                <option value='2'>Everybody</option>
                <option value='3'>Circles</option>
                <option value='4'>Friends of Friends</option>
            </select>");
}

/**
 * This function converts access rights in integer form to a string equivalent so that this value
 * may be rendered in the DOM. This is because access rights are stored in integer form inside the database.
 * @param $access_rights
 * @return string
 */
function convert_access_rights_to_string($access_rights){

    switch($access_rights){
        case 0:
            $access_rights = "Only me";
            break;
        case 1:
            $access_rights = "Friends";
            break;
        case 2:
            $access_rights = "Everybody";
            break;
        case 3:
            $access_rights = "Circles";
            break;
        case 4:
            $access_rights = "Friends of friends";
            break;
    }

    return $access_rights;
}
