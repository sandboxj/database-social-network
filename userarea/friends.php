<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/functions_photos.php");?>
<?php require_once("../server/functions_friends.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/validation_friends.php");?>
<?php $page_title="{$_SESSION["FirstName"]} {$_SESSION["LastName"]}'s Friends"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

<h2>Your Friends</h2>
<?php 
    // Display pending requests if exist
    $pending = find_pending($_SESSION["UserID"]);
    if (mysqli_num_rows($pending)>0) { 
?>
        <h5>Pending Friend Requests</h5>
        <div class="container">
    <?php
        while ($p_friend = mysqli_fetch_assoc($pending)) {
            $pic_result = find_profile_pic($p_friend["UserID"]);
            $profile_picture = mysqli_fetch_assoc($pic_result);
            $profile_picture_src = file_exists("img/Profilepictures" . $p_friend["UserID"] . "/" . $profile_picture["FileSource"]) ? "img/Profilepictures" . $p_friend["UserID"] . "/" . $profile_picture["FileSource"] : "img/" . $profile_picture["FileSource"];
            $uncached_src = $profile_picture_src . "?" . filemtime($profile_picture_src);
            mysqli_free_result($pic_result);
    ?>
        <div class="row polaroid">
            <div class="col-md-3">
                <a href="user_profile.php?id=<?php echo $p_friend['UserID']?>"><img src="<?php echo $uncached_src ?>" class="img-responsive" alt="Friend's profile picture'"></a>
            </div>
            <div class="col-md-9">
                <a href="user_profile.php?id=<?php echo $p_friend['UserID']?>"><h4><?php echo $p_friend["FirstName"] . " " . $p_friend["LastName"]?></h4></a><br /><br />
                <form method="post">
                    <button class="btn btn-primary" type="submit" name="add_friend" value="<?php echo $p_friend['FriendshipID']?>">
                        Accept
                    </button>
                    <button class="btn" type="submit" name="decline_friend" value="<?php echo $p_friend['FriendshipID']?>">
                        Decline
                    </button>
                </form>
            </div>
        </div>
    <?php
        }
    ?>
    </div><hr/>
<?php
    }
    mysqli_free_result($pending);
?>
<?php
    echo message();
    $accepted = find_accepted($_SESSION["UserID"]);
    if (mysqli_num_rows($accepted)<1) {
        echo ("<p style='font-style: italic'>You currently have 0 friends. </p>");
    } else {
    echo "<p style='font-style: italic'>You currently have " . mysqli_num_rows($accepted) . " friends.</p>";
    while ($a_friend = mysqli_fetch_assoc($accepted)) {
            $pic_result = find_profile_pic($a_friend["UserID"]);
            $profile_picture = mysqli_fetch_assoc($pic_result);
            $profile_picture_src = file_exists("img/Profilepictures" . $a_friend["UserID"] . "/" . $profile_picture["FileSource"]) ? "img/Profilepictures" . $a_friend["UserID"] . "/" . $profile_picture["FileSource"] : "img/" . $profile_picture["FileSource"];
            $uncached_src = $profile_picture_src . "?" . filemtime($profile_picture_src);
            mysqli_free_result($pic_result);
    ?>
        <div class="row polaroid">
            <div class="col-md-3">
                <a href="user_profile.php?id=<?php echo $a_friend['UserID']?>"><img src="<?php echo $uncached_src ?>" class="img-responsive" alt="Friend's profile picture'"></a>
            </div>
            <div class="col-md-9">
                <a href="user_profile.php?id=<?php echo $a_friend['UserID']?>"><h4><?php echo $a_friend["FirstName"] . " " . $a_friend["LastName"]?></h4><br /><br /></a>
            </div>
        </div>
<?php
        }
    }
?>
<hr />
<a href="logout.php">Logout</a>

<?php include("../includes/footer.php"); ?>
