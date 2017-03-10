<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/functions_photos.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/validation_search.php");?>
<?php $page_title="Search"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

<style>
  input[type=text] {
  width: 200px;
  box-sizing: border-box;
  border: 2px solid #ccc;
  border-radius: 4px;
  font-size: 18px;
  background-color: white;
  background-position: 10px 10px;
  background-repeat: no-repeat;
  padding: 5px 20px 5px 20px;
  margin: 5px 0px 5px 0px;
  -webkit-transition: width 0.4s ease-in-out;
  transition: width 0.4s ease-in-out;
}

input[type=text]:focus {
  width: 100%;
}
</style>

<h2>Find Friends</h2>
<?php echo message()?>
<form action="search.php" method="post">
    <input type="text" name="search_query" placeholder="Name..."></input><br />
    <button type="submit" name="search_result" value="Search" class="btn btn-primary">Search</button>
</form>

<?php
    if (isset($_POST["search_result"])) {
?>
    <hr />
    <h4>Search results</h4>
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
    }
?>



<hr />

<h4>People you may know</h4>

<!--<?php
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
    
    foreach($friends as $friend) {
        $friends_of_friends1 = "SELECT * FROM friendship f
                     WHERE f.User1ID = '{$friend}'
                     AND f.User2ID NOT LIKE '{$_SESSION["UserID"]}'
                     AND f.Status = 'Accepted'";
        $friend_friendship1 = mysqli_query($conn, $friends_of_friends1);
        $friends_of_friends2 = "SELECT * FROM friendship f
                     WHERE f.User2ID = '{$friend}'
                     AND f.User1ID NOT LIKE '{$_SESSION["UserID"]}'
                     AND f.Status = 'Accepted'";
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
    foreach($friends_of_friends as $fof => $count) {
        if ($count >= count($friends)/5) {
            $recommended = "SELECT * FROM user u
                            WHERE u.UserID = '{$fof}'";
            $recommendable = mysqli_query($conn, $recommended);
            $recommend = mysqli_fetch_assoc($recommendable);
            $profile_picture = find_profile_pic($recommend["UserID"]);
            $picture = mysqli_fetch_assoc($profile_picture);
            $source = "../userarea/img/" . $picture["FileSource"];
            $profile_pic = "<td><a href='user_profile.php?id={$recommend["UserID"]}'><img src={$source} class=\"img-rounded\" alt=\"Cinque Terre\" width=\"304\" height=\"236\"></a></td><br/>";
            echo $profile_pic;
            $name = "<td><a href='user_profile.php?id={$recommend["UserID"]}'>" . $recommend["FirstName"] . " " . $recommend["LastName"] . "</a></td>";
            echo "<h4>" . $name . "</h4>";
        }
    }
?>-->


<a href="logout.php">Logout</a>

<?php include("../includes/footer.php"); ?>
