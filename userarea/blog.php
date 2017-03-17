<?php require_once("../server/sessions.php"); ?>
<?php require("../server/functions_blog.php"); ?>
<?php require_once("../server/functions.php"); ?>
<?php require_once("../server/db_connection.php"); ?>
<?php require_once("../server/functions_user.php"); ?>
<?php $page_title = "Blogs" ?>
<?php require_once("../server/db_connection.php");?>
<?php confirm_logged_in(); ?>


<?php

//currently connected user
$userid = $_SESSION["UserID"];


$blog_title = $_GET['title'];
$current_blogID = find_blog_id($userid, $blog_title);


//packaged the old queries into one
$blog_details = find_blog_details($current_blogID);

$blog_content = $blog_details["blog_content"];
//This avoids any \n\r type of characters in the output
$trimmed_blog_title = $blog_details["blog_title"];
$date_posted = $blog_details["blog_date"];
$formatted_datetime = display_formatted_date($date_posted);


$blog_access_rights=find_blog_access_rights($current_blogID);




/*
 * If the delete blog button is pressed and following confirmation.
 * Deletes the post and redirects user to the blogs view.
 * Note: this involves redirection and should be positioned above the header
 */
if (isset($_POST["delete_blog"])) {


    delete_blog_post($current_blogID);
    redirect_to("../userarea/blogs.php");
}


/*
 * If the delete comment button is pressed and following confirmation.
 * Deletes the comment.
 *
 */
if (isset($_POST['delete_comment'])){
    $commentID = $_GET['commentID'];

    delete_blog_comment($commentID);

}

/*
 * When the save changes button is pressed, the script below returns the updated
 * blog content and the current blog ID and the content is updated in the database.
 */
if (isset($_POST['updated_blog_content']) && isset($_POST['blogID'])) {

    $updated_content = $_POST['updated_blog_content'];
    $current_blogID = $_POST['blogID'];


    update_blog_content($current_blogID, $updated_content);

}


/*
 * Triggered when the access rights for a blog are change. This is then updated at the
 * level of the database.
 */
if(isset($_GET['access'])){
    $updated_access_rights = $_GET['access'];

    update_blog_access_rights($current_blogID, $updated_access_rights);
    redirect_to("blog.php?title={$blog_title}");
}


/*
 * Triggered when the post comment button is pressed. It validates the comment input and
 * inserts the comment in the database
 */
if (isset($_POST["blog_comment"])){
    $comment_content = $_POST["blog_comment"];

    $empty_check = validate_comment_input($comment_content);
    if($empty_check == false){

        $comment_content = strip_tags($comment_content);
        $comment_content = htmlentities($comment_content);
        insert_comment($current_blogID,$userid,$comment_content);
    }

}

?>

<?php $page_title="{$trimmed_blog_title}"?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>


<section class="jumbotron jumbotron-blog">
    <div class="container-fluid">

        <div class="row">

            <div class="col-lg-4">

            </div>
            <div class="col-lg-4 text-center title">


                    <h1><?php echo "{$trimmed_blog_title}" ?></h1></p>
                    <br>
                    <h4 ><?php echo $formatted_datetime ?></h4>
                </div>
            </div>




            <div class="col-lg-4 pull-right">

                <div class="btn-toolbar pull-right" role="toolbar" aria-label="blog_options">
                    <div class="btn-group-vertical" aria-label="blog_options">

                        <form action="blog.php?title=<?php echo $blog_title; ?>&blogID=<?php echo $current_blogID; ?>" method="post">


                            <button type="submit" onclick="return confirm('Are you sure you want to delete this blog?')" name="delete_blog" class="btn btn-danger "><span class="glyphicon glyphicon-trash"></span> Delete Post
                            </button>

                        </form>

                        <!--DELETE AND ACCESS RIGHTS-->
                        <div class="dropdown">
                            <button  type ="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <span class="glyphicon glyphicon-cog"></span> <?php echo $blog_access_rights;?>
                                <span class="caret"></span></button>



                            <ul class="dropdown-menu" name="access">


                                <li for="presentation"><a href="blog.php?title=<?php echo $blog_title ?>&access=Only me">Only me</a></li>
                                <li for="presentation"><a href="blog.php?title=<?php echo $blog_title ?>&access=Friends">Friends</a></li>
                                <li for="presentation"><a href="blog.php?title=<?php echo $blog_title ?>&access=Everybody">Everybody</a></li>
                                <li for="presentation"><a href="blog.php?title=<?php echo $blog_title ?>&access=Circles">Circles</a></li>
                                <li for="presentation"><a href="blog.php?title=<?php echo $blog_title ?>&access=Friends of friends">Friends of friends</a></li>
                            </ul>

                        </div>



                    </div>
                </div>


            </div>

            </div>

        </div>

    </div>
</section>
<!--THIS PAGE IS FOR AN INDIVIDUAL BLOG POST-->
<!--TITLE, AUTHOR and DATE-->


<!--    BLOG CONTENT-->



<div class="container">
    <div class="row" >
        <div class="col-md-1">
        </div>
        <div class="col-md-10" id="card-content">


                <article style="white-space:inherit;"    contenteditable="true" >
                    <p><?php echo nl2br(ltrim($blog_content)); ?></p>

                </article>


        </div>
        <div class="col-md-1">

        </div>


    </div>
    <br>

    <div class="row">
        <div class="col-md-3 pull-right">
        <button  class="btn btn-primary " onClick="return alert('Your changes were saved')" id="save_changes"><span class="glyphicon glyphicon-pencil"></span> Save Changes
        </button>
        </div>
    </div>

</div>
</div>

<br>
<br>


<?php
$blog_comment_count = count_blog_comments($current_blogID);
?>

<!--ADD A COMMENT-->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-1">

        </div>
        <div class="col-md-10">
            <form action="blog.php?title=<?php echo $blog_title?>" method="post">
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
               if($commenter_userID == $userid) {


                   ?>
                   <p><?php echo "<a href='profile.php'>" . $comment_author . "</a>" ?> - <?php echo "{$comment_date_formatted}"; ?></p>
                   <p></p><?php echo "<h4>{$comment_content}</h4>"; ?></p>

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

           <div class="col-md-2 pull-right">
               <form  action="blog.php?title=<?php echo $blog_title; ?>&blogID=<?php echo $current_blogID; ?>
            &commentID=<?php echo $commentID ?>" method="post">


                   <button type="submit" onclick="return confirm('Are you sure you want to delete this comment?')" name="delete_comment" class="btn btn-danger pull-right"><span class="glyphicon glyphicon-trash"></span>
                   </button>

               </form>
           </div>
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





    <br/><br/>


<!--DO NOT FORGET TO RELEASE THE COMMENT RESULTS-->
<?php echo message();
mysqli_free_result($blog_comment_results);
?>


<!--SCRIPT FOR THE SAVE CHANGES FUNCTION; INCLUDED AT THE END TO MAKE SURE THAT TEH AFFECTED ELEMENT IS RENDERED-->
<script type="text/javascript">
    $('#save_changes').click(function(){
        var blog_content = $('.blog-content').html();

        var blogID = "<?php echo $current_blogID ?>";
        console.log("ID: "+blogID);
        console.log(blog_content);

        $.ajax({
            type: 'POST',
            url:  '../userarea/blog.php?title=<?php echo $blog_title; ?>',
            data: 'updated_blog_content=' +blog_content+ '&blogID='+blogID
        });


    });
</script>

<!--end of body-->
<?php include("../includes/footer.php"); ?>



