<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/db_connection.php");?>
<?php $page_title="{$_SESSION["FirstName"]} {$_SESSION["LastName"]}'s Notifications"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php");

$new_blog_comments_query = "SELECT u.UserID, u.FirstName, u.LastName, bc.Content, b.Title, bc.DatePosted FROM blog_comment bc, blog b, user u
							WHERE bc.BlogID= b.BlogID
							AND bc.CommenterUserID = u.UserID
							AND b.UserID = '{$_SESSION["UserID"]}'
              AND bc.CommenterUserID != '{$_SESSION["UserID"]}'
							AND bc.Seen = '0'";
$new_photo_comments_query = "SELECT u.UserID, u.FirstName, u.LastName, pcm.Content, pcm.DatePosted, pc.CollectionID, p.PhotoID FROM photo_comment pcm, photo_collection pc, photo p, user u
							 WHERE pcm.PhotoID = p.PhotoID
							 AND p.CollectionID = pc.CollectionID
							 AND pcm.CommenterUserID = u.UserID
							 AND pc.UserID = '{$_SESSION["UserID"]}'
               AND pcm.CommenterUserID != '{$_SESSION["UserID"]}'
							 AND pcm.Seen = '0'";
$new_friend_requests_query = "SELECT u.UserID, u.FirstName, u.LastName, f.Date FROM friendship f, user u
							  WHERE f.User1ID = u.UserID
							  AND f.User2ID = '{$_SESSION["UserID"]}'
							  AND f.Status = '0'";
$new_messages_query = "SELECT u.UserID, u.FirstName, u.LastName, m.MessageID, m.TimeSent FROM message m, user u
					   WHERE m.SenderUserID = u.UserID
					   AND m.ReceiverType = '1'
					   AND m.ReceiverID = '{$_SESSION["UserID"]}'
					   AND m.Status = '0'";

$new_blog_comments_result = mysqli_query($conn, $new_blog_comments_query);
$new_photo_comments_result = mysqli_query($conn, $new_photo_comments_query);
$new_friend_requests_result = mysqli_query($conn, $new_friend_requests_query);
$new_messages_result = mysqli_query($conn, $new_messages_query);
?>
<h4>Blog Comments</h4>
<?php
if (mysqli_num_rows($new_blog_comments_result)<1) {
    echo ("<p style='font-style: italic'>No new comments.</p>");
} else {
	while ($blog_comment = mysqli_fetch_assoc($new_blog_comments_result)) {
		echo "<a href=\"user_profile.php?id=" . $blog_comment["UserID"] . "\">" . $blog_comment["FirstName"] . " " . $blog_comment["LastName"] . "</a> posted \"" . $blog_comment["Content"] . "\" on <a href=\"blog.php?title=" . $blog_comment["Title"] . "\">'" . $blog_comment["Title"] . "'</a> on " . $blog_comment["DatePosted"] . ".<br/>";
	}
}
?>
<h4>Photo Comments</h4>
<?php
if (mysqli_num_rows($new_photo_comments_result)<1) {
    echo ("<p style='font-style: italic'>No new comments</p>");
} else {
	while ($photo_comment = mysqli_fetch_assoc($new_photo_comments_result)) {
    $single_photo_query = "SELECT * FROM photo p WHERE p.PhotoID = '{$photo_comment["PhotoID"]}'";
    $single_photo_result = mysqli_query($conn, $single_photo_query);
    $single_photo = mysqli_fetch_assoc($single_photo_result);
    $photo = http_build_query($single_photo);
		echo "<a href=\"user_profile.php?id=" . $photo_comment["UserID"] . "\">" . $photo_comment["FirstName"] . " " . $photo_comment["LastName"] . "</a> posted \"" . $photo_comment["Content"] . "\" on your <a href=\"single_photo.php?collection=" . $photo_comment["CollectionID"] . "&" . $photo . "\">photo</a> on " . $photo_comment["DatePosted"] . ".<br/>";
	}
}
?>
<h4>Friend Requests</h4>
<?php
if (mysqli_num_rows($new_friend_requests_result)<1) {
    echo ("<p style='font-style: italic'>No new requests</p>");
} else {
	while ($friend_request = mysqli_fetch_assoc($new_friend_requests_result)) {
		echo "<a href=\"user_profile.php?id=" . $friend_request["UserID"] . "\">" . $friend_request["FirstName"] . " " . $friend_request["LastName"] . "</a> sent you a friend request on " . $friend_request["Date"] . ".<br/>";
	}
}
?>
<h4>Messages</h4>
<?php
if (mysqli_num_rows($new_messages_result)<1) {
    echo ("<p style='font-style: italic'>No new messages.</p>");
} else {
	while ($message = mysqli_fetch_assoc($new_messages_result)) {
		echo "<a href=\"user_profile.php?id=" . $message["UserID"] . "\">" . $message["FirstName"] . " " . $message["LastName"] . "</a> sent you a <a href=\"message_read_inbox.php?in=" . $message["MessageID"] . "\">message</a> on " . $message["TimeSent"] . ".<br/>";
	}
}
?>

<hr />
<?php include("../includes/footer.php"); ?>
