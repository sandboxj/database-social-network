<?php
/**
 * Created by PhpStorm.
 * User: migue
 * Date: 3/8/2017
 * Time: 4:08 PM
 */


//retrieving all of a user's circles and display them; return results for the
//while loop
function find_user_circles($userid)
{
    global $conn;

    $query = "SELECT c.CircleID, CircleAdminUserID, CircleTitle, DateCreated
    FROM circle AS c, circle_member AS cm WHERE c.CircleID = cm.CircleID
        AND MemberUserID = '{$userid}' ";


    $circle_results_db = mysqli_query($conn, $query);
    confirm_query($circle_results_db);

    return $circle_results_db;
}



function validate_circle_name($userid, $circle_title){

    if(strlen(trim($circle_title))){

      $title_empty= false;
      return $title_empty;


    }else{
        //circle name is empty
        $title_empty = true;
        return $title_empty;

    }


}

//title of circles should be unique
function check_circle_title($userid, $circle_title){
    global $conn;





    $title_exists = false;
        $circle_title = mysqli_real_escape_string($conn, $circle_title);

        $title_check= "SELECT  CircleAdminUserID
        FROM circle WHERE CircleTitle = '{$circle_title}'
        AND CircleAdminUserID = '{$userid}'" ;


        $circle_admin_results_db = mysqli_query($conn, $title_check);
        confirm_query($circle_admin_results_db);




        if(mysqli_num_rows($circle_admin_results_db) > 0){
            //circle name already exists

            $title_exists = true;
            return $title_exists;
        }

        //nothing to output if the checks are successful

        return $title_exists;


}


//assume that the person creating the circle is the admin.
function insert_new_circle($userid, $circle_title){
    global $conn;

    $circle_adminID = $userid;
    $date_created = date('Y-m-d H:i:s');

    $circle_title = mysqli_real_escape_string($conn, $circle_title);

    $query = "INSERT INTO circle (CircleAdminUserID, DateCreated, CircleTitle)
    VALUES ('{$circle_adminID}', '{$date_created}', '{$circle_title}')";

    $result = mysqli_query($conn, $query);
    confirm_query($result);

    $circleID = mysqli_insert_id($conn);

    if($result){
        //also need to insert the user in the circle_members table

        $new_member_result = insert_new_circle_member($circleID, $userid);
        if($new_member_result){
            echo "<script>alert('Circle Created Successfully')</script>";
        }

    }else{
        echo "<script>alert('Failed to create circle')</script>";
    }




}


function insert_new_circle_member($circleID, $userid_to_add ){
    global $conn;

    $date_joined= date('Y-m-d H:i:s');

    $query = "INSERT INTO circle_member (CircleID, MemberUserID, DateJoined)
          VALUES ('{$circleID}', '{$userid_to_add}','{$date_joined}')";

    $result = mysqli_query($conn, $query);

    return $result;

}





function count_circle_members($circleID){
    global $conn;

    $query = "SELECT COUNT(CircleMemberID) as NumberOfMembers
     FROM circle_member WHERE CircleID = '{$circleID}' ";


    $member_count_results_db = mysqli_query($conn, $query);
    confirm_query($member_count_results_db);

    $member_count_array = mysqli_fetch_assoc($member_count_results_db);
    $member_count = $member_count_array['NumberOfMembers'];

    mysqli_free_result($member_count_results_db);
    return $member_count;


}



function find_circle_members($circleID){
    global $conn;

    $query = "SELECT MemberUserID FROM circle_member
            WHERE CircleID = '{$circleID}'";

    $members_results_db = mysqli_query($conn, $query);
    confirm_query($members_results_db);

    return $members_results_db;
}


function find_circle_details($circleID){

    global $conn;

    $query = "SELECT  CircleTitle, CircleAdminUserID FROM circle
            WHERE CircleID = '{$circleID}'";

    $circle_details_results_db = mysqli_query($conn, $query);
    confirm_query($circle_details_results_db);

    $circle_details_array = mysqli_fetch_assoc($circle_details_results_db);
    $circle_title = $circle_details_array['CircleTitle'];
    $circle_adminID = $circle_details_array['CircleAdminUserID'];


    mysqli_free_result($circle_details_results_db);

    $details_array = array("circle_title" =>$circle_title,"circle_admin" => $circle_adminID);
    return $details_array;

}


