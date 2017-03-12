<?php require_once("../server/validation_functions.php");?>
<?php
$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if (isset($_POST["post_comment"])) {
    // Check if post is blank
    if (strlen(trim($_POST["comment"]))) {
                
                // Enter comment into DB
                $photoid = $_GET["PhotoID"];
                $userid = ($is_owner) ? $_GET["id"] : $_SESSION["UserID"];
                $post_time = date('Y-m-d H:i:s');
                $content = mysqli_real_escape_string($conn, $_POST["comment"]);
                $query = "INSERT INTO photo_comment (PhotoID, CommenterUserID, DatePosted, Content) ";
                $query .= "VALUES ('{$photoid}', '{$userid}', '{$post_time}', '{$content}')";
                $result = mysqli_query($conn, $query);
                $_SESSION["message"] = ($result) ? "" : "Post failed.";
    } else {
        $_SESSION["message"] = "Cannot post empty comment.";
    }
    redirect_to("{$actual_link}");
} 
elseif (isset($_POST["delete_comment"])) {
    $id_todelete = $_POST["delete_comment"];
    $query = "DELETE FROM photo_comment ";
    $query .= "WHERE PhotoCommentID = '{$id_todelete}'";
    $result = mysqli_query($conn, $query);
    $_SESSION["message"] = ($result) ? "" : "Deleting post failed.";
    redirect_to("{$actual_link}");
}
else {
    // Do nothing
}
