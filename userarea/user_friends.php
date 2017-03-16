<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/functions_photos.php");?>
<?php require_once("../server/functions_friends.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/validation_friends.php");?>
<?php $visited_user = find_user_by_id($_GET["id"]); ?>
<?php $page_title= "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Friends"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

<section class="jumbotron jumbotron-friends">

    <div class="container">
        <div class="row text-center">
            <div class="col-md-4">
                <?php include("user_navbar.php"); ?>
            </div>
            <div class="col-md-5">
                <h1> <?php echo "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Friends" ?> </h1>
                <?php echo message()?>
            </div>
        </div>
    </div>
</section>

<?php
echo message();
$visited_userID = $_GET['id'];
$accepted_friends = find_accepted($_GET["id"]);


?>

<div class="container">
    <div class="row text-center">
        <?php
        $friend_count = count_friends_output($accepted_friends);
        echo $friend_count;

        ?>
    </div>

    <div class="row">


        <?php


        while ($a_friend = mysqli_fetch_assoc($accepted_friends)) {
            $pic_result = find_profile_pic($a_friend["UserID"]);
            $profile_picture = mysqli_fetch_assoc($pic_result);
            $profile_picture_src = file_exists("img/Profilepictures" . $a_friend["UserID"] . "/" . $profile_picture["FileSource"]) ? "img/Profilepictures" . $a_friend["UserID"] . "/" . $profile_picture["FileSource"] : "img/" . $profile_picture["FileSource"];
            $uncached_src = $profile_picture_src . "?" . filemtime($profile_picture_src);

            ?>
            <!--Special case where visitors profile is displayed-->
            <?php if($a_friend['UserID']===$_SESSION["UserID"]) { ?>

                <a href="profile.php?>">
                    <div class="col-md-6 polaroid">
                        <div class="col-md-7">
                            <img src="<?php echo $uncached_src ?>" class="img-responsive" alt="Friend's profile picture'">
                        </div>
                        <div class="col-md-5">
                            <h4><?php echo $a_friend["FirstName"] . " " . $a_friend["LastName"]?></h4><br /><br />
                        </div>
                    </div>
                </a>
                <!--Case for all other users-->
            <?php } else { ?>
                <a href="user_profile.php?id=<?php echo $a_friend['UserID'];?>">
                <div class="col-md-6 polaroid">
                    <div class="col-md-7">
                        <img src="<?php echo $uncached_src; ?>" class="img-responsive" alt="Friend's profile picture'">
                    </div>

                    <div class="col-md-5">
                        <h4><?php echo $a_friend["FirstName"] . " " . $a_friend["LastName"];?></h4>
                        <br /><br />
                        <?php
                        $exist_relation = find_friendship($_SESSION["UserID"], $a_friend["UserID"]);
                        $rows =mysqli_num_rows($exist_relation);


                        // If relation exists, accept or unfriend
                        if (mysqli_num_rows($exist_relation)>0) {
                            $friendship = mysqli_fetch_assoc($exist_relation);
                            //if =1 (true)
                            if ($friendship["Status"]) {
                                ?>
                                <h5>You are friends.</h5>
                                <br>
                                <form method="post" style="display: inline">
                                    <button type="submit" name="decline_friend" value="<?php echo $friendship["FriendshipID"] ?>" class="btn">Unfriend</button>
                                </form>
                                <?php
                            } else {
                                // If friend request is sent to you, option to accept
                                if ($friendship["User2ID"]===$_SESSION["UserID"]) {
                                    ?>
                                    <form  method="post" style="display: inline">
                                        <button type="submit" name="add_friend" value="<?php echo $friendship["FriendshipID"] ?>" class="btn btn-primary">Accept request</button>
                                    </form>
                                    <?php
                                } else {
                                    ?>

                                    <br>
                                    <form method="post" style="display: inline">
                                        <button type="submit" name="decline_friend" value="<?php echo $friendship["FriendshipID"] ?>" class="btn">Pending / Cancel request</button>
                                    </form>
                                    <?php
                                }
                            }
                        }
                        // If no relation exists, option to add friend
                        else {
                            ?>
                            <form method="post" style="display: inline">
                                <button type="submit" name="add_request" value="<?php echo $a_friend["UserID"] ?>" class="btn btn-primary">Add friend</button>
                            </form>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                </a>




<?php
            }//close else


        }//close while

?>

    </div>
</div>



 <?php mysqli_free_result($pic_result);
        mysqli_free_result($accepted_friends);?>
<?php include("../includes/footer.php"); ?>
