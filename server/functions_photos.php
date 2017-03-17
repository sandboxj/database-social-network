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
    $query .= "ORDER BY CollectionID LIKE 'Profilepictures%' DESC, DateCreated ASC";
    $collection_results = mysqli_query($conn, $query);
    confirm_query($collection_results);
    return $collection_results;
}

function find_photos_from_collection($collection_id)
{
    global $conn;
    $query = "SELECT PhotoID, CollectionID, Caption, DatePosted, FileSource FROM Photo ";
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

function count_photo_comments($photo_id) {
    global $conn;
    $query = "SELECT COUNT(PhotoCommentID) as c FROM Photo_comment ";
    $query .= "WHERE PhotoID = '{$photo_id}';";
    $photo_comments_count_results = mysqli_query($conn, $query);
    confirm_query($photo_comments_count_results);

    $photo_comments_count_array =mysqli_fetch_assoc($photo_comments_count_results);
    $photo_comments_count = $photo_comments_count_array['c'];


    mysqli_free_result($photo_comments_count_results);
    return $photo_comments_count;
}

function newest_photo_src($collection_id) {
    global $conn;
    $query = "SELECT FileSource FROM Photo ";
    $query .= "WHERE CollectionID = '{$collection_id}' ";
    $query .= "ORDER BY DatePosted DESC ";
    $query .= "LIMIT 1 ";
    $photo = mysqli_query($conn, $query);
    confirm_query($photo);
    if ($photo_src = mysqli_fetch_assoc($photo)) {
        return $photo_src;
    } else {
        return null;
    }
}
?>