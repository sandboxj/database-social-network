<?php require_once("../server/validation_functions.php");?>
<?php
if (isset($_POST["blog_post"])) {

    // Check if post is blank
    if (strlen(trim($_POST["blog_content"]))) {


                $post_time = date('Y-m-d H:i:s');
                // Enter post into DB
                $userid = $_SESSION["UserID"];
                $query = "INSERT INTO blog_comment (BlogID, CommenterUserID, DatePosted, Content) ";
                $query .= "VALUES ('{$blogID}', '{$blog_title}', '{$blog_content}', '{$post_time}', '{$access_rights}')";
                $result = mysqli_query($conn, $query);
                $_SESSION["message"] = ($result) ? "" : "Blog post failed.";
                redirect_to("../userarea/blogs.php");
            }
        } else {
            $_SESSION["message"] = "Please enter a title.";
            redirect_to("../userarea/blogs.php");
        }

    } else {
        $_SESSION["message"] = "Blog post failed. Post cannot be empty.";
        redirect_to("../userarea/blogs.php");
    }
} else {
    // Do nothing
}
?>