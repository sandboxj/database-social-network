<?php require_once("sessions.php");?>
<?php require_once("db_connection.php");?>
<?php require_once("functions.php");?>
<?php require_once("validation_functions.php");?>

<?php
if (isset($_POST["blog_post"])) {
    // Check if post is blank
    if (strlen(trim($_POST["blog_content"]))) {
        // Gather content 
        $blog_content = mysqli_real_escape_string($conn, $_POST["blog_content"]);
        $post_time = date('Y-m-d H:i:s');
        // Enter post into DB
        $userid = $_SESSION["UserID"];
        $query = "INSERT INTO Blog (UserID, Content, DatePosted) ";
        $query .= "VALUES ('{$userid}', '{$blog_content}', '{$post_time}')";
        $result = mysqli_query($conn, $query);
        $_SESSION["message"] = ($result) ? "" : "Blog post failed.";
        redirect_to("../userarea/blog.php");
    } else {
        $_SESSION["message"] = "Blog post failed. Post cannot be empty.";
        redirect_to("../userarea/blog.php");
    }
} else {
    // redirect back and state post failed
    $_SESSION["message"] = "Blog post error.";
    redirect_to("../userarea/blog.php");
}

