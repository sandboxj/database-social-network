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
            $friendshipz = find_friendship($_SESSION["UserID"], $a_friend["UserID"]);
            $isfriend = (mysqli_fetch_assoc($friendshipz)["User1ID"] == "") ? 0 : 1;
            $display = 1;
            if ($a_friend["PrivacySetting"] == "3") {
                if (!$isfriend) {
                    $display = 0;
                }
            }
            if ($display) {
            $pic_result = find_profile_pic($a_friend["UserID"]);
            $profile_picture = mysqli_fetch_assoc($pic_result);
            $profile_picture_src = file_exists("img/Profilepictures" . $a_friend["UserID"] . "/" . $profile_picture["FileSource"]) ? "img/Profilepictures" . $a_friend["UserID"] . "/" . $profile_picture["FileSource"] : "img/" . $profile_picture["FileSource"];
            $uncached_src = $profile_picture_src . "?" . filemtime($profile_picture_src);

            ?>
            <!--Special case where visitors profile is displayed-->
            <?php if($a_friend['UserID']===$_SESSION["UserID"]) { ?>

                <a href="profile.php">
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
            <?php } else { 
                      $friends_of_searched_result = find_accepted($a_friend["UserID"]);
                      while ($f_of_s = mysqli_fetch_assoc($friends_of_searched_result)) {
                          $fs_of_searched[] = $f_of_s["UserID"];
                      }
                      $friends_result = find_accepted($_SESSION["UserID"]);
                      while ($f = mysqli_fetch_assoc($friends_result)) {
                          $fs[] = $f["UserID"];
                      }
                      $mutual_friend_count = 0;
                      foreach($fs_of_searched as $f) {
                          if (in_array($f , $fs)) {
                              $mutual_friend_count++;
                          }
                      }
                      $self_query = "SELECT * FROM user u
                                     WHERE u.UserID = '{$_SESSION["UserID"]}'";
                      $self_result = mysqli_query($conn, $self_query);
                      $self = mysqli_fetch_assoc($self_result);
                      $self_interest = $self["Interest"];
                      $share_interest = 0;
                      if ($a_friend["Interest"] == $self_interest) {
                          $share_interest = 1;
                      }
            ?>
                <a href="user_profile.php?id=<?php echo $a_friend['UserID'];?>">
                <div class="col-md-6 polaroid">
                    <div class="col-md-7">
                        <img src="<?php echo $uncached_src; ?>" class="img-responsive" alt="Friend's profile picture'">
                    </div>

                    <div class="col-md-5">
                        <h4><?php echo $a_friend["FirstName"] . " " . $a_friend["LastName"];?></h4>
                        <br />
                        Location: <?php echo $a_friend["CurrentLocation"]?>
                        <br />
                        <?php if ($share_interest) {
                        echo $a_friend["Interest"] . " is a shared interest. <br/>";
                        }
                        ?>
                        <br />
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
                                <br/>
                                <form method="post" style="display: inline">
                                    <button type="submit" name="decline_friend" value="<?php echo $friendship["FriendshipID"] ?>" class="btn">Unfriend</button>
                                </form>
                                <?php
                            } else {
                                // If friend request is sent to you, option to accept
                                if ($friendship["User2ID"]===$_SESSION["UserID"]) {
                                    ?>
                                    You have <?php echo $mutual_friend_count?> mutual friends.
                                    <br />
                                    <br />
                                    <form method="post" style="display: inline">
                                        <button type="submit" name="add_friend" value="<?php echo $friendship["FriendshipID"] ?>" class="btn btn-primary">Accept request</button>
                                    </form>
                                    <?php
                                } else {
                                    ?>
                                    You have <?php echo $mutual_friend_count?> mutual friends.
                                    <br />
                                    <br />
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
                            You have <?php echo $mutual_friend_count?> mutual friends.
                            <br />
                            <br />
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
            }


        }//close while

?>

    </div>
</div>



 <?php mysqli_free_result($pic_result);
        mysqli_free_result($accepted_friends);?>
<?php include("../includes/footer.php"); ?>
