<?php
/**
 * Created by PhpStorm.
 * User: migue
 * Date: 2/27/2017
 * Time: 8:20 PM
 */



//GENERAL PURPOSE FUNCTIONS **********************************************************************

/**
 * This function converts access rights in string form to an integer equivalent so that this value
 * may be inserted in the database.
 * @param $access_rights
 * @return int
 */
function convert_access_rights_to_int($access_rights){

    switch($access_rights){
        case "Only me":
            $access_rights = 0;
            break;
        case "Friends":
            $access_rights = 1;
            break;
        case "Everybody":
            $access_rights = 2;
            break;
        case "Circles":
            $access_rights = 3;
            break;
        case "Friends of friends":
            $access_rights = 4;
            break;
    }


    return $access_rights;
}


/**
 * This function converts access rights in integer form to a string equivalent so that this value
 * may be rendered in the DOM. This is because access rights are stored in integer form inside the database.
 * @param $access_rights
 * @return string
 */
function convert_access_rights_to_string($access_rights){

    switch($access_rights){
        case 0:
            $access_rights = "Only me";
            break;
        case 1:
            $access_rights = "Friends";
            break;
        case 2:
            $access_rights = "Everybody";
            break;
        case 3:
            $access_rights = "Circles";
            break;
        case 4:
            $access_rights = "Friends of friends";
            break;
    }

    return $access_rights;
}

/**This function takes the exploded array of a date and formats it to output
* as a blog's subtitle. (Change the output directly here).
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
    if ($time[0] > 12) {
        $time[0] = $time[0] - 12;
        $time_suffix = " p.m.";
    } elseif($time[0] == 00){
        $time_suffix = " p.m.";
    }
    //avoids displaying -12 at midnight
    else {
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
            //making sure the 0 is removed e.g. 04 -->4
            $day = (int) $date[2];
            $day="{$day}th";

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



/**
 * This function checks the access rights of the blog and returns a boolean to determine whether the blog should
 * or should not be displayed in the blog page; note that the method uses the integer access_rights from the db.
 * $is_Friend is a boolean that checks the currently connected user is friends with the user whose blogs he is trying to view
 * (true if yes, false if no). $is_Circle checks whether a user is in the same circle as another.
 *
 *
 * @param $access_rights
 * @param $isFriend
 * @param $isCircle
 * @return bool
 */
function confirm_access_rights($access_rights, $is_friend, $is_circle){

    $check = false;

    switch ($access_rights){
        //only me
        case 0:
            //don't display
            return $check;
        //friends
        case 1:
            if($is_friend == true){
                $check = true;
                return $check;

            }else{
                return $check;
            }
        //everyone
        case 2:
            $check =true;
            return $check;
        //circles
        case 3:
            if($is_circle == true){
                $check = true;
                return $check;
            }else{
                return $check;
            }
    }


}


//BLOGS & BLOG related FUNCTIONS *********************************************************************

/**
 * This function retrieves all of a user's blogs from the database and returns
 * the result set so that each row can be rendered in the DOM. Note that the results
 * of this method need to be released after use.
 *
 * Attributes retrieved: DatePosted, Title, Content, AccessRights, FirstName, LastName
 *
 * @param $userid
 * @return bool|mysqli_result
 */
function find_blogs ($userid) {
    global $conn;


    $query = "SELECT DatePosted, Title, Content, AccessRights, FirstName, LastName FROM user u, blog b
			WHERE u.UserID = b.UserID AND b.UserID = '{$userid}'
			ORDER BY DatePosted DESC";
    $blog_results_db = mysqli_query($conn, $query);
    confirm_query($blog_results_db);


    return $blog_results_db;
}




/**
 * This function finds and retrieves the blogID for a specific blog from the database,
 * based on the currently connected user's id as
 * well as the blog title. Titles are unique to ensure that this works.
 *
 * @param $userid
 * @param $blog_title
 * @return int blogID
 *
 *
 */
