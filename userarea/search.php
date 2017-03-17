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

    <div class="container">
    <div class="row">
    <div class="col-md-6">
        <h1 class="lobster-title">User results:</h1>
        <?php
        if (mysqli_num_rows($result)<1) {
            echo ("<p style='font-style: italic'>No matches.</p>");
        } else {
            while ($search_friend = mysqli_fetch_assoc($result)) {
                $friendshipz = find_friendship($_SESSION["UserID"], $search_friend["UserID"]);
                $isfriend = (mysqli_fetch_assoc($friendshipz)["User1ID"] == "") ? 0 : 1;
                if ($isfriend || $search_friend["PrivacySetting"] == "2") {
                    $pic_result = find_profile_pic($search_friend["UserID"]);
                    $profile_picture = mysqli_fetch_assoc($pic_result);
                    $profile_picture_src = file_exists("img/Profilepictures" . $search_friend["UserID"] . "/" . $profile_picture["FileSource"]) ? "img/Profilepictures" . $search_friend["UserID"] . "/" . $profile_picture["FileSource"] : "img/" . $profile_picture["FileSource"];
                    $uncached_src = $profile_picture_src . "?" . filemtime($profile_picture_src);
                    mysqli_free_result($pic_result);
                    ?>
                    <div class="row polaroid">
                        <div class="col-md-7">
                            <a href="user_profile.php?id=<?php echo $search_friend['UserID']?>"><img src="<?php echo $uncached_src ?>" class="img-responsive" alt="Friend's profile picture'"></a>
                        </div>
                        <div class="col-md-5">
                            <a href="user_profile.php?id=<?php echo $search_friend['UserID']?>"><h4><?php echo $search_friend["FirstName"] . " " . $search_friend["LastName"]?></h4></a>
                            <br /><br />
                                            <?php
                $exist_relation = find_friendship($_SESSION["UserID"], $search_friend["UserID"]);
                // If relation exists, accept or unfriend
                if (mysqli_num_rows($exist_relation)>0) {
                    $friendship = mysqli_fetch_assoc($exist_relation);
                    if ($friendship["Status"]) {
                        ?>
                        <form method="post" style="display: inline">
                            <button type="submit" name="decline_friend" value="<?php echo $friendship["FriendshipID"] ?>" class="btn btn-default ">Unfriend</button>
                        </form>
                        <?php
                    } else {
                        // If friend request is sent to you, option to accept
                        if ($friendship["User2ID"]===$_SESSION["UserID"]) {
                            ?>
                            <form method="post" style="display: inline">
                                <button type="submit" name="add_friend" value="<?php echo $friendship["FriendshipID"] ?>" class="btn btn-primary ">Accept request</button>
                            </form>
                            <?php
                        } else {
                            ?>
                            <form method="post" style="display: inline">
                                <button type="submit" name="decline_friend" value="<?php echo $friendship["FriendshipID"] ?>" class="btn btn-default ">Pending / Cancel request</button>
                            </form>
                            <?php
                        }
                    }
                }
                // If no relation exists, option to add friend
                else {
                    ?>
                    <form method="post" style="display: inline">
                        <button type="submit" name="add_request" value="<?php echo $search_friend['UserID'] ?>" class="btn btn-primary ">Add friend</button>
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
                                                <?php
                $exist_relation = find_friendship($_SESSION["UserID"], $search_friend["UserID"]);
                // If relation exists, accept or unfriend
                if (mysqli_num_rows($exist_relation)>0) {
                    $friendship = mysqli_fetch_assoc($exist_relation);
                    if ($friendship["Status"]) {
                        ?>
                        <form method="post" style="display: inline">
                            <button type="submit" name="decline_friend" value="<?php echo $friendship["FriendshipID"] ?>" class="btn btn-default ">Unfriend</button>
                        </form>
                        <?php
                    } else {
                        // If friend request is sent to you, option to accept
                        if ($friendship["User2ID"]===$_SESSION["UserID"]) {
                            ?>
                            <form method="post" style="display: inline">
                                <button type="submit" name="add_friend" value="<?php echo $friendship["FriendshipID"] ?>" class="btn btn-primary ">Accept request</button>
                            </form>
                            <?php
                        } else {
                            ?>
                            <form method="post" style="display: inline">
                                <button type="submit" name="decline_friend" value="<?php echo $friendship["FriendshipID"] ?>" class="btn btn-default ">Pending / Cancel request</button>
                            </form>
                            <?php
                        }
                    }
                }
                // If no relation exists, option to add friend
                else {
                    ?>
                    <form method="post" style="display: inline">
                        <button type="submit" name="add_request" value="<?php echo $search_friend['UserID'] ?>" class="btn btn-primary ">Add friend</button>
                    </form>
                    <?php
                }
                ?>
                            </div>
                        </div>
                        <?php
                    } else {
                        echo ("<p style='font-style: italic'>No matches.</p>");
                    }
                } else {
                }
            }
        }
        mysqli_free_result($result);
        ?>

    </div>
    <div class="col-md-6">

    <h1 class="lobster-title">Blog Results:</h1>

    <?php
    if (mysqli_num_rows($result2)<1) {
        echo ("<p style='font-style: italic'>No matches.</p>");
    } else {
        while ($blog = mysqli_fetch_assoc($result2)) {
            $is_in_user_circle = is_in_another_user_circle($blog["UserID"], $_SESSION["UserID"]);
            $is_friend = check_friendship($blog["UserID"], $_SESSION["UserID"]);
            if($is_friend == true){
                $is_friend_of_friend = true;
            }else{
                $is_friend_of_friend = check_friends_of_friends($blog["UserID"], $_SESSION["UserID"]);
            }
            $check = confirm_access_rights($blog["AccessRights"], $is_friend, $is_in_user_circle, $is_friend_of_friend);
            if($check == true) {
                $circle_author = find_full_name($blog["UserID"]);
                $formatted_date  = display_formatted_date($blog["DatePosted"]);
                $title_output = "Title: <td> {$blog["Title"]} </td><br />";
                ?>
                <a href='user_blog.php?title=<?php echo $blog["Title"]; ?>&id=<?php echo $blog["UserID"] ?>'>
                    <div class="polaroid col-md-6 individual-blog">
                        <h3><?php echo $title_output; ?></h3>
                        <br>
                        <h4>Author: <?php echo "{$circle_author['FirstName']} {$circle_author['LastName']}"?></h4>
                        <h6><?php echo $formatted_date ?></h6>
                    </div></a>
            <?php }
        }
    }
}
?>
    </div>
    </div>
    </div>







    <h1 class="lobster-title">People you may know:</h1>


<?php include("recommendations2.php")?>
<?php include("../includes/footer.php"); ?>