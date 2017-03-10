<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/functions_photos.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/validation_search.php");?>
<?php $page_title="Search results"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

		<h2>Search results</h2>
		
    <?php echo message()?>
    <?php
    while($u = mysqli_fetch_assoc($result)) {
        $pic = find_profile_pic($u["UserID"]);
        while($picture = mysqli_fetch_assoc($pic)) {
            $out = "../userarea/img/" . $picture["FileSource"];
        }
        $image = "<td><a href='user_profile.php?id={$u["UserID"]}'><img src={$out} class=\"img-rounded\" alt=\"Cinque Terre\" width=\"304\" height=\"236\"></a></td><br/>";
        echo $image;
        $output = "<td><a href='user_profile.php?id={$u["UserID"]}'>" . $u["FirstName"] . " " . $u["LastName"] . "</a></td>";
        echo "<h4>" . $output . "</h4>";
    }
    mysqli_free_result($result);
		?>
		<hr />
		<a href="logout.php">Logout</a>

<?php include("../includes/footer.php"); ?>