//might not be needed anymore
function find_circle_photoID($circleID){

    global $conn;

    $query = "SELECT CirclePhotoID FROM circle
            WHERE CircleID = '{$circleID}'";

    $circle_photo_results_db = mysqli_query($conn, $query);
    confirm_query($circle_photo_results_db);

    $circle_photo_array = mysqli_fetch_assoc($circle_photo_results_db);
    $circle_photoID = $circle_photo_array['CirclePhotoID'];

    mysqli_free_result($circle_photo_results_db);
    return $circle_photoID;

}

//might not be needed anymore
function find_circle_title($circleID){

    global $conn;

    $query = "SELECT CircleTitle FROM circle
            WHERE CircleID = '{$circleID}'";

    $circle_title_results_db = mysqli_query($conn, $query);
    confirm_query($circle_title_results_db);

    $circle_title_array = mysqli_fetch_assoc($circle_title_results_db);
    $circle_title = $circle_title_array['CircleTitle'];

    mysqli_free_result($circle_title_results_db);
    return $circle_title;

}



//might not be needed anymore
function find_circle_admin($circleID){

    global $conn;

    $query = "SELECT CircleAdminUserID FROM circle
            WHERE CircleID = '{$circleID}'";

    $circle_admin_results_db = mysqli_query($conn, $query);
    confirm_query($circle_admin_results_db);

    $circle_admin_array = mysqli_fetch_assoc($circle_admin_results_db);
    $circle_adminID = $circle_admin_array['CircleAdminUserID'];

    mysqli_free_result($circle_admin_results_db);
    return $circle_adminID;

}

function delete_circle($circleID){
     global $conn;

    $query = "DELETE FROM circle
            WHERE CircleID = '{$circleID}'";

    $result = mysqli_query($conn, $query);

    if($result){
        echo "<script>alert('Circle successfully deleted') </script>";

    }else{
        echo "<script>alert('Circle successfully deleted') </script>";
    }
}

function is_in_specific_circle($userid, $circleID){

//checks whether the given user belongs to a specified circle 
    global $conn;

    $query =  "SELECT * FROM circle_member
            WHERE CircleID='{$circleID}' AND MemberUserID='{$userid}' 
            LIMIT 1";

    $results_db = mysqli_query($conn, $query);

    $is_in_Circle = false;
    
    if($results = mysqli_fetch_assoc($results_db)){
        $is_in_Circle = true;
       
        return $is_in_Circle;
      
    }else{
        return $is_in_Circle;

    }

    mysqli_free_result($results);
            
}



//finish this afterwards; userid is the userid of the person that sets the access rights
//here compare this to check whether a user is in any of the circles
function is_in_another_user_circle($userid, $user_viewer){

    $circle_check = false;
    $circle_results_db = find_user_circles($userid);
    $circleID_array = array();
    while($circle_results = mysqli_fetch_assoc($circle_results_db)){

       
        $circleID = $circle_results['CircleID'];

//array will contain all the circles that the user being viewed is in
        array_push($circleID_array, $circleID );

    }

    //then check whether the viewer is in any of the circles
    $is_in_another_user_circle = false;

    foreach($circleID_array as $user_circle){
        $circle_check = is_in_specific_circle($user_viewer, $user_circle);
        
        if($circle_check == true){
            //the user has circle visibility
            return $circle_check;
        }
    }

//this means that the user viewing another user's profile is not in any 
    return $circle_check;
    
}

//userid of the currently connected user
function leave_circle($circleID, $userid){
    global $conn;

    $query = "DELETE FROM circle_member
    WHERE CircleID ='{$circleID}' AND MemberUserID = '{$userid}'";

    $result = mysqli_query($conn, $query);

    if($result){
        echo "<script>alert('Left circle') </script>";

    }else{
        echo "<script>alert('Could not leave circle') </script>";
    }

}
?>

