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


<section class="jumbotron jumbotron-friends">

    <div class="container">
        <div class="row text-center">
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
                <h1> Your Friends </h1>
                <?php echo message()?>
            </div>
        </div>
    </div>
</section>

<?php 
    // Display pending requests if exist
    $pending = find_pending($_SESSION["UserID"]);
    if (mysqli_num_rows($pending)>0) { 
?>

        <h3>Pending Friend Requests:</h3>
        <div class="container">
            <div class="row ">
    <?php
        while ($p_friend = mysqli_fetch_assoc($pending)) {
            $pic_result = find_profile_pic($p_friend["UserID"]);
            $profile_picture = mysqli_fetch_assoc($pic_result);
            $profile_picture_src = file_exists("img/Profilepictures" . $p_friend["UserID"] . "/" . $profile_picture["FileSource"]) ? "img/Profilepictures" . $p_friend["UserID"] . "/" . $profile_picture["FileSource"] : "img/" . $profile_picture["FileSource"];
            $uncached_src = $profile_picture_src . "?" . filemtime($profile_picture_src);
            mysqli_free_result($pic_result);
    ?>

            <a href="user_profile.php?id=<?php echo $p_friend['UserID']?>">
                <div class="col-md-6 polaroid">
            <div class="col-md-7">
                <img src="<?php echo $uncached_src ?>" class="img-responsive" alt="Friend's profile picture'">
            </div>
            <div class="col-md-5">
                <h4><?php echo $p_friend["FirstName"] . " " . $p_friend["LastName"]?></h4><br /><br />
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
            </a>


    <?php
        }//closing the while look
    ?>
            </div>
    </div>
<?php
    }
    mysqli_free_result($pending);
?>

<h2>Your Friends:</h2>
<?php
    echo message();
    $accepted_friends = find_accepted($_SESSION["UserID"]);


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
        <a href="user_profile.php?id=<?php echo $a_friend['UserID']?>">
     <div class="col-md-6 polaroid">
            <div class="col-md-7">
                <img src="<?php echo $uncached_src ?>" class="img-responsive" alt="Friend's profile picture'">
            </div>
            <div class="col-md-5">
                <h4><?php echo $a_friend["FirstName"] . " " . $a_friend["LastName"]?></h4><br /><br />
            </div>
            <?php $exist_relation = find_friendship($_SESSION["UserID"], $a_friend["UserID"]);
                  $rows =mysqli_num_rows($exist_relation);
                  if (mysqli_num_rows($exist_relation)>0) {
                      $friendship = mysqli_fetch_assoc($exist_relation);
                  }?>
            <form method="post" style="display: inline">
              <button type="submit" name="decline_friend" value="<?php echo $friendship['FriendshipID'] ?>" class="btn">Unfriend</button>
            </form>
     </div>
</a>
<?php

        }//closing while

?>
    </div>
</div>

<?php
//release the results outside the loop
mysqli_free_result($pic_result);
mysqli_free_result($accepted_friends);
?>



