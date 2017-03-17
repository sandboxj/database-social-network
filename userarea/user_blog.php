<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require("../server/functions_user.php");?>
<?php require("../server/functions_blog.php");?>
<?php require_once("../server/db_connection.php");?>
<?php $visited_user = find_user_by_id($_GET["id"]); ?>
<?php $page_title= "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Blogs"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

<!--THIS PAGE IS FOR AN EXTERNAL VIEW OF THE BLOG i.e. when viewing the blogs of other users-->
<?php

//currently connected user
$viewer_userID = $_SESSION["UserID"];

//blog author is the visited user
$visited_userID = $_GET['id'];


$first_name= $visited_user["FirstName"];
$last_name = $visited_user["LastName"];


$blog_title = $_GET['title'];
$current_blogID = find_blog_id($visited_userID,$blog_title);

//packaged the old queries into one
$blog_details = find_blog_details($current_blogID);

$blog_content = $blog_details["blog_content"];

//This avoids any \n\r type of characters in the output

$date_posted = $blog_details["blog_date"];

$formatted_datetime = display_formatted_date($date_posted);


$blog_access_rights=find_blog_access_rights($current_blogID);



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
        insert_comment($current_blogID,$viewer_userID,$comment_content);
    }

}

if (isset($_POST['delete_comment'])){
    $commentID = $_GET['commentID'];

    delete_blog_comment($commentID);

}

?>

<!--TITLE, AUTHOR and DATE-->

<section class="jumbotron " id="jumbotron-user-blogs">
    <div class="container-fluid">

        <div class="row">

            <div class="col-md-4">
                <?php include("user_navbar.php"); ?>
            </div>
            <div class="col-md-5 text-center title">


                <h1><?php echo "{$blog_title}" ?></h1>
                <br>

            </div>
            <div class="col-md-3">
                <h3 style="color: white"><?php echo $author_output;?></h3>
                <br>
                <h4 style="color: white" ><?php echo $formatted_datetime ?></h4>

            </div>
        </div>


    </div>
</section>



<!--    BLOG CONTENT -->

    <div class="container">
        <div class="row" >
            <div class="col-md-1">
            </div>
            <div class="col-md-10" id="card-content">


                <article style="white-space:inherit;">
                    <p><?php echo $blog_content; ?></p>

                </article>


            </div>
            <div class="col-md-1">

            </div>


        </div>



    </div>




<?php
$blog_comment_count = count_blog_comments($current_blogID);
?>

<br>

<!--ADD A COMMENT-->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-1">

        </div>
        <div class="col-md-10">
            <form action="user_blog.php?title=<?php echo $blog_title; ?>&id=<?php echo $visited_userID;?>" method="post">
                <textarea style="width:100%;"  name="blog_comment" placeholder="Write your comment here..."></textarea><br />

                <button type="submit" class="btn btn-primary pull-right">Add a comment</button>

            </form>
        </div>
        <div class="col-md-1">

        </div>
    </div>
</div>

<br>
<!--COMMENTS-->
<div class="container-fluid" >
    <div class="row ">
        <div class="col-md-3">
            <h3><?php echo $blog_comment_count; if($blog_comment_count ==1){echo " Comment:";}else{ echo " Comments:";} ?></h3>
        </div>
    </div>
    <br>
    <div class="row">

        <?php $blog_comment_results = find_blog_comments($current_blogID);

        if (mysqli_num_rows($blog_comment_results)<1) {
            echo ("<p style='font-style: italic; padding-left: 10px'>  No comments yet. Be the first comment on this blog. </p>");
        }

        while($blog_comments = mysqli_fetch_assoc($blog_comment_results)) {
            $commenter_userID = $blog_comments['CommenterUserID'];
            $commenter_full_name = find_full_name($commenter_userID);
            $commenter_first_name= $commenter_full_name["FirstName"];
            $commenter_last_name = $commenter_full_name["LastName"];

            $comment_author = "{$commenter_first_name} {$commenter_last_name}";

            $comment_date = $blog_comments['DatePosted'];
            //formatted for display
            $comment_date_formatted = display_formatted_date($comment_date);


            $comment_content = $blog_comments['Content'];

            $commentID  = $blog_comments['BlogCommentID'];

            ?>

            <div class="container-fluid">

                <div class="row polaroid-circle-messages">

                    <div class="col-md-10">

                        <br>

                        <?php
                        /*
                       * If statement here to change the hyperlink from the commenter name. It will send users
                       * to their own profile view (profile.php) if they click on their own name; it will send them
                       * to the correct profile if they press another user's name (user_profile.php)
                       */
                        if($commenter_userID == $viewer_userID) {


                            ?>
                            <p><?php echo "<a href='profile.php'>" . $comment_author . "</a>" ?> - <?php echo "{$comment_date_formatted}"; ?></p>
                            <p><?php echo "<h4>{$comment_content}</h4>"; ?></p>

                            <?php
                        }else{
                            ?>
                            <p><?php echo "<a href='user_profile.php?id={$commenter_userID}'>" . $comment_author . "</a>" ?> - <?php echo "{$comment_date_formatted}"; ?></p>
                            <p><?php echo "<h5>{$comment_content}</h5>"; ?></p>

                            <?php
                        }//closing else
                        ?>


                        <br>
                    </div>


                    <?php if($commenter_userID == $viewer_userID) { ?>
                    <div class="col-md-2 pull-right">
                        <form  action="user_blog.php?title=<?php echo $blog_title; ?>&id=<?php echo $visited_userID; ?>&commentID=<?php echo $commentID;?>" method="post">


                            <button type="submit" onclick="return confirm('Are you sure you want to delete this comment?')" name="delete_comment" class="btn btn-danger pull-right"><span class="glyphicon glyphicon-trash"></span>
                            </button>

                        </form>
                    </div>
                <?php }// closing if loop for delete to ensure that user can only delete own comments ?>
                </div>

            </div>
            <br>
            <?php
//bracket to close the while loop
        }
        ?>




    </div>
</div>



<?php include("../includes/footer.php"); ?>
