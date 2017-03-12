<?php
function find_profile_pic($userid)
{
            global $conn;
            // $search_term = $userid . "_profilepicture% ";
            $query = "SELECT * FROM user u, photo p ";
            $query .= "WHERE u.ProfilePhotoID = p.PhotoID AND u.UserID = '{$userid}' ";
            $query .= "LIMIT 1";
            $pic_results = mysqli_query($conn, $query);
            confirm_query($pic_results);
            return $pic_results;
}

function find_collection($collection_id)
{
    global $conn;
    $query = "SELECT * FROM Photo_Collection ";
    $query .= "WHERE CollectionID = '{$collection_id}' ";
    $collection_exist = mysqli_query($conn, $query);
        if ($exist = mysqli_fetch_assoc($collection_exist)) {
        mysqli_free_result($collection_exist);
        return $exist;
    } else {
        return false;
    }
}

function exist_collection($collection_id) {
    if (!find_collection($collection_id)) {
        redirect_to("collections.php");
    }
}

function find_collections($userid)
{
    global $conn;
    $query = "SELECT * FROM Photo_Collection ";
    $query .= "WHERE UserID = '{$userid}' ";
    $query .= "ORDER BY DateCreated ASC";
    $collection_results = mysqli_query($conn, $query);
    confirm_query($collection_results);
    return $collection_results;
}

function find_photos_from_collection($collection_id)
{
    global $conn;
    $query = "SELECT * FROM Photo ";
    $query .= "WHERE CollectionID = '{$collection_id}' ";
    $query .= "ORDER BY DatePosted DESC";
    $photos_results = mysqli_query($conn, $query);
    confirm_query($photos_results);
    return $photos_results;
}

function find_photo_comments($photo_id) {
    global $conn;
    $query = "SELECT * FROM Photo_comment ";
    $query .= "WHERE PhotoID = '{$photo_id}';";
    $photo_comments_results = mysqli_query($conn, $query);
    confirm_query($photo_comments_results);
    return $photo_comments_results;
}
?>