<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/db_connection.php");?>
<?php $page_title="{$_SESSION["FirstName"]} {$_SESSION["LastName"]}'s Friends"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

		<h2>Your Friends</h2>
<?php $pending = "SELECT * FROM friendship f
                 WHERE f.User2ID = '{$_SESSION["UserID"]}'
                 AND f.Status = 'Pending'";
    $p_friends = mysqli_query($conn, $pending);
    confirm_query($p_friends);
    $p = mysqli_fetch_assoc($p_friends);
    $exists_pending = ($p["User1ID"] == "") ? 0 : 1;
    if ($exists_pending) { ?>
        <h5>Pending Friend Requests</h5>
        <?php
        $pending_friends = "SELECT * FROM friendship f
                            WHERE f.User2ID = '{$_SESSION["UserID"]}'
                            AND f.Status = 'Pending'";
        $pending_friendship = mysqli_query($conn, $pending_friends);
        confirm_query($pending_friendship);
        while ($pf = mysqli_fetch_assoc($pending_friendship)) {
            $pending_query = "select * from user u
                              where u.UserID = '{$pf["User1ID"]}'";
            $pu = mysqli_query($conn, $pending_query);
            confirm_query($pu);
            $pending_user = mysqli_fetch_assoc($pu);
            $pending_pic = find_profile_pic($pending_user["UserID"]);
            $pending_picture = mysqli_fetch_assoc($pending_pic);
            $pending_out = "../userarea/img/" . $pending_picture["FileSource"];
            $pending_image = "<td><a href='user_profile.php?id={$pending_user["UserID"]}'><img src={$pending_out} class=\"img-rounded\" alt=\"Cinque Terre\" width=\"304\" height=\"236\"></a></td><br/>";
            echo $pending_image;
            $pending_output = "<td><a href='user_profile.php?id={$pending_user["UserID"]}'>" . $pending_user["FirstName"] . " " . $pending_user["LastName"] . "</a></td>";
            echo "<h4>" . $pending_output . "</h4>";
        }
        echo "<hr/>";
    }
?>
<?php
    $friends1 = "SELECT * FROM friendship f
                 WHERE f.User1ID = '{$_SESSION["UserID"]}'
                 AND f.Status = 'Accepted'";
    $friendship1 = mysqli_query($conn, $friends1);
    confirm_query($friendship1);
    $friends2 = "SELECT * FROM friendship f
                 WHERE f.User2ID = '{$_SESSION["UserID"]}'
                 AND f.Status = 'Accepted'";
    $friendship2 = mysqli_query($conn, $friends2);
    confirm_query($friendship2);
    while ($f1 = mysqli_fetch_assoc($friendship1)) {
        $query1 = "select * from user u
                  where u.UserID = '{$f1["User2ID"]}'";
        $u1 = mysqli_query($conn, $query1);
        confirm_query($u1);
        $user1 = mysqli_fetch_assoc($u1);
        $pic1 = find_profile_pic($user1["UserID"]);
        $picture1 = mysqli_fetch_assoc($pic1);
        $out1 = "../userarea/img/" . $picture1["FileSource"];
        $image1 = "<td><a href='user_profile.php?id={$user1["UserID"]}'><img src={$out1} class=\"img-rounded\" alt=\"Cinque Terre\" width=\"304\" height=\"236\"></a></td><br/>";
        echo $image1;
        $output1 = "<td><a href='user_profile.php?id={$user1["UserID"]}'>" . $user1["FirstName"] . " " . $user1["LastName"] . "</a></td>";
        echo "<h4>" . $output1 . "</h4>";
    }
     while ($f2 = mysqli_fetch_assoc($friendship2)) {
        $query2 = "select * from user u
                  where u.UserID = '{$f2["User1ID"]}'";
        $u2 = mysqli_query($conn, $query2);
        confirm_query($u2);
        $user2 = mysqli_fetch_assoc($u2);
        $pic2 = find_profile_pic($user2["UserID"]);
        $picture2 = mysqli_fetch_assoc($pic2);
        $out2 = "../userarea/img/" . $picture2["FileSource"];
        $image2 = "<td><a href='user_profile.php?id={$user2["UserID"]}'><img src={$out2} class=\"img-rounded\" alt=\"Cinque Terre\" width=\"304\" height=\"236\"></a></td><br/>";
        echo $image2;
        $output2 = "<td><a href='user_profile.php?id={$user2["UserID"]}'>" . $user2["FirstName"] . " " . $user2["LastName"] . "</a></td>";
        echo "<h4>" . $output2 . "</h4>";
    }
		?>
		<hr />
		<a href="logout.php">Logout</a>

<?php include("../includes/footer.php"); ?>
