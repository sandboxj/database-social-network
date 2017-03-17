<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/functions_photos.php");?>
<?php require_once("../server/functions_friends.php");?>
<?php require_once("../server/functions_circle.php");?>
<?php require_once("../server/functions_user.php");?>
<?php require("../server/functions_blog.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/validation_search.php");?>
<?php require_once("../server/validation_friends.php");?>
<?php $page_title="Search"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

<?php echo message()?>


<?php
if (isset($_POST["search_result"]) && $result) {
?>



<section class="jumbotron jumbotron-search">
    <div class="container">
        <div class="row text-center">

        </div>

    </div>
</section>

<h1>User results:</h1>
<?php
$my_friends = find_accepted($_SESSION["UserID"]);
$friends = [];
while ($my_friend = mysqli_fetch_assoc($my_friends)) {
    array_push($friends, $my_friend["UserID"]);       
}
mysqli_free_result($my_friends);
$friends_of_friends = [];
foreach($friends as $friend) {
    $friends_of_friends1 = "SELECT * FROM friendship f
                            WHERE f.User1ID = '{$friend}'
                            AND f.User2ID NOT LIKE '{$_SESSION["UserID"]}'
                            AND f.Status = '1'";
    $friend_friendship1 = mysqli_query($conn, $friends_of_friends1);
    $friends_of_friends2 = "SELECT * FROM friendship f
                            WHERE f.User2ID = '{$friend}'
                            AND f.User1ID NOT LIKE '{$_SESSION["UserID"]}'
                            AND f.Status = '1'";
    $friend_friendship2 = mysqli_query($conn, $friends_of_friends2);
    while ($friend_f1 = mysqli_fetch_assoc($friend_friendship1)) {
        $friend_query1 = "select * from user u
                          where u.UserID = '{$friend_f1["User2ID"]}'";
        $friend_u1 = mysqli_query($conn, $friend_query1);
        $friend_user1 = mysqli_fetch_assoc($friend_u1);
        if (!in_array($friend_user1["UserID"], $friends)) {
            if (array_key_exists($friend_user1["UserID"] , $friends_of_friends)) {
                $friends_of_friends[$friend_user1["UserID"]] = $friends_of_friends[$friend_user1["UserID"]] + 1;
            } else {
                $friends_of_friends[$friend_user1["UserID"]] = 1;
            }
        }
    }
    while ($friend_f2 = mysqli_fetch_assoc($friend_friendship2)) {
        $friend_query2 = "select * from user u
                          where u.UserID = '{$friend_f2["User1ID"]}'";
        $friend_u2 = mysqli_query($conn, $friend_query2);
        $friend_user2 = mysqli_fetch_assoc($friend_u2);
        if (!in_array($friend_user2["UserID"], $friends)) {
            if (array_key_exists($friend_user2["UserID"] , $friends_of_friends)) {
                $friends_of_friends[$friend_user2["UserID"]] = $friends_of_friends[$friend_user2["UserID"]] + 1;
            } else {
                $friends_of_friends[$friend_user2["UserID"]] = 1;
            }
        }
    }
}
arsort($friends_of_friends);
$self_query = "SELECT * FROM user u
               WHERE u.UserID = '{$_SESSION["UserID"]}'";
$self_result = mysqli_query($conn, $self_query);
$self = mysqli_fetch_assoc($self_result);
$self_interest = $self["Interest"];
$self_location = $self["CurrentLocation"];
if ($self_location == "x") {
    $self_location = "null";
}

if (mysqli_num_rows($result)<1) {
    echo ("<p style='font-style: italic'>No matches.</p>");
} else {
    while ($search_friend = mysqli_fetch_assoc($result)) {
        $friendshipz = find_friendship($_SESSION["UserID"], $search_friend["UserID"]);
        $friendship_details = mysqli_fetch_assoc($friendshipz);
        $isfriend = ($friendship_details["User1ID"] == "") ? 0 : 1;
        if ($isfriend || $search_friend["PrivacySetting"] == "2") {
            $pic_result = find_profile_pic($search_friend["UserID"]);
            $profile_picture = mysqli_fetch_assoc($pic_result);
            $profile_picture_src = file_exists("img/Profilepictures" . $search_friend["UserID"] . "/" . $profile_picture["FileSource"]) ? "img/Profilepictures" . $search_friend["UserID"] . "/" . $profile_picture["FileSource"] : "img/" . $profile_picture["FileSource"];
            $uncached_src = $profile_picture_src . "?" . filemtime($profile_picture_src);
            mysqli_free_result($pic_result);
            $share_interest = 0;
            if ($search_friend["Interest"] == $self_interest) {
                $share_interest = 1;
            }
            $mutual_friend_count = 0;
            if (array_key_exists($search_friend["UserID"], $friends_of_friends)) {
                $mutual_friend_count = $friends_of_friends[$search_friend["UserID"]];
            }
            ?>
	    <div class="row polaroid">
  		<div class="col-md-3">
    		     <a href="user_profile.php?id=<?php echo $search_friend['UserID']?>"><img src="<?php echo $uncached_src ?>" class="img-responsive" alt="Friend's profile picture'"></a>
  		</div>
  	    <div class="col-md-9">
    		    <a href="user_profile.php?id=<?php echo $search_friend['UserID']?>"><h4><?php echo $search_friend["FirstName"] . " " . $search_friend["LastName"]?></h4></a>
    		    <br />
                    Location: <?php echo $search_friend["CurrentLocation"]?>
          <br/>
                    <?php if ($share_interest) {
                              echo $search_friend["Interest"] . " is a shared interest. <br/>";
                          }?>
                    <br/>
                                <?php
                $exist_relation = find_friendship($_SESSION["UserID"], $search_friend["UserID"]);
                // If relation exists, accept or unfriend
                if (mysqli_num_rows($exist_relation)>0) {
                    $friendshipzz = mysqli_fetch_assoc($exist_relation);
                    if ($friendshipzz["Status"]) {
                        ?>
          <br/>
                        <form method="post" style="display: inline">
                            <button type="submit" name="decline_friend" value="<?php echo $friendshipzz["FriendshipID"] ?>" class="btn btn-default">Unfriend</button>
                        </form>
                        <?php
                    } else {
                        // If friend request is sent to you, option to accept
                        if ($friendshipzz["User2ID"]===$_SESSION["UserID"]) {
                            ?>
          
                    You have <?php echo $mutual_friend_count?> mutual friends.
                    <br /><br/>
                            <form method="post" style="display: inline">
                                <button type="submit" name="add_friend" value="<?php echo $friendshipzz["FriendshipID"] ?>" class="btn btn-primary">Accept request</button>
                            </form>
                            <?php
                        } else {
                            ?>
          
                    You have <?php echo $mutual_friend_count?> mutual friends.
                    <br /><br/>
                            <form method="post" style="display: inline">
                                <button type="submit" name="decline_friend" value="<?php echo $friendshipzz["FriendshipID"] ?>" class="btn btn-default">Pending / Cancel request</button>
                            </form>
                            <?php
                        }
                    }
                }
                // If no relation exists, option to add friend
                else {
                    ?>
          
                    You have <?php echo $mutual_friend_count?> mutual friends.
                    <br /><br/>
                    <form method="post" style="display: inline">
                        <button type="submit" name="add_request" value="<?php echo $search_friend['UserID'] ?>" class="btn btn-primary">Add friend</button>
                    </form>
                    <?php
                }
                ?>
  	    	</div>
	    </div>
	<?php
        } elseif ($search_friend["PrivacySetting"] == "1") {
            $friends_of_searched_result = find_accepted($search_friend["UserID"]);
            while ($f_of_s = mysqli_fetch_assoc($friends_of_searched_result)) {
                $fs_of_searched[] = $f_of_s["UserID"];
            }
            $friends_result = find_accepted($_SESSION["UserID"]);
            while ($f = mysqli_fetch_assoc($friends_result)) {
                $fs[] = $f["UserID"];
            }
            $is_friend_of_friend = 0;
            foreach($fs_of_searched as $f) {
                if (in_array($f , $fs)) {
                    $is_friend_of_friend = 1;
                }
            }
            if ($is_friend_of_friend) {
                $pic_result = find_profile_pic($search_friend["UserID"]);
                $profile_picture = mysqli_fetch_assoc($pic_result);
                $profile_picture_src = file_exists("img/Profilepictures" . $search_friend["UserID"] . "/" . $profile_picture["FileSource"]) ? "img/Profilepictures" . $search_friend["UserID"] . "/" . $profile_picture["FileSource"] : "img/" . $profile_picture["FileSource"];
                $uncached_src = $profile_picture_src . "?" . filemtime($profile_picture_src);
                mysqli_free_result($pic_result);
                ?>
	    <div class="row polaroid">
  		<div class="col-md-3">
    		    <a href="user_profile.php?id=<?php echo $search_friend['UserID']?>"><img src="<?php echo $uncached_src ?>" class="img-responsive" alt="Friend's profile picture'"></a>
  		</div>
  		<div class="col-md-9">
    		    <a href="user_profile.php?id=<?php echo $search_friend['UserID']?>"><h4><?php echo $search_friend["FirstName"] . " " . $search_friend["LastName"]?></h4></a>
    		    <br /><br />
                    Location: <?php echo $search_friend["CurrentLocation"]?>
                    <br />
                    You have <?php echo $mutual_friend_count?> mutual friends.
                    <br />
                    <?php if ($share_interest) {
                              echo $search_friend["Interest"] . " is a shared interest. <br/>";
                          }?>
                    <br/>
                <?php
                $exist_relation = find_friendship($_SESSION["UserID"], $search_friend["UserID"]);
                // If relation exists, accept or unfriend
                if (mysqli_num_rows($exist_relation)>0) {
                    $friendshipzz = mysqli_fetch_assoc($exist_relation);
                    if ($friendshipzz["Status"]) {
                        ?>
                        <form method="post" style="display: inline">
                            <button type="submit" name="decline_friend" value="<?php echo $friendshipzz["FriendshipID"] ?>" class="btn btn-default">Unfriend</button>
                        </form>
                        <?php
                    } else {
                        // If friend request is sent to you, option to accept
                        if ($friendshipzz["User2ID"]===$_SESSION["UserID"]) {
                            ?>
                            <form method="post" style="display: inline">
                                <button type="submit" name="add_friend" value="<?php echo $friendshipzz["FriendshipID"] ?>" class="btn btn-primary">Accept request</button>
                            </form>
                            <?php
                        } else {
                            ?>
                            <form method="post" style="display: inline">
                                <button type="submit" name="decline_friend" value="<?php echo $friendshipzz["FriendshipID"] ?>" class="btn btn-default">Pending / Cancel request</button>
                            </form>
                            <?php
                        }
                    }
                }
                // If no relation exists, option to add friend
                else {
                    ?>
                    <form method="post" style="display: inline">
                        <button type="submit" name="add_request" value="<?php echo $search_friend['UserID'] ?>" class="btn btn-primary">Add friend</button>
                    </form>
                    <?php
                }
                ?>
  		</div>
	    </div>
	    <?php

           






<h1 class="lobster-title">People you may know </h1>

<?php include("recommendations.php")?>


<?php include("../includes/footer.php"); ?>
