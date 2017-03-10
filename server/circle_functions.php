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

    $query = "SELECT c.CircleID, CircleTitle, DateCreated, CirclePhotoID
    FROM circle AS c, circle_member AS cm WHERE c.CircleID = cm.CircleID
        AND MemberUserID = '{$userid}' ";

    $circle_results_db = mysqli_query($conn, $query);
    confirm_query($circle_results_db);

    return $circle_results_db;
}



function validate_circle_name($userid, $circle_title){

    if(strlen(trim($circle_title))){

        return true;


    }else{
        //circle name is empty
        $errors['CircleName']="Circle Name cannot be empty";
        return false;

    }


}

//assume that the person creating the circle is the admin.
function insert_new_circle($userid, $circle_title, $circle_photoID){
    global $conn;

    $circle_adminID = $userid;
    $date_created = date('Y-m-d H:i:s');

    $circle_title = mysqli_real_escape_string($conn, $circle_title);

    $query = "INSERT INTO circle (CircleAdminUserID, DateCreated, CircleTitle, CirclePhotoID)
    VALUES ('{$circle_adminID}', '{$date_created}', '{$circle_title}','{$circle_photoID}')";

    $result = mysqli_query($conn, $query);

    $circleID = mysqli_insert_id($conn);

        echo "circleID: " .$circleID;

    if($result){
        //also need to insert the user in the circle_members table
        echo "circle was created";
        insert_new_circle_member($circleID, $userid);


    }else{
        $errors["Circle"] = "Failed to create circles";
    }




}



function insert_new_circle_member($circleID, $userid ){
    global $conn;

    $date_joined= date('Y-m-d H:i:s');

    $query = "INSERT INTO circle_member (CircleID, MemberUserID, DateJoined)
          VALUES ('{$circleID}', '{$userid}','{$date_joined}')";

    $result = mysqli_query($conn, $query);

    if($result){
        echo "member was inserted";

    }else{
        $error['CircleMember'] = "Failed to insert new member";
    }

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

//title of circles should be unique
//function check_circle_title($userid, $circle_title){
//    global $conn;
//
//    if(strlen(trim($circle_title))){
//
//        $circle_title = mysqli_real_escape_string($circle_title);
//
//        $title_check= "SELECT  CircleTitle
//    FROM circle WHERE CircleID = (
//        SELECT circleID FROM circle_member
//        WHERE MemberUserID = '{$userid}')
//        AND CircleTitle = '{$circle_title}'";
//
//        $title_results_db = mysqli_query($conn, $title_check);
//        $circle_title_array = mysqli_fetch_assoc($title_results_db);
//        $circle_title_db = $circle_title_array['CircleTitle'];
//
//        if($circle_title === $circle_title_db){
//            //circle name already exists
//            $output = "You already have a blog with this name, please change the name";
//            return $output;
//        }
//
//        //nothing to output if the checks are successful
//        $output ="";
//        return $output;
//
//
//    }else{
//        //if no circle name is provided
//
//    }
//}

function find_circle_members($circleID){
    global $conn;

    $query = "SELECT MemberUserID FROM circle_member
            WHERE CircleID = '{$circleID}'";

    $members_results_db = mysqli_query($conn, $query);
    confirm_query($members_results_db);

    return $members_results_db;
}

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

// function toggle_invite_menu(){
//     echo "<br><br>
//     <div class='container' style='border-style: solid;'>
//             <div class='row'>
//             <h2>Choose a friend to invite to your circle:</h2>
//              <div class='col-md-5'>
            
//                  <ul>
//                     <li> Name here</li>
//                     </ul>
//                </div>
//             </div>
//            </div>";

// }

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

    $query =  "SELECT CircleID, MemberUserID FROM circle_member
            WHERE CircleID='{$circleID}' AND '{$userid}' 
            LIMIT 1";

    $results = mysqli_query($conn, $query);

    $is_in_Circle = false;
    if($results){
        $is_in_Circle = true;
        return $is_in_Circle;
    }else{
        return $is_in_Circle;

    }

    mysqli_free_result($results);
            
}

function add_circle_member($circleID, $userid_to_add){
    global $conn;

    $date_joined = date('Y-m-d H:i:s');

    $query = "INSERT INTO circle_member (CircleID, MemberUserID, DateJoined)
    VALUES('{$circleID}', '{$userid_to_add}', '{$date_joined}')";

    
    $result = mysqli_query($conn, $query);


    if ($result) {
        echo "<script>alert('Member was added successfully')</script>";


    } else {
        echo  "<script>alert('Failed to add member')</script>";
    }

}

//finish this afterwards; userid is the userid of the person that sets the access rights
//here compare this to check whether a user is in any of the circles
function is_in_another_user_circle($userid, $user_viewer){


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
        $check = is_in_specific_circle($user_viewer, $user_circle);
        
        if($check == true){
            //the user has circle visibility
            return $check;
        }
    }

//this means that the user viewing another user's profile is not in any 
    return $check;
    
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

