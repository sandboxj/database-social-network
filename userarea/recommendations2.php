<?php require_once("../server/functions.php");?>
<?php require_once("../server/functions_photos.php");?>
<?php require_once("../server/functions_friends.php");?>

<?php
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


$cities = ["Aberdeen", "Belfast", "Birmingham", "Cardiff", "Dublin", "Edinburgh", "Glasgow", "London", "Manchester", "Swansea"];
$non_friends_result = find_non_friends($_SESSION["UserID"]);

while ($non_friend = mysqli_fetch_assoc($non_friends_result)) {


	$non_friends[] = $non_friend["UserID"];
	$interests[] = $non_friend["Interest"];
	$birthdates[] = strtotime($non_friend["DateOfBirth"]);
	$locations[] = $non_friend["CurrentLocation"];
}

$do_not_recommend_query = "SELECT * FROM do_not_recommend d
						   WHERE d.UserID = '{$_SESSION["UserID"]}'";
$do_not_recommend_result = mysqli_query($conn, $do_not_recommend_query);


while ($non_recommendable = mysqli_fetch_assoc($do_not_recommend_result)) {
	$non_recommendables[] = $non_recommendable["UnknownUserID"];
}


$self_query = "SELECT * FROM user u
			   WHERE u.UserID = '{$_SESSION["UserID"]}'";
$self_result = mysqli_query($conn, $self_query);
$self = mysqli_fetch_Assoc($self_result);
$self_interest = $self["Interest"];

if ($self_interest == "None") {
	$self_interest == "x";
}

$self_dob = strtotime($self["DateOfBirth"]);
$self_location = $self["CurrentLocation"];
$self_index = array_search($self_location, $cities);
$sum_of_distances = $distances[$self_index][0];
$total_mutual_friends = 0;
$do_not_recommend = 0;
$sum_of_age_differences =0 ;
$unrecommendables=[];

for ($non_friend = 0; $non_friend < count($non_friends); $non_friend++) {
	$mutual_friends_count = find_mutual_friends($_SESSION["UserID"], $non_friends[$non_friend]);
	$total_mutual_friends = $total_mutual_friends + $mutual_friends_count;
	$mutual_friends[] = $mutual_friends_count;
    $age_difference = abs($self_dob - $birthdates[$non_friend]);
	$sum_of_age_differences = $sum_of_age_differences + $age_difference;
	$age_differences[] = $age_difference;

	if (in_array($non_friends[$non_friend], $unrecommendables)) {
		$do_not_recommend = 1;
	}

	$unrecommendables[] = $do_not_recommend;
}
$share_interest = 0;
$scores = [];

for ($non_friend = 0; $non_friend < count($non_friends); $non_friend++) {
	if ($interests[$non_friend] == $self_interest) {
		$share_interest = 1;
	}

	$non_friend_index = array_search($locations[$non_friend], $cities);
	$distance = $distances[$self_index][$non_friend_index];

	if ($total_mutual_friends == 0) {
		$score =  0.1*(1-$distance/$sum_of_distances) + 0.1*$share_interest + 0.1*(1-$age_differences[$non_friend]/$sum_of_age_differences);
        //print $non_friend[$non_friend] . " x " . 0.1*(1-$distance/$sum_of_distances) . " x " . 0.1*$share_interest . " x " . 0.1*(1-$age_difference[$non_friend]/$sum_of_age_differences) . " x " . $score . " z <br/>";
        $scores[$non_friends[$non_friend]] = $score;

	} else {
		$score = 0.7*$mutual_friends[$non_friend]/$total_mutual_friends + 0.1*(1-$distance/$sum_of_distances) + 0.1*$share_interest + 0.1*(1-$age_differences[$non_friend]/$sum_of_age_differences);
        //print $non_friends[$non_friend] . " x " . 0.7*$mutual_friends[$non_friend]/$total_mutual_friends . " x " . 0.1*(1-$distance/$sum_of_distances) . " x " . 0.1*$share_interest . " x " . 0.1*(1-$age_differences[$non_friend]/$sum_of_age_differences) . " x " . $score . " z <br/>";
        $scores[$non_friends[$non_friend]] = $score;
	}
}


arsort($scores);
$num_recommends = 3;
foreach ($scores as $non_friend => $score) {
	if ($num_recommends > 0) {
		if ($unrecommendables[in_array($non_friend, $non_friends)] == 0) {

			$mf = $mutual_friends[in_array($non_friend, $non_friends)];

			$recommended = "SELECT * FROM user 
                      WHERE UserID = '{$non_friend}'";

      $recommendable = mysqli_query($conn, $recommended);
      confirm_query($recommendable);
      $recommend = mysqli_fetch_assoc($recommendable);
      $profile_picture = find_profile_pic($recommend["UserID"]);
      $picture = mysqli_fetch_assoc($profile_picture);
      $picture_src = file_exists("img/Profilepictures" . $recommend["UserID"] . "/" . $picture["FileSource"]) ? "img/Profilepictures" . $recommend["UserID"] . "/" . $picture["FileSource"] : "img/" . $picture["FileSource"];
      $uncached_src = $picture_src . "?" . filemtime($picture_src);?>
			<div class="col-md-6 polaroid col-md-offset-3">
				<div class="col-md-7">
					<a href="user_profile.php?id=<?php echo $recommend['UserID']?>"><img src="<?php echo $uncached_src ?>" class="img-responsive" alt="Recommended user's profile picture'"></a>
				</div>
				<div class="col-md-5">
					<a href="user_profile.php?id=<?php echo $recommend['UserID']?>"><h4><?php echo $recommend["FirstName"] . " " . $recommend["LastName"]?></h4></a>
					<br />
					Location: <?php echo $recommend["CurrentLocation"]?>
					<br />
					You have <?php echo $mf?> mutual friends.
					<br />
	  <?php if ($recommend["Interest"] == $self_interest) {
	    				echo $recommend["Interest"] . " is a shared interest. <br/>";
					}?>
					<br/>
					<form method="post" style="display: inline">
						<button type="submit" name="do_not_recommend" value="<?php echo $recommend['UserID']?>" class="btn btn-primary">Don't know this person</button>
					</form>
                    <br>
                    <br>
                    <br>
                    <br>
					<form method="post" style="display: inline">
						<button type="submit" name="add_request" value="<?php echo $recommend['UserID']?>" class="btn btn-primary">Add friend</button>
					</form>
				</div>
			</div>
	   <?php $num_recommends--;
		}
	}
}
?>