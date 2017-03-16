<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/functions_photos.php");?>
<?php require_once("../server/functions_friends.php");?>
<?php require_once("../server/functions_circle.php");?>
<?php require("../server/functions_blog.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/validation_search.php");?>
<?php require_once("../server/validation_friends.php");?>
<?php require_once("../userarea/unrecommend.php");?>
<?php $page_title="Search"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

<?php echo message()?>


<?php
if (isset($_POST["search_result"]) && $result) {
?>

<h4>User results</h4>
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
  		<div class="col-md-3">
    		     <a href="user_profile.php?id=<?php echo $search_friend['UserID']?>"><img src="<?php echo $uncached_src ?>" class="img-responsive" alt="Friend's profile picture'"></a>
  		</div>
  	    	<div class="col-md-9">
    		     <a href="user_profile.php?id=<?php echo $search_friend['UserID']?>"><h4><?php echo $search_friend["FirstName"] . " " . $search_friend["LastName"]?></h4></a>
    		     <br />
    		     <br />
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
    		    <br />
    		    <br />
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

<hr />

<h4>Blog Results</h4>
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
            $formatted_date  = display_formatted_date($blog["DatePosted"]);
            $output = "Title: <td> {$blog["Title"]} </td><br />";
            ?>
            <a href='user_blog.php?title='<?php echo $blog["Title"]; ?>&id=<?php echo $blog["UserID"] ?>'>
              <div class="polaroid col-md-4 individual-blog">
                <?php echo $output; ?>
                <h6><?php echo $formatted_date ?></h6>
              </div></a>
  <?php }
    }
}
}
?>


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
    } else {
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
        } else {
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
