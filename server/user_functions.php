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
?>