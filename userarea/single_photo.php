<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/functions_photos.php");?>
<?php require_once("../server/db_connection.php");?>
<?php confirm_logged_in(); ?>
<?php $collection_details = find_collection($_GET["collection"]);?>
<?php $visited_user = (isset($_GET["id"])) ? find_user_by_id($_GET["id"]) : null; ?>
<?php $is_owner = $_SESSION["UserID"]===$collection_details["UserID"]; ?>
<?php $page_title = ($is_owner) ? "Photos" : "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Photos"?>
<?php require_once("../server/validation_photo_comment.php");?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>
        
        <h2>Photos</h2>
        <div>
                <?php 
                        if(!$is_owner) { 
                                include("user_navbar.php");
                ?>
                <a href="user_photos.php?id=<?php echo $_GET["id"]; ?>&collection=<?php echo $_GET["collection"];?>">Back to Collection</a>
                <?php } else { ?>
                <a href="photos.php?collection=<?php echo $_GET["collection"];?>">Back to Collection</a>
                <?php } ?>
        </div>
        <div class="container">
                <div class="span12">
                        <figure>
                        <img src="img/<?php echo ($_GET["collection"] . "/" . $_GET["FileSource"]) ?>" alt="Collection Picture" class="center-block img-responsive">
                        <figcaption style="text-align: center; background-color: white;">
                        <?php 
                                $caption = isset($_GET["Caption"]) ? trim($_GET["Caption"]) : "";
                                echo $caption;
                        ?>
                        </figcaption>
                        </figure>
                </div>
        </div>
        <hr />
        <div class="container">
                <h5 style="font-weight: bold">Comments</h5>
                <form method="post">
                        <textarea class="form-control" name="comment" style="width: 50%"></textarea>
                        <?php echo message()?>
                        <input class="btn" type="submit" name="post_comment" value="Post comment" />
                </form><br />
                <?php if($is_owner) { ?>
                <a onclick="$('.col-md-1').toggleClass('hidden');" style="cursor: pointer">Delete comments</a>
                <?php } ?>
                <!--<button class="btn" onclick="$('#edit_profile_pic').toggleClass();">Delete comments</button>-->
                <?php
                        $photo_comments = find_photo_comments($_GET["PhotoID"]);
                if (mysqli_num_rows($photo_comments)<1) {
                    echo ("<p style='font-style: italic'>No comments yet. Be the first comment on this photo. </p>");
                }
                while ($comment = mysqli_fetch_assoc($photo_comments)) {
                ?>
                        <div style="border: 1px solid grey">                               
                                <div class="row">
                                        <?php
                                                if(!$is_owner) {
                                                        $visitor_rights = ($comment["CommenterUserID"]===$_SESSION["UserID"]) ? "" : "hidden";
                                                } else {
                                                        $visitor_rights = "hidden";
                                                }
                                        ?>                                    
                                        <div class="col-md-1 <?php echo $visitor_rights ?>">
                                        <form method="post">
                                                <button type="submit" name="delete_comment" value="<?php echo $comment['PhotoCommentID']?>">
                                                        <span class="glyphicon glyphicon-trash">
                                                </button>
                                        </form>
                                        </div>
                                        <div class="col-md-9">
                                        Author: <?php echo $comment["CommenterUserID"] ?>
                                        </div>
                                        <div class="col-md-2" style="font-style: italic">
                                        <?php echo $comment["DatePosted"] ?>
                                        </div>
                                </div>
                                <div class="row">
                                        <div class="col-md-12">
                                        <?php echo nl2br($comment["Content"]); ?>
                                        </div>
                                </div>
                        </div>
                                
                <?php
                }
                ?>
        </div>


<?php include("../includes/footer.php"); ?>
