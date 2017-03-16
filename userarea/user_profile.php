<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/functions_photos.php");?>
<?php require_once("../server/functions_friends.php");?>
<?php require_once("../server/validation_friends.php");?>
<?php $visited_user = find_user_by_id($_GET["id"]); ?>
<?php $page_title= "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Profile"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

<?php
$collection_id = "Profilepictures" . $visited_user['UserID'];
$pic_result = find_profile_pic($visited_user["UserID"]);
$profile_picture = mysqli_fetch_assoc($pic_result);
$profile_picture_src = file_exists("img/" . $collection_id . "/" . $profile_picture["FileSource"]) ? "img/" . $collection_id . "/" . $profile_picture["FileSource"] : "img/" . $profile_picture["FileSource"];
$uncached_src = $profile_picture_src . "?" . filemtime($profile_picture_src);
mysqli_free_result($pic_result);
?>
<section class="jumbotron jumbotron-profile" style="height: 450px;">
    <div class="container">
        <div class="row">
            <div class="col-md-3 content">
                <br>
                <?php include("user_navbar.php"); ?>
                <br>
                <br>
                <?php
                $exist_relation = find_friendship($_SESSION["UserID"], $visited_user["UserID"]);
                // If relation exists, accept or unfriend
                if (mysqli_num_rows($exist_relation)>0) {
                    $friendship = mysqli_fetch_assoc($exist_relation);
                    if ($friendship["Status"]) {
                        ?>
                        <form method="post" style="display: inline">
                            <button type="submit" name="decline_friend" value="<?php echo $friendship["FriendshipID"] ?>" class="btn btn-default btn-block">Unfriend</button>
                        </form>
                        <?php
                    } else {
                        // If friend request is sent to you, option to accept
                        if ($friendship["User2ID"]===$_SESSION["UserID"]) {
                            ?>
                            <form method="post" style="display: inline">
                                <button type="submit" name="add_friend" value="<?php echo $friendship["FriendshipID"] ?>" class="btn btn-primary btn-block">Accept request</button>
                            </form>
                            <?php
                        } else {
                            ?>
                            <form method="post" style="display: inline">
                                <button type="submit" name="decline_friend" value="<?php echo $friendship["FriendshipID"] ?>" class="btn btn-block">Pending / Cancel request</button>
                            </form>
                            <?php
                        }
                    }
                }
                // If no relation exists, option to add friend
                else {
                    ?>
                    <form method="post" style="display: inline">
                        <button type="submit" name="add_request" value="<?php echo $visited_user['UserID'] ?>" class="btn btn-primary btn-block">Add friend</button>
                    </form>
                    <?php
                }
                ?>

            </div>
            <div class="col-md-1">


            </div>
            <div class="col-md-4 text-center">

                <div class="container">
                <img src="<?php echo $uncached_src ?>" class="img-responsive img-circle" alt="Profile picture">
                </div>
                <?php echo message();?>
                <h2><?php echo "{$visited_user["FirstName"]} {$visited_user["LastName"]}";?></h2>
                <br><br>

            </div>
            <div class="col-md-1">


            </div>
            <div class="col-md-3">
                <?php $found_user = find_user_by_email($_SESSION["Email"]); ?>
                <!--Edits dont do anything yet-->
                <h2>Profile Details:</h2>
                <br>
                Date of Birth: <?php echo $visited_user["DateOfBirth"]?> <br /> <br />
                Location: <?php echo $visited_user["CurrentLocation"]?><br/><br/>
                Email: <?php echo $visited_user["Email"]?><br/><br/>
                Phone Number: <?php echo $visited_user["PhoneNumber"]?><br/><br/>
                Interest: <?php echo $visited_user["Interest"]?><br/><br/>
                <br>

            </div>

        </div>
    </div>
    </div>
</section>




   <br />






<?php include("../includes/footer.php"); ?>
