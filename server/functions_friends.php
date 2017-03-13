<?php
function find_pending($userid) {
    global $conn;
    $query = "SELECT * FROM friendship f, user u ";
    $query .= "WHERE f.User2ID = '{$userid}' ";
    $query .= "AND f.Status = 0 ";
    $query .= "AND f.User1ID = u.UserID ";
    $query .= "ORDER BY Date DESC";
    $result = mysqli_query($conn, $query);
    confirm_query($result);
    return $result;
}
function find_accepted($userid) {
    global $conn;
    $query = "SELECT * FROM user u ";
    $query .= "JOIN (";
    $query .= "SELECT FriendshipID, User1ID as friend, User2ID as other, Date FROM friendship ";
    $query .= "WHERE User2ID = '{$userid}' AND Status = 1 ";
    $query .= "UNION ALL ";
    $query .= "SELECT FriendshipID, User2ID as friend, User1ID as other, Date FROM friendship ";
    $query .= "WHERE User1ID = '{$userid}' AND Status = 1";
    $query .= ") f ";
    $query .= "ON u.UserID = f.friend ";
    $query .= "ORDER BY FirstName ASC";
    $result = mysqli_query($conn, $query);
    confirm_query($result);
    return $result;
}


function find_friendship($user1ID, $user2ID) {
    global $conn;
    $query = "SELECT * FROM friendship ";
    $query .= "WHERE (User1ID = '{$user1ID}' AND User2ID = '{$user2ID}') ";
    $query .= "OR (User1ID = '{$user2ID}' AND User2ID = '{$user1ID}') ";
    $query .= "LIMIT 1";
    $result = mysqli_query($conn, $query);
    confirm_query($result);
    return $result;
}
function delete_friendship($friendship_id) {
    global $conn;
    $query = "DELETE FROM friendship ";
    $query .= "WHERE FriendshipID = '{$friendship_id}'";
    $result = mysqli_query($conn, $query);
    confirm_query($result);
    return $result;
}
?>