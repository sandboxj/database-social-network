<?php
/**
 * Created by PhpStorm.
 * User: migue
 * Date: 2/27/2017
 * Time: 8:20 PM
 */


/**this function takes the exploded array of a date and formats it to output
* as a blog's subtitle
*/
function display_formatted_date($date_posted){

    $date_to_format = explode(" ", $date_posted,2);
    $date = explode("-", $date_to_format[0],3);
    $time = explode(":", $date_to_format[1],3);

    $day ="";
    $month="";
    $year="";

    $time_suffix="";
    $output="";

    //checking the time(am or pm)
    if ($time[0] > 12 || $time[0] == 00) {
        $time[0] = $time[0] - 12;
        $time_suffix = " p.m.";
    } else {
        $time_suffix = " a.m.";
    }

    //checking the day
    switch ($date[2]){
        case 1:
            $day = "1st";
            break;
        case 2:
            $day = "2nd";
            break;
        case 3:
            $day = "3rd";
            break;
        case 21:
            $day = "21st";
            break;
        case 22:
            $day = "22nd";
            break;
        case 23:
            $day = "23rd";
            break;
        case 31:
            $day = "31st";
            break;
        default:
            $day="{$date[2]}th";

    }

    //checking the month
    switch ($date[1]){
        case 1:
            $month= "January";
            break;
        case 2:
            $month= "February";
            break;
        case 3:
            $month= "March";
            break;
        case 4:
            $month= "April";
            break;
        case 5:
            $month= "May";
            break;
        case 6:
            $month= "June";
            break;
        case 7:
            $month= "July";
            break;
        case 8:
            $month= "August";
            break;
        case 9:
            $month= "September";
            break;
        case 10:
            $month= "October";
            break;
        case 11:
            $month= "November";
            break;
        case 12:
            $month= "December";
            break;
    }

    $output = "Posted on {$month} {$day} at {$time[0]}:{$time[1]} {$time_suffix}";
    return $output;
}



function find_blogs ($userid) {
    global $conn;

    //double check this query; WHY selecting all from both tables??
    //shouldn't it just be from the blogs table; we won't need all the other user info here
    $query = "SELECT DatePosted, Title, Content, AccessRights, FirstName, LastName FROM user u, blog b
			WHERE u.UserID = b.UserID AND b.UserID = '{$userid}'
			ORDER BY DatePosted DESC";
    $blog_results_db = mysqli_query($conn, $query);
    confirm_query($blog_results_db);


    return $blog_results_db;
}

/**
 * This function finds the blogID for a specific blog based on the currently connected user's id as
 * well as the blog title
 * @param $userid
 * @param $blog_title
 * @return mixed
 *
 * T
 */
function find_blog_id($userid, $blog_title)
{
    global $conn;

    $query = "SELECT BlogID FROM blog
            WHERE blog.UserID = '{$userid}' AND blog.Title = '{$blog_title}'
            LIMIT 1";

    //getting the results from the db
    $blog_results_db = mysqli_query($conn, $query);
    confirm_query($blog_results_db);

    //associative array with blogID
    $blogID_array = mysqli_fetch_assoc($blog_results_db);

    //extracting the numericalID
    $blogID = $blogID_array["BlogID"];

    mysqli_free_result($blog_results_db);

    return $blogID;
}

function find_blog_date($blogID){
    global $conn;


    $query = "SELECT DatePosted FROM blog
            WHERE blog.BlogID = '{$blogID}'
            LIMIT 1";

    $date_posted_db = mysqli_query($conn, $query);
    confirm_query($date_posted_db);

    //associative array with date posted
    $date_posted_array = mysqli_fetch_assoc($date_posted_db);

    $post_date = $date_posted_array["DatePosted"];


    mysqli_free_result($date_posted_db);
    return $post_date;


}

function find_blog_content($blogID){
    global $conn;


    $query = "SELECT Content FROM blog
            WHERE blog.BlogID = '{$blogID}'
            LIMIT 1";

    $content_results_db = mysqli_query($conn, $query);
    confirm_query($content_results_db );

    //associative array with date posted
    $content_array = mysqli_fetch_assoc($content_results_db );

    $blog_content = $content_array["Content"];


    mysqli_free_result($content_results_db);
    return $blog_content;


}

//validates comment and inserts into database
function validate_insert_comment_input($blogID, $userid, $comment_content)
{
    global $conn;


    // Check if post is blank
    if (strlen(trim($comment_content))) {

        $comment_content = mysqli_real_escape_string($conn, $comment_content);

        $post_time = date('Y-m-d H:i:s');
        // Enter post into DB
        $userid = $_SESSION["UserID"];
        $query = "INSERT INTO blog_comment (BlogID, CommenterUserID, DatePosted, Content) ";
        $query .= "VALUES ('{$blogID}', '{$userid}', '{$post_time}', '{$comment_content}'";

        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "Comment Posted";


        } else {
            echo "The comment could not be posted.";
        }


    } else {
        echo "Blog post cannot be empty";
    }

}
?>