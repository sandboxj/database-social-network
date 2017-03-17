<?php require_once("../server/validation_functions.php");?>
<?php
if (isset($_POST["add_friend"])) {
    $id_toupdate = $_POST["add_friend"];
    $query = "UPDATE friendship ";
    $query .= "SET Status = 1 ";
    $query .= "WHERE FriendshipID = '{$id_toupdate}'";
    $result = mysqli_query($conn, $query);
    confirm_query($result);
} 
elseif (isset($_POST["decline_friend"])) {
    $id_todelete = $_POST['decline_friend'];
    $query = "DELETE FROM friendship ";
    $query .= "WHERE FriendshipID = '{$id_todelete}'";
    $result = mysqli_query($conn, $query);
    confirm_query($result);
}
elseif (isset($_POST["add_request"])) {
    $id_to_add = $_POST["add_request"];
    $date = date('Y-m-d H:i:s');
    $userid = $_SESSION['UserID'];
    $query = "INSERT INTO friendship (User1ID, User2ID, Status, Date) VALUES (";
    $query .= "'{$userid}', '{$id_to_add}', 0, '{$date}')";
    $result = mysqli_query($conn, $query);
    confirm_query($result);
    $_SESSION["message"] = ($result) ? "Friend request sent." : "Friend request failed.";

} elseif (isset($_POST["do_not_recommend"])) {
    $unrecommend_query = "INSERT INTO do_not_recommend (UserID, UnknownUserID)
						              VALUES ('{$_SESSION["UserID"]}', '{$_POST["do_not_recommend"]}')";
    $unrecommend = mysqli_query($conn, $unrecommend_query);
}
else {
    // Do nothing
}
