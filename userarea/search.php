<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/functions_photos.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/validation_search.php");?>
<?php $page_title="Search"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

<h2>Find Friends</h2>
<?php echo message()?>
<form action="search.php" method="post">
  <input type="text" name="search_query" placeholder="Name..." class="search_form"></input>
  <br />
  <button type="submit" name="search_result" value="Search" class="btn btn-primary">Search</button>
</form>

<?php
if (isset($_POST["search_result"]) && $result) {
?>
<hr />
<h4>Search results</h4>
<?php
if (mysqli_num_rows($result)<1) {
    echo ("<p style='font-style: italic'>No matches.</p>");
} else {
    while ($search_friend = mysqli_fetch_assoc($result)) {
        $pic_result = find_profile_pic($search_friend["UserID"]);
        $profile_picture = mysqli_fetch_assoc($pic_result);
        $profile_picture_src = file_exists("img/Profilepictures" . $search_friend["UserID"] . "/" . $profile_picture["FileSource"]) ? "img/Profilepictures" . $search_friend["UserID"] . "/" . $profile_picture["FileSource"] : "img/" . $profile_picture["FileSource"];
        $uncached_src = $profile_picture_src . "?" . filemtime($profile_picture_src);
        mysqli_free_result($pic_result);
    ?>
<div class="row polaroid">
  <div class="col-md-3">
    <a href="user_profile.php?id=<?php echo $search_friend['UserID']?>">
    <img src="<?php echo $uncached_src ?>" class="img-responsive" alt="Friend's profile picture'">
    </a>
  </div>
  <div class="col-md-9">
    <a href="user_profile.php?id=<?php echo $search_friend['UserID']?>">
        <h4><?php echo $search_friend["FirstName"] . " " . $search_friend["LastName"]?></h4>
    </a>
    <br />
    <br />
  </div>
</div>
<?php
    }
}
mysqli_free_result($result);
}
?>



<hr />

<h4>People you may know</h4>

<?php
    $friends1 = "SELECT * FROM friendship f
                 WHERE f.User1ID = '{$_SESSION["UserID"]}'
                 AND f.Status = '1'";
    $friendship1 = mysqli_query($conn, $friends1);
    confirm_query($friendship1);
    $friends2 = "SELECT * FROM friendship f
                 WHERE f.User2ID = '{$_SESSION["UserID"]}'
                 AND f.Status = '1'";
    $friendship2 = mysqli_query($conn, $friends2);
    confirm_query($friendship2);
    while ($f1 = mysqli_fetch_assoc($friendship1)) {
        $query1 = "select * from user u
                  where u.UserID = '{$f1["User2ID"]}'";
        $u1 = mysqli_query($conn, $query1);
        confirm_query($u1);
        $user1 = mysqli_fetch_assoc($u1);
        $friends[] = $user1["UserID"];
    }
     while ($f2 = mysqli_fetch_assoc($friendship2)) {
        $query2 = "select * from user u
                  where u.UserID = '{$f2["User1ID"]}'";
        $u2 = mysqli_query($conn, $query2);
        confirm_query($u2);
        $user2 = mysqli_fetch_assoc($u2);
        $friends[] = $user2["UserID"];
    }
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
    $no_recommends = 3;
    foreach($friends_of_friends as $fof => $count) {
        if ($no_recommends > 0) {
            $do_not_recommend_query = "SELECT * FROM do_not_recommend d
                                       WHERE d.UserID = '{$_SESSION["UserID"]}' AND d.UnknownUserID = '{$fof}'";
            $do_not_recommend = mysqli_query($conn, $do_not_recommend_query);
			      $no_recommend = mysqli_fetch_assoc($do_not_recommend);
            $should_recommend = ($no_recommend["UserID"] == "") ? 1 : 0;
            if ($should_recommend) {
                if ($count >= count($friends)/5) {
                    $recommended = "SELECT * FROM user u
                                    WHERE u.UserID = '{$fof}'";
                    $recommendable = mysqli_query($conn, $recommended);
                    $recommend = mysqli_fetch_assoc($recommendable);
                    $profile_picture = find_profile_pic($recommend["UserID"]);
                    $picture = mysqli_fetch_assoc($profile_picture);
                    $picture_src = file_exists("img/Profilepictures" . $recommend["UserID"] . "/" . $picture["FileSource"]) ? "img/Profilepictures" . $recommend["UserID"] . "/" . $picture["FileSource"] : "img/" . $picture["FileSource"];
                    $uncached_src = $picture_src . "?" . filemtime($picture_src);
                    ?>
                    <div class="row polaroid">
                        <div class="col-md-3">
                            <a href="user_profile.php?id=<?php echo $recommend['UserID']?>"><img src="<?php echo $uncached_src ?>" class="img-responsive" alt="Recommended user's profile picture'"></a>
                                </div>
                                    <div class="col-md-9">
                                        <a href="user_profile.php?id=
                                            <?php echo $recommend['UserID']?>"><h4>
                                            <?php echo $recommend["FirstName"] . " " . $recommend["LastName"]?>
                                        </h4>
                                        </a><br /><br />
                                        <form action="search.php?id=<?php echo $fof ?>" method="post">
                                            <input type="submit" name="do_not_recommend" value="Don't know this person" />
                                        </form>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <?php
                }
            $no_recommends--;
            }
        }
    }
?>

<a href="logout.php">Logout</a>

<?php include("../includes/footer.php"); ?>
