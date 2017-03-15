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

function find_non_friends($userid) {
    global $conn;
    $query = "SELECT * FROM User WHERE UserID != '{$userid}' AND UserID NOT IN (";
    $query .= "SELECT * FROM user u ";
    $query .= "JOIN (";
    $query .= "SELECT FriendshipID, User1ID as friend, User2ID as other, Date FROM friendship ";
    $query .= "WHERE User2ID = '{$userid}' AND Status = 1 ";
    $query .= "UNION ALL ";
    $query .= "SELECT FriendshipID, User2ID as friend, User1ID as other, Date FROM friendship ";
    $query .= "WHERE User1ID = '{$userid}' AND Status = 1";
    $query .= ") f ";
    $query .= "ON u.UserID = f.friend ";
    $query .= ")";
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

//pass results from db here; friend_userid refers to the person whose profile we are viewing
function check_friendship($friend_userid, $viewer_userID)
{ //checking if the viewer is friend with the user
    $friend_results_db = find_accepted($viewer_userID);

    $is_friend = false;

    while ($friend_results = mysqli_fetch_assoc($friend_results_db)) {
        //friendID is the ID of friend of the viewer
        $friendID = $friend_results['UserID'];

        if ($friendID == $friend_userid) {
            //if the user whose page we are visiting is a friend
            $is_friend = true;
            return $is_friend;

        }

    }
    mysqli_free_result($friend_results_db);
    return $is_friend;
}

//when on the page of someone who is not a friend --> not_friend_userID=
function check_friends_of_friends($not_friend_userID, $viewer_userID){

    $is_friend_of_friend = false;

    $not_friend_results_db = find_accepted($not_friend_userID);

    //array of all the friends of the person whose page we are viewing
    $not_friend_friends_array = array();
    while ($not_friend_results = mysqli_fetch_assoc($not_friend_results_db)) {
        //friendID is the ID of the friends whose person we are not friends with
        $friendID = $not_friend_results['UserID'];
        array_push($not_friend_friends_array, $friendID);
}

    foreach($not_friend_friends_array as $friend_of_not_friend){
        $is_friend = check_friendship($friend_of_not_friend, $viewer_userID);

        if($is_friend == true){
            $is_friend_of_friend = true;
            return $is_friend_of_friend;
        }
    }

    return $is_friend_of_friend;
}

?>