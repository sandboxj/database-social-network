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
	if($user) {
		// if user matches check password 
		if(password_check($password, $user["Password"])) {
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

function logged_in() {
    return isset($_SESSION["UserID"]);
}

function confirm_logged_in() {
    if(!logged_in()) {
        redirect_to("login.php");
    }
}

function find_blogs($userid) {
            global $conn;
			$query = "SELECT * FROM Blog ";
            $query .= "LEFT JOIN User ";
            $query .= "ON Blog.UserID = User.UserID ";
    		$query .= "AND Blog.UserID = '{$userid}' ";
            $query .= "ORDER BY ";
            $query .= "DatePosted DESC";
			$blog_results = mysqli_query($conn, $query);	
			confirm_query($blog_results);
            return $blog_results;
}

function find_profile_pic($userid) {
            global $conn;
            // $search_term = $userid . "_profilepicture% ";
			$query = "SELECT * FROM Photo ";
			$query .= "WHERE ";
    		$query .= "PhotoID LIKE '{$userid}_profilepicture%' ";
            $query .= "LIMIT 1";
			$pic_results = mysqli_query($conn, $query);	
			confirm_query($pic_results);
            return $pic_results;
}