function find_blog_id($userid, $blog_title)
{
    global $conn;


    $query = "SELECT BlogID FROM blog
            WHERE UserID = '{$userid}' AND Title = '{$blog_title}'
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


/**This function finds and retrieves a blog post's date from the database. The blog is specified
 * by the blogID parameter.
 * @param $blogID
 * @return string
 */
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

/**
 * This function finds and retrieves ta blog's title from the database. The blog is specified
 * by the blogID parameter.
 *
 * @param $blogID
 * @return string
 */
function find_blog_title($blogID){
    global $conn;


    $query = "SELECT Title FROM blog
            WHERE blog.BlogID = '{$blogID}'
            LIMIT 1";

    $title_db = mysqli_query($conn, $query);
    confirm_query($title_db);

    //associative array with date posted
    $title_array = mysqli_fetch_assoc($title_db);

    $title = $title_array["Title"];


    mysqli_free_result($title_db);
    return $title;


}

/**
 * This function finds and retrieves ta blog's content from the database. The blog is specified
 * by the blogID parameter.
 * @param $blogID
 * @return string
 */
function find_blog_content($blogID){
    global $conn;


    $query = "SELECT Content FROM blog
            WHERE blog.BlogID = '{$blogID}'";

    $content_results_db = mysqli_query($conn, $query);
    confirm_query($content_results_db );

    //associative array with date posted
    $content_array = mysqli_fetch_assoc($content_results_db );

    $blog_content = $content_array["Content"];



    mysqli_free_result($content_results_db);
    return $blog_content;


}

/**This function finds and retrieves a blog's access rights from the database. The blog is specified
 * by the blogID parameter.
 *
 * NOTE that the access rights are returned as a string so that they can be displayed straight away.
 * @param $blogID
 * @return string
 */
function find_blog_access_rights($blogID){
    global $conn;


    $query = "SELECT AccessRights FROM blog
            WHERE blog.BlogID = '{$blogID}'";

    $access_results_db = mysqli_query($conn, $query);
    confirm_query($access_results_db );

    //associative array with date posted
    $access_array = mysqli_fetch_assoc($access_results_db );

    $access_rights_int = $access_array["AccessRights"];

    $access_rights_string = convert_access_rights_to_string($access_rights_int);


    mysqli_free_result($access_results_db);
    return $access_rights_string;
}


/**
 *
 * This method ensures the uniqueness of the title. It returns a string output if the title already exits or if the
 * title is left blank. If the title is not in the database, the method outputs an empty string.
 * @param $userid
 * @param $blog_title
 * @return string
 */
function check_blog_title($userid, $blog_title){
    global $conn;

    if(strlen(trim($blog_title))) {

        //make sure that this did not mess up anything
        $blog_title = mysqli_real_escape_string($conn, $blog_title);

        $title_check = "SELECT Title FROM blog
                    WHERE blog.userID = '{$userid}' 
                    AND blog.Title = '{$blog_title}'";
        $title_results_db = mysqli_query($conn, $title_check);
        $title_array = mysqli_fetch_assoc($title_results_db);
        $title = $title_array['Title'];


        if ($title === $blog_title) {
            //if the blog post already exists
            $output = "You already have a blog with this title, please change the title";
            return $output;
        }

        //nothing to output if checks are successful
        $output="";
        return $output;
    }else{
        //in case there is no blog title.
        $output= "Please provide a title for the blog.";
        return $output;
    }


}

/**
 * This function validates a blog post. It calls the check_blog_title($userid, $blog_title) function to double-check
 * that the title input is valid. It returns an output if there are any errors. if no errors, the $output variable is an
 * empty string.
 * @param $userid
 * @param $blog_title
 * @param $blog_content
 * @return string
 */
function validate_blog_post($userid, $blog_title, $blog_content){


    if(strlen(trim($blog_content))){

        $output = check_blog_title($userid, $blog_title);

        return $output;

    }else{
        //if empty
        $output = "Blog post failed. Post content cannot be empty.";
        return $output;
    }
}


/**
 * This function takes the current userid, the blog title and content as well as access rights and inserts
 * this information in the blog table of the database.
 * @param $userid
 * @param $blog_title
 * @param $blog_content
 * @param $access_rights
 */
function insert_blog_post($userid, $blog_title, $blog_content, $access_rights){
    global $conn;

    $post_time = date('Y-m-d H:i:s');

    $blog_title = mysqli_real_escape_string($conn, $blog_title);

    $blog_content = mysqli_real_escape_string($conn, $blog_content);



    $query = "INSERT INTO blog (UserID, DatePosted, Title, Content, AccessRights)
        VALUES ('{$userid}',  '{$post_time}', '{$blog_title}', '{$blog_content}', '{$access_rights}')";

    $result = mysqli_query($conn, $query);


    if ($result) {
        echo "Blog Posted";


    } else {
        echo "Failed to post blog.";
    }

}


/**
 * This function takes the edited content of a blog an updates the record in the database for the specified BlogID.
 *
 * @param $blogID
 * @param $updated_blog_content
 */
function update_blog_content($blogID, $updated_blog_content){

    global $conn;

    $updated_blog_content = mysqli_real_escape_string($conn, $updated_blog_content);

    $query = "UPDATE blog SET Content='{$updated_blog_content}'
    WHERE BlogID='{$blogID}'";

    $result = mysqli_query($conn, $query);

    if($result){
        echo "blog content was successfully updated";

    }else{
        echo "failed to update the record";
    }

}

/**
 * This function takes the updated string version of the access rights and transforms this into an integer value
 * by calling the convert_access_rights_to_int($updated_access_rights) function.
 * The record is then updated in the database for the specified blog id
 * @param $blogID
 * @param $updated_access_rights
 */
function update_blog_access_rights($blogID, $updated_access_rights){
    global $conn;



    $updated_access_rights_int = convert_access_rights_to_int($updated_access_rights);


    $query = "UPDATE blog SET AccessRights='{$updated_access_rights_int}'
    WHERE BlogID='{$blogID}'";


    $result = mysqli_query($conn, $query);

    if($result){
        echo "access rights were successfully updated";

    }else{
        echo "failed to update the record";
    }
}


/**This function deletes a blog from the blog table based on the blogID.
 * @param $blogID
 */
function delete_blog_post($blogID){
    global $conn;



    $query = "DELETE FROM blog WHERE BlogID='{$blogID}'";
    $result = mysqli_query($conn, $query);

    if($result){
        echo "Blog was successfully deleted";

    }else{
        echo "failed to delete the record";
    }
}


//*****************************************************************************************
//BLOG COMMENTS related functions
/**
 * This methods retrieve's all of a blog's comments from the database and returns the result set so that
 * each row of results may be rendered in the DOM. Note that the results
 * of this method need to be released after use.
 *
 * Attributes retrieved: BlogCommentID, CommenterUserID, DatePosted, Content.
 *
 * @param $blogID
 * @return bool|mysqli_result
 */
function find_blog_comments($blogID){
    global $conn;

    $query = "SELECT BlogCommentID, CommenterUserID, DatePosted, Content
              FROM blog_comment
              WHERE BlogID = '{$blogID}'
              ORDER BY DatePosted ASC";

    $comment_results_db = mysqli_query($conn, $query);
    confirm_query($comment_results_db);

    return $comment_results_db;
}

/**
 * This function validates the comment input and returns a boolean. True if the validation is successful, false otherwise
 *
 * @param $comment_content
 * @return bool
 */
function validate_comment_input($comment_content)
{
    $check = false;

    // Check if post is blank
    if (strlen(trim($comment_content))) {

        $check = true;
        return $check;



    } else {
        $check=false;
        echo "Blog post cannot be empty";
        return $check;
    }

}

/**
 * This function adds a comment to the blog_comment table of database. The blog to which the comment should be added is specified
 * by the blogID and the commenter user is also passed as an argument.
 * @param $blogID
 * @param $userid - the user commenting
 * @param $comment_content
 */
function insert_comment($blogID, $commenter_userid, $comment_content){
    global $conn;

    $comment_content = mysqli_real_escape_string($conn, $comment_content);

    $post_time = date('Y-m-d H:i:s');
    // Enter post into DB

    $query = "INSERT INTO blog_comment (BlogID, CommenterUserID, DatePosted, Content)
        VALUES ('{$blogID}', '{$commenter_userid}', '{$post_time}', '{$comment_content}')";

    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "Comment Posted";


    } else {
        echo "The comment could not be posted.";
    }
}

/**
 * This function deletes a comment from the blog_comment table based on the commentID.
 * @param $commentID
 */
function delete_blog_comment($commentID){
    global $conn;



    $query = "DELETE FROM blog_comment WHERE BlogCommentID='{$commentID}'";
    $result = mysqli_query($conn, $query);

    if($result){
        echo "comment was successfully deleted";

    }else{
        echo "failed to delete the record";
    }
}


//saved this here in case we need it later
//function find_commentID($commenter_userID,$comment_date, $comment_content ){
//    global $conn;
//
//    $query = "SELECT BlogCommentID FROM blog_commment
//            WHERE CommenterUserID = '{$commenter_userID}' AND DatePosted = '{$comment_date}' AND Content='{$comment_content}'
//            LIMIT 1";
//
//
//
//
//    //getting the results from the db
//    $comment_results_db = mysqli_query($conn, $query);
//    confirm_query($comment_results_db);
//
//    //associative array with blogID
//    $commentID_array = mysqli_fetch_assoc($comment_results_db);
//
//    //extracting the numericalID
//    $commentID = $commentID_array["BlogCommentID"];
//
//
//    mysqli_free_result($comment_results_db);
//
//    return $commentID;
//}



?>