<!--  RECOMMENDATIONS-->
<h4>People you may know</h4>

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
                     AND f.Status = 1";
        $friend_friendship1 = mysqli_query($conn, $friends_of_friends1);
        $friends_of_friends2 = "SELECT * FROM friendship f
                     WHERE f.User2ID = '{$friend}'
                     AND f.User1ID NOT LIKE '{$_SESSION["UserID"]}'
                     AND f.Status = 1";
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
    $count_total = 0;
    foreach($friends_of_friends as $fof => $count) {
        $count_total = $count_total + $count;
    }
    
    $self_query = "SELECT * FROM user u
                   WHERE u.UserID = '{$_SESSION["UserID"]}'";
    $self_result = mysqli_query($conn, $self_query);
    $self = mysqli_fetch_assoc($self_result);
    $self_location = $self["CurrentLocation"];
    if ($self_location == "") {
        $self_location = "null";
    }
    $sum_of_distances = 0;
    $index = -1;
    if ($self_location == "Aberdeen") {
        $index = 0;
    } elseif ($self_location == "Belfast") {
        $index = 1;
    } elseif ($self_location == "Birmingham") {
        $index = 2;
    } elseif ($self_location == "Cardiff") {
        $index = 3;
    } elseif ($self_location == "Dublin") {
        $index = 4;
    } elseif ($self_location == "Edinburgh") {
        $index = 5;
    } elseif ($self_location == "Glasgow") {
        $index = 6;
    } elseif ($self_location == "London") {
        $index = 7;
    } elseif ($self_location == "Manchester") {
        $index = 8;
    } elseif ($self_location == "Swansea") {
        $index = 9;
    }
    
    $distances = array(
      array(4040,0,371,519,634,498,149,195,640,408,626),
      array(2809,371,0,355,392,140,230,176,518,271,356),
      array(2570,519,355,0,142,308,395,406,162,113,170),
      array(2948,634,392,142,0,294,497,492,211,231,55),
      array(2875,498,140,308,294,0,350,308,463,266,248),
      array(2987,149,230,395,497,350,0,67,534,281,484),
      array(2966,195,176,406,492,308,67,0,555,295,472),
      array(3609,640,518,162,211,463,534,555,0,262,264),
      array(2363,408,271,113,231,266,281,295,262,0,236),
      array(2911,626,356,170,55,248,484,472,264,236,0)
    );
    
    $sum_of_distances = $distances[$index][0];
    $self_interest = $self["Interest"];
    if ($self_interest = "None") {
        $self_interest = "";
    }
    $self_dob = strtotime($self["DateOfBirth"]);
    
    $nons = find_non_friends($_SESSION["UserID"]);
    $non_friends = [];
    while ($non = mysqli_fetch_assoc($nons)) {
        if ($non["UserID"] != 1) {
            $non_friends[] = $non["UserID"];
        }
    }
    $scores = [];
    $sum_of_age_differences = 0;
    foreach($non_friends as $nf) {
        $non_friend_query = "SELECT * FROM user u
                             WHERE u.UserID = '{$nf}'";
        $non_friend_result = mysqli_query($conn, $non_friend_query);
        $non_friend = mysqli_fetch_assoc($non_friend_result);
        $non_friend_dob = strtotime($non_friend["DateOfBirth"]);
        $age_difference = abs($self_dob - $non_friend_dob);
        $sum_of_age_differences = $sum_of_age_differences + $age_difference;
    }
    for ($i = 0; $i < count($non_friends); $i++) {
        $non_friend_query = "SELECT * FROM user u
                             WHERE u.UserID = '{$non_friends[$i]}'";
        $non_friend_result = mysqli_query($conn, $non_friend_query);
        $non_friend = mysqli_fetch_assoc($non_friend_result);
        $non_friend_dob = strtotime($non_friend["DateOfBirth"]);
        $age_difference = abs($self_dob - $non_friend_dob);
        $non_friend_location = $non_friend["CurrentLocation"];
        
        $index2 = 0;
        if ($non_friend_location == "Aberdeen") {
            $index2 = 1;
        } elseif ($non_friend_location == "Belfast") {
            $index2 = 2;
        } elseif ($non_friend_location == "Birmingham") {
           $index2 = 3;
        } elseif ($non_friend_location == "Cardiff") {
            $index2 = 4;
        } elseif ($non_friend_location == "Dublin") {
            $index2 = 5;
        } elseif ($non_friend_location == "Edinburgh") {
            $index2 = 6;
        } elseif ($non_friend_location == "Glasgow") {
            $index2 = 7;
        } elseif ($non_friend_location == "London") {
            $index2 = 8;
        } elseif ($non_friend_location == "Manchester") {
            $index2 = 9;
        } elseif ($non_friend_location == "Swansea") {
            $index2 = 10;
        }
        
        
        $distance = $distances[$index][$index2];
        $share_interest = 0;
        $non_friend_query = "SELECT * FROM user u
                             WHERE u.Interest = '{$self_interest}'
                             AND u.UserID = '{$non_friends[$i]}'";
        $non_friend_result = mysqli_query($conn, $non_friend_query);
        $non_friend = mysqli_fetch_assoc($non_friend_result);
        if ($non_friend["Interest"] != "") {
            $share_interest = 1;
        }
        $mutual_friend_count = 0;
        if (array_key_exists($non_friends[$i], $friends_of_friends)) {
            $mutual_friend_count = $friends_of_friends[$non_friends[$i]];
        }
        if (!$count_total == 0) {
            $score = 0.7*$mutual_friend_count/$count_total + 0.1*(1-$distance/$sum_of_distances) + 0.1*$share_interest + 0.1*(1-$age_difference/$sum_of_age_differences);
            //print $non_friends[$i] . " x " . 0.7*$mutual_friend_count/$count_total . " x " . 0.1*(1-$distance/$sum_of_distances) . " x " . 0.1*$share_interest . " x " . 0.1*(1-$age_difference/$sum_of_age_differences) . " x " . $score . " z <br/>";
            $scores[$non_friends[$i]] = $score;
        } else {
            $score =  0.1*(1-$distance/$sum_of_distances) + 0.1*$share_interest + 0.1*(1-$age_difference/$sum_of_age_differences);
            //print $non_friends[$i] . " x " . 0.1*(1-$distance/$sum_of_distances) . " x " . 0.1*$share_interest . " x " . 0.1*(1-$age_difference/$sum_of_age_differences) . " x " . $score . " z <br/>";
            $scores[$non_friends[$i]] = $score;
        }
    }
    arsort($scores);
    $no_recommends = 0;
    foreach ($scores as $nf => $score) {
        if ($no_recommends < 3) {
            $do_not_recommend_query = "SELECT * FROM do_not_recommend d
                                       WHERE d.UserID = '{$_SESSION["UserID"]}' AND d.UnknownUserID = '{$nf}'";
            $do_not_recommend = mysqli_query($conn, $do_not_recommend_query);
			      $no_recommend = mysqli_fetch_assoc($do_not_recommend);
            $pending_query = "SELECT * FROM friendship f
                              WHERE ((f.User1ID = '{$_SESSION["UserID"]}' AND f.User2ID = '{$nf}')
                              OR (f.User2ID = '{$_SESSION["UserID"]}' AND f.User1ID = '{$nf}'))
                              AND f.Status = '0'";
            $pending = mysqli_query($conn, $pending_query);
			      $ispending = mysqli_fetch_assoc($pending);
		        $can_recommend = 0;
            if ($no_recommend["UserID"] == "") {
                if ($ispending["User1ID"] == "") {
                    $can_recommend = 1;
                } else {
                    $can_recommend = 0;
                }
            }
            if ($can_recommend) {
                $recommended = "SELECT * FROM user u
                                WHERE u.UserID = '{$nf}'";
                $recommendable = mysqli_query($conn, $recommended);
                $recommend = mysqli_fetch_assoc($recommendable);
                $profile_picture = find_profile_pic($recommend["UserID"]);
                $picture = mysqli_fetch_assoc($profile_picture);
                $picture_src = file_exists("img/Profilepictures" . $recommend["UserID"] . "/" . $picture["FileSource"]) ? "img/Profilepictures" . $recommend["UserID"] . "/" . $picture["FileSource"] : "img/" . $picture["FileSource"];
                $uncached_src = $picture_src . "?" . filemtime($picture_src);
                $no_recommends++;
                $share_interest = 0;
                if ($recommend["Interest"] == $self_interest) {
                    $share_interest = 1;
                }
                $mutual_friend_count = 0;
                if (array_key_exists($nf, $friends_of_friends)) {
                    $mutual_friend_count = $friends_of_friends[$nf];
                }
                ?>
<div class="row polaroid">
  <div class="col-md-3">
    <a href="user_profile.php?id=<?php echo $recommend['UserID']?>"><img src="<?php echo $uncached_src ?>" class="img-responsive" alt="Recommended user's profile picture'"></a>
  </div>
  <div class="col-md-9">
    <a href="user_profile.php?id=<?php echo $recommend['UserID']?>"><h4><?php echo $recommend["FirstName"] . " " . $recommend["LastName"]?></h4></a>
    <br />
    Location: <?php echo $recommend["CurrentLocation"]?>
    <br />
    You have <?php echo $mutual_friend_count?> mutual friends.
    <br />
    <?php if ($share_interest) {
                              echo $recommend["Interest"] . " is a shared interest. <br/>";
                          }?>
    <br/>
    <form method="post" style="display: inline">
      <button type="submit" name="do_not_recommend" value="<?php echo $recommend['UserID']?>" class="btn btn-primary">Don't know this person</button>
    </form>
    <form method="post" style="display: inline">
      <button type="submit" name="add_request" value="<?php echo $recommend['UserID']?>" class="btn btn-primary">Add friend</button>
    </form>
  </div>
</div>
<?php
            }
        }
    }
    
?>


<?php include("../includes/footer.php"); ?>
