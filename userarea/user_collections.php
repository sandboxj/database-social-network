<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/functions_photos.php");?>
<?php require_once("../server/functions_friends.php");?>
<?php require_once("../server/blog_functions.php");?>
<?php require_once("../server/circle_functions.php");?>
<?php $visited_user = find_user_by_id($_GET["id"]); ?>
<?php $page_title= "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Collections"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

    <h2><?php echo "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Collections" ?></h2>
    <?php include("user_navbar.php"); ?><br />
    <div class="container">
        <?php
            $viewer_userID = $_SESSION['UserID'];
            $user_collections = find_collections($visited_user["UserID"]);
            $count = 0;
        while ($collection = mysqli_fetch_assoc($user_collections)) {
            $access_rights = $collection["AccessRights"];
            $in_circle = is_in_another_user_circle($visited_user["UserID"], $viewer_userID);
            $is_friend = check_friendship($visited_user["UserID"], $viewer_userID);
            $is_friend_of_friend = ($is_friend) ? true : check_friends_of_friends($visited_user["UserID"], $viewer_userID);
            $check = confirm_access_rights($access_rights, $is_friend, $in_circle, $is_friend_of_friend);
            if(!$check) {
                continue;
            }
            // if ($access_rights==0) {
            //     continue;
            // } else {
			// 	if($access_rights==1) {
			// 		$exist_friendship = find_friendship($viewer_userID, $visited_user["UserID"]);
			// 		if (mysqli_num_rows($exist_friendship)<1) {
			// 			continue;
			// 		} else {}
			// 	} elseif ($access_rights == 3) {
			// 		if(!is_in_another_user_circle($visited_user["UserID"], $viewer_userID)) {
            //             continue;
            //         }
			// 	} elseif ($access_rights == 4) {
			// 		if(!check_friends_of_friends($visited_user["UserID"], $viewer_userID)) {
            //             continue;
            //         }
                if (($count == 0)) {
                    echo "<div class='row'>";
                } else {
                }
        ?>
        <div class='polaroid col-md-3'>
            <figure>
                <?php 
                    $newest_photo = newest_photo_src($collection["CollectionID"]);
                    $newest_src = (isset($newest_photo)) ? "img/" . $collection["CollectionID"] . "/" . $newest_photo["FileSource"] : "img/empty.png";
                ?>
                <a href='user_photos.php?id=<?php echo $visited_user["UserID"] ?>&collection=<?php echo $collection["CollectionID"] ?>'>
                <img src="<?php echo $newest_src; ?>" alt='thumbnail' class="center-block img-responsive"><br />
                </a>
                <figcaption>
                <a href='user_photos.php?id=<?php echo $visited_user["UserID"] ?>&collection=<?php echo $collection["CollectionID"] ?>'>
                <?php echo $collection["CollectionTitle"] ?> 
                </a>
                </figcaption>
            </figure>
        </div>
        <?php
				if (($count == 3)) {
					echo "</div>";
					$count = 0;
				} else {
					$count += 1;
				}
            }
        
        ?>
        <?php
            mysqli_free_result($user_collections);
        ?>
    </div></div>
        
    <hr />
    <a href="logout.php">Logout</a>

<?php include("../includes/footer.php"); ?>
