<?php
if (isset($_GET["addfriend"])) {
	  $time_now = date('Y-m-d H:i:s');
    $friends1 = "SELECT * FROM friendship f
                 WHERE ((User1ID='{$_GET["id"]}' AND User2ID='{$_SESSION["UserID"]}')
                    OR (User2ID='{$_GET["id"]}' AND User1ID='{$_SESSION["UserID"]}'))";
    $friendship1 = mysqli_query($conn, $friends1);
    $friend1 = mysqli_fetch_assoc($friendship1);
    $isfriend = ($friend1["User1ID"] == "") ? 0 : 1;
    if (!$isfriend) {
	  $add_relation = "INSERT INTO friendship (User1ID, User2ID, Status, Date)
					           VALUES ('{$_SESSION["UserID"]}', '{$_GET["id"]}', 'Pending', '{$time_now}')";
	  $add = mysqli_query($conn, $add_relation);
    }
} 
if (isset($_GET["accept"])) {
    $accept_relation = "UPDATE friendship SET Status='Accepted' WHERE User1ID='{$_GET["id"]}' AND User2ID='{$_SESSION["UserID"]}'";
	  $accept = mysqli_query($conn, $accept_relation);
} 
if (isset($_GET["decline"]) || isset($_GET["unfriend"])) {
    $delete_relation = "DELETE FROM friendship WHERE ((User1ID='{$_GET["id"]}' AND User2ID='{$_SESSION["UserID"]}')
                                                  OR (User2ID='{$_GET["id"]}' AND User1ID='{$_SESSION["UserID"]}'))";
	  $delete = mysqli_query($conn, $delete_relation);
}
if (isset($_GET["cancel"])) {
    $delete_relation = "DELETE FROM friendship WHERE ((User1ID='{$_GET["id"]}' AND User2ID='{$_SESSION["UserID"]}')
                                                  OR (User2ID='{$_GET["id"]}' AND User1ID='{$_SESSION["UserID"]}'))
                                                  AND Status='Pending'";
	  $delete = mysqli_query($conn, $delete_relation);
}
?>