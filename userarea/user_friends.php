<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/functions_photos.php");?>
<?php require_once("../server/db_connection.php");?>
<?php $query = "SELECT * FROM user u
                WHERE u.UserID like '{$_GET['id']}'";
                $displayed_user = mysqli_query($conn, $query);
                confirm_query($displayed_user);
                $user = mysqli_fetch_assoc($displayed_user) ?>
<?php $page_title="{$user["FirstName"]} {$user["LastName"]}'s Friends"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

<h2><?php echo "{$user["FirstName"]} {$user["LastName"]}'s Friends" ?></h2>
<?php
    $friends1 = "SELECT * FROM friendship f
                 WHERE f.User1ID = '{$_GET['id']}'
                 AND f.Status = '1'";
    $friendship1 = mysqli_query($conn, $friends1);
    confirm_query($friendship1);
    $friends2 = "SELECT * FROM friendship f
                 WHERE f.User2ID = '{$_GET['id']}'
                 AND f.Status = '1'";
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
