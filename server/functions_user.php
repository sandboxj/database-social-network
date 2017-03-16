<?php
/**
 * Created by PhpStorm.
 * User: migue
 * Date: 2/28/2017
 * Time: 1:23 PM
 */


/**
 *
 * Returns an array with the results from the database
 * containing the user's ["Firstname", "LastName"].
 * @param $userid
 * @return array|null
 */
function find_full_name($userid){
    global $conn;

    $query ="SELECT FirstName, LastName 
            FROM user 
            WHERE UserID= '{$userid}'
            LIMIT 1 ";



    $user_results_db = mysqli_query($conn, $query);
    confirm_query($user_results_db);

    $user_array = mysqli_fetch_assoc($user_results_db);

    mysqli_free_result($user_results_db);

    //returning the array with the full name;
    return $user_array;
}


//want to return the privacy setting as a string
function find_user_privacy_setting($userid){

    global $conn;
    $query  = "SELECT SearchVisibility FROM user JOIN privacy_setting
    WHERE PrivacySettingID = PrivacySetting AND UserID = '{$userid}'";

    $privacy_results_db = mysqli_query($conn, $query);
    confirm_query($privacy_results_db);

    $privacy_setting_array = mysqli_fetch_assoc($privacy_results_db);
    $privacy_setting = $privacy_setting_array['SearchVisibility'];

    mysqli_free_result($privacy_results_db);
    return $privacy_setting;



}


function update_privacy_setting($userid, $privacy_setting){
    global $conn;



    $query = "UPDATE user SET PrivacySetting ='{$privacy_setting}'
    WHERE UserID = '{$userid}'";

    $result = mysqli_query($conn, $query);

   return $result;


}


// THIS FUNCTION IS NOT BEING USED ATM
function convert_privacy_setting_to_enum($privacy_setting){
    global $conn;

    $query = "SELECT PrivacySettingID FROM privacy_setting 
  WHERE SearchVisibility = '{$privacy_setting}' ";

    $privacy_results_db = mysqli_query($conn, $query);
    confirm_query($privacy_results_db);

    $privacy_setting_array = mysqli_fetch_assoc($privacy_results_db);
    $privacy_setting = $privacy_setting_array['PrivacySettingID'];

    mysqli_free_result($privacy_results_db);
    return $privacy_setting;
}
?>