<?php require_once("../server/validation_functions.php");?>
<?php
if (isset($_POST["blog_post"])) {
    // Check if post is blank
    if (strlen(trim($_POST["blog_content"]))) {
        if (strlen(trim($_POST["blog_title"]))) {
            // Gather content 
            $blog_content = mysqli_real_escape_string($conn, $_POST["blog_content"]);
            $blog_title = mysqli_real_escape_string($conn, $_POST["blog_title"]);
            $title_check = "SELECT * FROM blog
                            WHERE blog.UserID = '{$_SESSION["UserID"]}'
                            AND blog.Title = '{$_POST["blog_title"]}'";
            $out = mysqli_query($conn, $title_check);
            $title = mysqli_fetch_assoc($out);
            if ($title["Title"] === $_POST["blog_title"]) {
                $_SESSION["message"] = "You already have a blog with this title, please change the title.";
                redirect_to("../userarea/blogs.php");
            } else {
                $title_check .= "VALUES ('{$userid}', '{$blog_title}', '{$blog_content}', '{$post_time}', '{$access_rights}')";
                $access_rights = mysqli_real_escape_string($conn, $_POST["access"]);
                $post_time = date('Y-m-d H:i:s');
                // Enter post into DB
                $userid = $_SESSION["UserID"];
                $query = "INSERT INTO Blog (UserID, Title, Content, DatePosted, AccessRights) ";
                $query .= "VALUES ('{$userid}', '{$blog_title}', '{$blog_content}', '{$post_time}', '{$access_rights}')";
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
