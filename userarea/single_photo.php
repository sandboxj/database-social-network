<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/functions_photos.php");?>
<?php require_once("../server/functions_user.php");?>
<?php require_once("../server/functions_blog.php");?>
<?php require_once("../server/db_connection.php");?>
<?php confirm_logged_in(); ?>
<?php $collectionID = $_GET["CollectionID"];
$collection_details = find_collection($collectionID);
;?>
<?php $visited_user = (isset($_GET["id"])) ? find_user_by_id($_GET["id"]) : null; ?>
<?php $is_owner = $_SESSION["UserID"]==$collection_details["UserID"]; ?>
<?php $page_title = ($is_owner) ? "Photo" : "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Photo"?>
<?php require_once("../server/validation_photo_comment.php");?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

<?php
$userid =$_SESSION["UserID"];
$photoID = $_GET['PhotoID'];
$collection = $_GET['collection'];
$file_source = $_GET['FileSource'];
$caption = isset($_GET["Caption"]) ? trim($_GET["Caption"]) : "";



if(isset($_GET['id'])){
    $viewer_userID=$_GET['id'];

}

?>
<section class="jumbotron jumbotron-photocollections">

    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 ">
            <?php


            if (!$is_owner){?>
                <a  style="color: white;" href="user_photos.php?collection=<?php echo $collection;?>&id=<?php echo $_GET["id"] ?>">
                <button class="btn btn-primary pull-left"><i class="glyphicon glyphicon-chevron-left"></i> Back to Collection</button>
                </a>
            <?php }else{?>
                <a  style="color: white;" href="photos.php?collection=<?php echo $collection;?>">
                <button class="btn btn-primary pull-left"><i class="glyphicon glyphicon-chevron-left"></i> Back to Collection</button>
                </a>
           <?php }?>


            </div>
            <div class="col-md-4">
                <h1><?php echo $_GET["Caption"] ?></h1>
                <?php echo message()?>
            </div>
            <div class="col-md-4">

            </div>
        </div>
    </div>
</section>

        <div class="container">
                <div class="span12">
                        <figure>
                        <img src="img/<?php echo ($_GET["collection"] . "/" . $_GET["FileSource"]) ?>" alt="Collection Picture" class="center-block img-responsive">

                        </figure>
                </div>
        </div>

<br>
<br>
<br>


<!--ADD A COMMENT-->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-1">

        </div>
        <div class="col-md-10">
            <form action="single_photo.php?CollectionID=<?php echo $collectionID ?>&Caption=<?php echo $caption ?>&PhotoID=<?php echo $photoID;?>&collection=<?php echo $collection;?>&FileSource=<?php echo $file_source; if(isset($_GET['id'])){ echo "&id=".$_GET['id']; }?>" method="post">
                <textarea style="width:100%;"  name="comment" placeholder="Write your comment here..."></textarea><br />
                <button type="submit" name="post-photo-comment" class="btn btn-primary pull-right">Add a comment</button>

            </form>

        </div>
        <div class="col-md-1">

        </div>
    </div>
</div>


<?php
$photo_comment_count = count_photo_comments($photoID);
?>
<!--COMMENTS-->
<div class="container-fluid" >
    <div class="row ">
        <div class="col-md-3">
            <h3><?php echo $photo_comment_count; if($photo_comment_count ==1){echo " Comment:";}else{ echo " Comments:";} ?></h3>
        </div>
    </div>
    <br>
    <div class="row">

        <?php $photo_comment_results = find_photo_comments($photoID);

        if (mysqli_num_rows($photo_comment_results)<1) {
            echo ("<p style='font-style: italic; padding-left: 10px'>  No comments yet. Be the first comment on this blog. </p>");
        }

        while($photo_comments = mysqli_fetch_assoc($photo_comment_results)) {

            $seen_query = "UPDATE photo_comment
                           SET Seen = '1'
                           WHERE PhotoCommentID = '{$photo_comments["PhotoCommentID"]}'";
            $seen = mysqli_query($conn, $seen_query);

            $commenter_userID = $photo_comments['CommenterUserID'];
            $commenter_full_name = find_full_name($commenter_userID);
            $commenter_first_name= $commenter_full_name["FirstName"];
            $commenter_last_name = $commenter_full_name["LastName"];

            $comment_author = "{$commenter_first_name} {$commenter_last_name}";

            $comment_date = $photo_comments['DatePosted'];
            //formatted for display
            $comment_date_formatted = display_formatted_date($comment_date);


            $comment_content = $photo_comments['Content'];

            $commentID  = $photo_comments['PhotoCommentID'];

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
                        <form action="single_photo.php?CollectionID=<?php echo $collectionID ?>&Caption=<?php echo $caption ?>&PhotoID=<?php echo $photoID;?>&collection=<?php echo $collection;?>&FileSource=<?php echo $file_source;?>&commentID=<?php echo $commentID; if(isset($_GET['id'])) echo "&id=".$_GET['id'];?>" method="post">

                        <?php //OWNER CAN DELETE ALL COMMENTS
                        if($is_owner) { ?>
                            <button type="submit"
                                    onclick="return confirm('Are you sure you want to delete this comment?')"
                                    name="delete_photo_comment" class="btn btn-danger pull-right"><i
                                        class="glyphicon glyphicon-trash"></i>
                            </button>
                            <?php
                        }else{
                            //if not the owner
                            if($commenter_userID == $userid){
                                ?>
                            <button type="submit"
                                    onclick="return confirm('Are you sure you want to delete this comment?')"
                                    name="delete_photo_comment" class="btn btn-danger pull-right"><i
                                        class="glyphicon glyphicon-trash"></i>
                            </button>
                          <?php  }
                        }
                            ?>

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
mysqli_free_result($photo_comment_results);
?>






<?php include("../includes/footer.php"); ?>
