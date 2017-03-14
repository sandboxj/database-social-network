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

<h2><?php echo "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Friends" ?></h2>
<?php include("user_navbar.php"); ?><br />
<?php
    $accepted = find_accepted($visited_user["UserID"]);
    if (mysqli_num_rows($accepted)<1) {
        echo ("<p style='font-style: italic'>No friends to show. </p>");
    } else {
    echo "<p style='font-style: italic'>" . $visited_user["FirstName"] . " currently has " . mysqli_num_rows($accepted) . " friends.</p>";
    while ($a_friend = mysqli_fetch_assoc($accepted)) {
            $pic_result = find_profile_pic($a_friend["UserID"]);
            $profile_picture = mysqli_fetch_assoc($pic_result);
            $profile_picture_src = file_exists("img/Profilepictures" . $a_friend["UserID"] . "/" . $profile_picture["FileSource"]) ? "img/Profilepictures" . $a_friend["UserID"] . "/" . $profile_picture["FileSource"] : "img/" . $profile_picture["FileSource"];
            $uncached_src = $profile_picture_src . "?" . filemtime($profile_picture_src);
            mysqli_free_result($pic_result);
    ?>
    <!--Special case where visitors profile is displayed-->
    <?php if($a_friend['UserID']===$_SESSION["UserID"]) { ?>
        <div class="row polaroid">
            <div class="col-md-3">
                <a href="profile.php"><img src="<?php echo $uncached_src ?>" class="img-responsive" alt="Friend's profile picture'"></a>
            </div>
            <div class="col-md-9">
                <a href="profile.php?>"><h4><?php echo $a_friend["FirstName"] . " " . $a_friend["LastName"]?></h4><br /><br /></a>
            </div>
        </div>
    <!--Case for all other users-->
    <?php } else { ?>
        <div class="row polaroid">
            <div class="col-md-3">
                <a href="user_profile.php?id=<?php echo $a_friend['UserID']?>"><img src="<?php echo $uncached_src ?>" class="img-responsive" alt="Friend's profile picture'"></a>
            </div>
            <div class="col-md-9">
                <a href="user_profile.php?id=<?php echo $a_friend['UserID']?>"><h4><?php echo $a_friend["FirstName"] . " " . $a_friend["LastName"]?></h4><br /><br /></a>
                <?php 
                $exist_relation = find_friendship($_SESSION["UserID"], $a_friend["UserID"]);
                // If relation exists, accept or unfriend
                if (mysqli_num_rows($exist_relation)>0) {
                    $friendship = mysqli_fetch_assoc($exist_relation);
                    if ($friendship["Status"]) {
                ?>
                    <form method="post" style="display: inline">
                        <button type="submit" name="decline_friend" value="<?php echo $friendship["FriendshipID"] ?>" class="btn">Unfriend</button>
                    </form>
                <?php
                    } else {
                        // If friend request is sent to you, option to accept
                        if ($friendship["User2ID"]===$_SESSION["UserID"]) {
                ?>
                            <form method="post" style="display: inline">
                                <button type="submit" name="add_friend" value="<?php echo $friendship["FriendshipID"] ?>" class="btn btn-primary">Accept request</button>
                            </form>
                <?php
                        } else {
                ?>
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
                        <button type="submit" name="add_request" value="<?php echo $visited_user['UserID'] ?>" class="btn btn-primary">Add friend</button>
                    </form>
                <?php    
                }
                ?>
            </div>
        </div>
<?php
            }
        }
    }
?>
<hr />
<a href="logout.php">Logout</a>

<?php include("../includes/footer.php"); ?>
