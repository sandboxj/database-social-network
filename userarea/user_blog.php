<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require("../server/user_functions.php");?>
<?php require("../server/functions_blog.php");?>
<?php require_once("../server/db_connection.php");?>
<?php $page_title="{$_GET['title']}"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

<!--THIS PAGE IS FOR AN EXTERNAL VIEW OF THE BLOG i.e. when viewing the blogs of other users-->
<?php

//currently connected user
$viewer_userID = $_SESSION["UserID"];

//blog author
$friend_userid = $_GET['user'];

$friend_full_name = find_full_name($friend_userid);
$first_name= $friend_full_name['FirstName'];
$last_name = $friend_full_name['LastName'];


$blog_title = $_GET['title'];
$blogID = find_blog_id($friend_userid,$blog_title);
$blog_content = find_blog_content($blogID);
$blog_access_rights=find_blog_access_rights($blogID);

$date_posted= find_blog_date($blogID);
$formatted_datetime = display_formatted_date($date_posted);

$author_output = "Author: {$first_name} {$last_name} <td><br>";



//blog comment validation and insertion
/*
 * After pressing the post comment button. Validates the comment and inserts it in the db
 */
if (isset($_POST["blog_comment"])){
    $comment_content = $_POST["blog_comment"];

    $empty_check= validate_comment_input($comment_content);
    if($empty_check == false){

        $comment_content = strip_tags($comment_content);
        $comment_content = htmlentities($comment_content);
        insert_comment($blogID,$viewer_userID,$comment_content);
    }

}

?>

<!--TITLE, AUTHOR and DATE-->

<div class="container-fluid"  >
    <div class="row top-buffer" >
        <div class="col-md-7" id="card-header" >
<!--NEED TO DOUBLE CHECK THIS CSS -->
                <div class="card-title" >
                    <h2><?php echo "Title: {$blog_title}" ?></h2>
                    <h3><?php echo $author_output;?></h3>
                    <h5 ><?php echo $formatted_datetime ?></h5>
                    <br>
                </div>

        </div>


    </div>
    <br>

<!--    BLOG CONTENT (EDITABLE)-->
    <div class="row top-buffer">
        <div class="col-md-7" id="card-content">
            <br>
            <div class="card-content">
                <p><?php echo $blog_content ?></p>
            </div>
        </div>

    </div>

</div>



<!--Comment section header-->
<div class="container-fluid comment-section">
    <div class="row top-buffer">
        <div class="col-md-3">
            <h3>Comments:</h3>
        </div>
    </div>
</div>

<!--Comments-->
<div class="container-fluid">
    <div class="row">


            <?php $blog_comment_results = find_blog_comments($blogID);

            while($blog_comments = mysqli_fetch_assoc($blog_comment_results)) {
                $commenter_userID = $blog_comments['CommenterUserID'];
                $commenter_full_name = find_full_name($commenter_userID);
                $commenter_first_name= $commenter_full_name["FirstName"];
                $commenter_last_name = $commenter_full_name["LastName"];

                $comment_author = "{$commenter_first_name} {$commenter_last_name}";

                $comment_date = $blog_comments['DatePosted'];
                $comment_date_formatted = display_formatted_date($comment_date);


                $comment_content = $blog_comments['Content'];

                ?>
        <div class="container-fluid">
        <div class="col-md-7" id="comment">
                <br>
                <?php
                /*
                 * If statement here to change the hyperlink from the commenter name. It will send users
                 * to their own profile view (profile.php) if they click on their own name; it will send them
                 * to the correct profile if they press another user's name (user_profile.php)
                 */
                if($commenter_userID == $viewer_userID) {


                    ?>
                    <p><?php echo "<a href='profile.php'>" . $comment_author . "</a>" ?>
                        said: <?php echo "<h4>{$comment_content}</h4>"; ?></p>
                    <hr>
                    <?php
                }else{
                    ?>
                    <p><?php echo "<a href='user_profile.php?id={$commenter_userID}'>" . $comment_author . "</a>" ?>
                        said: <?php echo "<h4>{$comment_content}</h4>"; ?></p>
                    <hr>

        <?php
                }//closing else
                    ?>

                <p><?php echo "<h5>{$comment_date_formatted}</h5>"; ?></p>
                <br>
        </div>
        </div>
                <br>
                <?php

//bracket to close the while loop
            }
            ?>

    </div>
</div>
<br>


<!--ADD A COMMENT -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-7">
            <form action="user_blog.php?title=<?php echo $blog_title?>&user=<?php echo $friend_userid;?>" method="post">
                <h4>Comment</h4><textarea style="width:95%;"  name="blog_comment" placeholder="Write your comment here..."></textarea><br />
                <button type="submit" class="btn btn-primary">Add a comment</button>

            </form>
        </div>
    </div>
</div>


<?php include("../includes/footer.php"); ?>
