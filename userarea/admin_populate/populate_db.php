<?php
if (isset($_POST["populate_users"])) {
	$no_users = $_POST["num_of_users"];
	while ($no_users > 0) {
		$myLine = rand(1,209407); 
		$file = new SplFileObject('../userarea/admin_populate/names.csv');
		$file->seek($myLine-1);
		$line = $file->current();
		$data = explode(',', $line);
    $myLine = rand(1,10); 
		$file = new SplFileObject('../userarea/admin_populate/cities.csv');
		$file->seek($myLine-1);
		$line = $file->current();
    $trimline = trim($line, " \t\n\r\0\x0B");
		$gender = ($data[0] = "B") ? 1 : 0;
		$first_name = $data[1];
		$last_name = $data[2];
		$email = $first_name . "." . $last_name . "@example.com";
		$email_query = "SELECT Email FROM User
						WHERE Email = '{$email}'";
		$email_check = mysqli_query($conn, $email_query);
		if (mysqli_num_rows($email_check) > 0) {
			$email_exists = 1;
		}
		if (!$email_exists) {
			$no_users--;
			$password = password_encrypt($data[3]);
			$location = $trimline;
			$min = 0;
			$max = 999999999;
			$phone_number = "07" . strval(rand($min, $max));
			$hashed_password = password_encrypt($password);
			$start = strtotime("1 January 2010");
			$timestamp = mt_rand($start, time());
			$date_joined = date("Y-m-d H:i:s", $timestamp);
			$start = strtotime("1 January 1980");
			$end = strtotime("1 January 2001");
			$timestamp = mt_rand($start, $end);
			$dob = date("Y-m-d", $timestamp);
      $interests = ["None", "Politics", "Music", "Database Systems", "Food", "Philosophy", "Movies", "Sports", "Travelling", "Gaming", "Reading"];
      $interest = $interests[rand(0,9)];
      $privacy = rand(0,3);
			print $first_name . " " . $last_name . " " . $email . " " . $date_joined . " " . $dob . " " . $gender . " " . $location . " " . $phone_number . " " . $interest . "<br/>";
			$population_query = "INSERT INTO user (Email, Password, FirstName, LastName, DateJoined, DateOfBirth, Gender, CurrentLocation, PhoneNumber, PrivacySetting, Interest)
								           VALUES ('{$email}', '{$password}', '{$first_name}', '{$last_name}', '{$date_joined}', '{$dob}', '{$gender}', '{$location}', '{$phone_number}', '{$privacy}', '{$interest}')";
			$populate = mysqli_query($conn, $population_query);
		}
	}
}

if (isset($_POST["populate_friendships"])) {
	$no_friends = $_POST["num_of_friends"];
	$users_query = "SELECT * from user
                  WHERE user.UserID NOT LIKE '1'";
	$user_list = mysqli_query($conn, $users_query);
	while ($user = mysqli_fetch_assoc($user_list)) {
		$users[] = $user["UserID"];
		$dates_joined[] = $user["DateJoined"];
	}
	$no_users = count($users);
	for($i = 0; $i < $no_users; $i++) {
		$user_1 = $users[$i];
		$past_users[] = $user_1;
		print $user_1 . "<br/>";
		$friends = [];
		for ($friend_no = 0; $friend_no < rand(0, $no_friends); $friend_no++) {
			$user_2 = $users[rand(0,$no_users-1)];
			if ($user_2 != $user_1) {
				$allgood = 1;
				foreach($past_users as $user) {
					if ($user_2 != $user && $allgood) {
						$allgood = 1;
					} else {
						$allgood = 0;
					}
				}
				if ($allgood) {
					$friends[] = $user_2;
				} else {
					$friends[] = "null";
				}
			}
		}
		$friends_final = array_unique($friends);
		foreach($friends_final as $friend) {
			$isfriend_query = "SELECT * FROM friendship f
							   WHERE (f.User1ID = '{$user_1}' AND f.User2ID = '{$friend}')
							   OR (f.User2ID = '{$user_1}' AND f.User1ID = '{$friend}')";
			$isfriend_result = mysqli_query($conn, $isfriend_query);
			$f = mysqli_fetch_assoc($isfriend_result);
			$friend_join_date_query = "SELECT * from user
									               WHERE user.UserID = '{$friend}'";
			$join_date_result = mysqli_query($conn, $friend_join_date_query);
			$join_date = mysqli_fetch_assoc($join_date_result);
			$friend_join_date = strtotime($join_date["DateJoined"]);
			$isfriend = ($f["User1ID"] == "") ? 0 : 1;
			if (!$isfriend) {
				$user_join_date = strtotime($dates_joined[$i]);
				if ($user_join_date - $friend_join_date > 0) {
					$start = $user_join_date;
				} else {
					$start = $friend_join_date;
				}
				print $friend . " ";
				$timestamp = mt_rand($start, time());
				$date_created = date("Y-m-d H:i:s", $timestamp);
        $statuses = ['1', '0'];
        $status = $statuses[rand(0,1)];
				$friendship_query = "INSERT INTO friendship (User1ID, User2ID, Status, Date)
									 VALUES ('{$user_1}', '{$friend}', '{$status}', '{$date_created}')";
				$friendship = mysqli_query($conn, $friendship_query);
			}
		}
		print "<br/>";
	}
}

if (isset($_POST["populate_circles"])) {
	$no_circles = $_POST["num_of_circles"];
	$users_query = "SELECT * from user
                  WHERE user.UserID NOT LIKE '1'";
	$users = mysqli_query($conn, $users_query);
	while ($user = mysqli_fetch_assoc($users)) {
		$users_list[] = $user["UserID"];
		$dates_joined[] = $user["DateJoined"];
	}
  foreach($users_list as $user) {
    for ($circle = 0; $circle < rand(0, $no_circles); $circle++) {
      $myLine = rand(1,651);
		  $title_file = new SplFileObject('../userarea/admin_populate/blog_titles.csv');
		  $title_file->seek($myLine-1);
		  $title_line = $title_file->current();
		  $title = $title_line;
		  $title_check_query = "SELECT * FROM circle
		            					  WHERE circle.CircleAdminUserID = '{$user}'
				  			            AND circle.CircleTitle = '{$title}'";
		  $title_result = mysqli_query($conn, $title_check_query);
		  $output = mysqli_fetch_assoc($title_result);
		  if ($output["CircleTitle"] != $title) {
        $index = array_search($user, $users_list);
        $start = strtotime($dates_joined[$index]);
			  $timestamp = mt_rand($start, time());
			  $date_created = date("Y-m-d H:i:s", $timestamp);
        $circle_query = "INSERT INTO circle (CircleAdminUserID, DateCreated, CircleTitle, CirclePhotoID)
							           VALUES ('{$user}', '{$date_created}', '{$title}', '1')";
        $circle_result = mysqli_query($conn, $circle_query);
        print $user . " " . $date_created . " " . $title . " " . $no_circles . "<br/>";
      } else {
        $circle--;
      }
    }
  }
}

if (isset($_POST["populate_circle_members"])) {
  $no_circle_members = $_POST["num_of_circle_members"];
  
}

if (isset($_POST["populate_collections"])) {
	$no_collections = $_POST["num_of_collections"];
}

if (isset($_POST["populate_photos"])) {
  $no_photos = $_POST["num_of_photos"];
}

if (isset($_POST["populate_photo_comments"])) {
  $no_photo_comments = $_POST["num_of_photo_comments"];
}

if (isset($_POST["populate_blogs"])) {
	$no_blogs = $_POST["num_of_blogs"];
	$users_query = "SELECT * from user
                  WHERE user.UserID NOT LIKE '1'";
	$users_result = mysqli_query($conn, $users_query);
	while ($user = mysqli_fetch_assoc($users_result)) {
		$users[] = $user["UserID"];
		$dates_joined[] = $user["DateJoined"];
	}
	for ($user = 0; $user < count($users); $user++) {
		for ($blog_no = 0; $blog_no < rand(0,$no_blogs); $blog_no++) {
			$start = strtotime($dates_joined[$user]);
			$timestamp = mt_rand($start, time());
			$date_created = date("Y-m-d H:i:s", $timestamp);
			$myLine = rand(1,651);
			$content_file = new SplFileObject('../userarea/admin_populate/blog_content.csv');
			$title_file = new SplFileObject('../userarea/admin_populate/blog_titles.csv');
			$content_file->seek($myLine-1);
			$title_file->seek($myLine-1);
			$content_line = $content_file->current();
			$title_line = $title_file->current();
			$title = $title_line;
			$title_check_query = "SELECT * FROM blog
								  WHERE blog.UserID = '{$users[$user]}'
								  AND blog.Title = '{$title}'";
			$title_result = mysqli_query($conn, $title_check_query);
			$output = mysqli_fetch_assoc($title_result);
			if ($output["Title"] != $title) {
				$content = $content_line;
				$access_rights = rand(0,4);
				$user_circles = [];
				if ($access_rights == 4) {
					$circles_query = "SELECT * FROM circle c
									          WHERE c.CircleAdminUserID = '{$users[$user]}'";
					$circles_result = mysqli_query($conn, $circles_query);
					while ($circle = mysqli_fetch_assoc($circles_result)) {
						$user_circles[] = $circle["CircleID"];
					}
					if (count($user_circles) != 0) {
						$circle = $user_circles[rand(0,count($user_circles)-1)];
					}
				}
			print $users[$user] . " x ";
			print $date_created . " x ";
			print $title . " x ";
			print $content . " x ";
			print $access_rights . " x ";
			if ($access_rights == 4 && count($user_circles)) {
				print $circle . " x ";
			}
			print "<br/>";
			if ($access_rights == 4 && count($user_circles) != 0) {
				$blog_query = "INSERT INTO blog (UserID, DatePosted, Title, Content, AccessRights, Circle)
							         VALUES ('{$users[$user]}', '{$date_created}', '{$title}', '{$content}', '{$access_rights}', '{$circle}')";
				$blog_result = mysqli_query($conn, $blog_query);
			} else {
				$blog_query = "INSERT INTO blog (UserID, DatePosted, Title, Content, AccessRights, Circle)
							         VALUES ('{$users[$user]}', '{$date_created}', '{$title}', '{$content}', '{$access_rights}', null)";
				$blog_result = mysqli_query($conn, $blog_query);
			}
			}
		}
	}
}


if (isset($_POST["populate_blog_comments"])) {
  $no_comments = $_POST["num_of_blog_comments"];
	$users_query = "SELECT * from user
                  WHERE user.UserID NOT LIKE '1'";
	$users = mysqli_query($conn, $users_query);
	while ($user = mysqli_fetch_assoc($users)) {
		$users_list[] = $user["UserID"];
	}
  foreach ($users_list as $user) {
    $blogs_query = "SELECT * from blog b
                    WHERE b.UserID not like '{$user}'";
	  $blogs = mysqli_query($conn, $blogs_query);
    while ($blog = mysqli_fetch_assoc($blogs)) {
		  $blog_user_list[] = $blog["UserID"];
      $blog_id_list[] = $blog["BlogID"];
	  }      
    for ($comment = 0; $comment < rand(0, $no_comments); $comment++) {
      $blog_user = $blog_user_list[rand(0,count($blog_user_list)-1)];
      $user_index = array_search($blog_user, $blog_user_list);
      $friends_query = "SELECT * FROM friendship f
                        WHERE (((f.User1ID = '{$user}' AND f.User2ID = '{$blog_user}')
                        OR (f.User2ID = '{$user}' AND f.User1ID = '{$blog_user}'))
                        AND f.Status = '1')";
      $friends_result = mysqli_query($conn, $friends_query);
      $friend = mysqli_fetch_assoc($friends_result);
      $blog_id = $blog_id_list[$user_index];
      $specific_blog_query = "SELECT * FROM blog b
                              WHERE b.BlogID = '{$blog_id}'";
      $specific_blog_result = mysqli_query($conn, $specific_blog_query);
      $specific_blog = mysqli_fetch_assoc($specific_blog_result);
      if ($friend["User1ID"] != "") {
        if ($specific_blog["AccessRights"] == 1 || $specific_blog["AccessRights"] == 2 || $specific_blog["AccessRights"] == 3) {
          $start = strtotime($specific_blog["DatePosted"]);
			    $timestamp = mt_rand($start, time());
			    $date_created = date("Y-m-d H:i:s", $timestamp);
			    $myLine = rand(1,16);
			    $comment_file = new SplFileObject('../userarea/admin_populate/blog_comments.csv');
			    $comment_file->seek($myLine-1);
			    $comment_line = $comment_file->current();
			    $comment = $comment_line;
			    $seen = rand(0,1);
          $blog_comment_query = "INSERT INTO blog_comment (BlogID, CommenterUserID, DatePosted, Content, Seen)
							                   VALUES ('{$blog_id}', '{$user}', '{$date_created}', '{$comment}', '{$seen}')";
          $blog_comment = mysqli_query($conn, $blog_comment_query);
        } elseif ($specific_blog["AccessRights"] == 4) {
          $circle = $specific_blog["Circle"];
          $circle_member_query = "SELECT * FROM circle_member cm
                                  WHERE cm.CircleID = '{$circle}'
                                  AND cm.MemberUserID = '{$user}'";
          $circle_member_result = mysqli_query($conn, $circle_member_query);
          $circle_member = mysqli_fetch_assoc($circle_member_result);
          if ($circle_member["CircleID"] != "") {
            $start = strtotime($specific_blog["DatePosted"]);
			      $timestamp = mt_rand($start, time());
			      $date_created = date("Y-m-d H:i:s", $timestamp);
			      $myLine = rand(1,16);
			      $comment_file = new SplFileObject('../userarea/admin_populate/blog_comments.csv');
			      $comment_file->seek($myLine-1);
			      $comment_line = $comment_file->current();
			      $comment = $comment_line;
            $blog_comment_query = "INSERT INTO blog_comment (BlogID, CommenterUserID, DatePosted, Content)
					  		                   VALUES ('{$blog_id}', '{$user}', '{$date_created}', '{$comment}')";
            $blog_comment = mysqli_query($conn, $blog_comment_query);
          } else {
            $comment--;
          }
        } else {
          $comment--;
        }
      } else {
        if ($specific_blog["AccessRights"] == 2) {
          $user_friends_query = "SELECT * FROM friendship f
                                 WHERE (f.User1ID = '{$user}'
                                 OR f.User2ID = '{$user}')";
          $user_friends_result = mysqli_query($conn, $user_friends_query);
          while ($user_friend = mysqli_fetch_assoc($user_friends_result)) {
            if ($user_friend["User1ID"] != "") {
              $user_friends[] = ($user_friend["User1ID"] == $user) ? $user_friend["User2ID"] : $user_friend["User1ID"];
            }
	        }
          $friends_of_blog_user_query = "SELECT * FROM friendship f
                                         WHERE (f.User1ID = '{$blog_user}'
                                         OR f.User2ID = '{$blog_user}')";
          $friends_of_blog_user_result = mysqli_query($conn, $friends_of_blog_user_query);
          $is_friend_of_friend = 0;
          while ($friend_of_blog_user = mysqli_fetch_assoc($friends_of_blog_user_result)) {
            if ($friend_of_user["User1ID"] != "" && !$is_friend_of_friend) {
		          $fobu = ($friend_of_blog_user["User1ID"] == $blog_user) ? $friend_of_blog_user["User2ID"] : $friend_of_blog_user["User1ID"];
              $is_friend_of_friend = (array_search($fobu, $user_friends) != 0) ? 1:0;
            }
	        }
          if ($is_friend_of_friend) {
            $start = strtotime($specific_blog["DatePosted"]);
			      $timestamp = mt_rand($start, time());
			      $date_created = date("Y-m-d H:i:s", $timestamp);
			      $myLine = rand(1,16);
			      $comment_file = new SplFileObject('../userarea/admin_populate/blog_comments.csv');
			      $comment_file->seek($myLine-1);
			      $comment_line = $comment_file->current();
			      $comment = $comment_line;
            $blog_comment_query = "INSERT INTO blog_comment (BlogID, CommenterUserID, DatePosted, Content)
					  		                   VALUES ('{$blog_id}', '{$user}', '{$date_created}', '{$comment}')";
            $blog_comment = mysqli_query($conn, $blog_comment_query);
          }
        } elseif ($specific_blog["AccessRights"] == 3) {
          $start = strtotime($specific_blog["DatePosted"]);
			    $timestamp = mt_rand($start, time());
			    $date_created = date("Y-m-d H:i:s", $timestamp);
			    $myLine = rand(1,16);
			    $comment_file = new SplFileObject('../userarea/admin_populate/blog_comments.csv');
			    $comment_file->seek($myLine-1);
			    $comment_line = $comment_file->current();
			    $comment = $comment_line;
          $blog_comment_query = "INSERT INTO blog_comment (BlogID, CommenterUserID, DatePosted, Content)
							                   VALUES ('{$blog_id}', '{$user}', '{$date_created}', '{$comment}')";
          $blog_comment = mysqli_query($conn, $blog_comment_query);
        } else {
          $comment--;
        }
      }  
    }
  }
}

if (isset($_POST["populate_messages"])) {

}

if (isset($_POST["purge"])) {
  $delete_user_query = "DELETE FROM user
                        WHERE user.UserID NOT LIKE '1'";
  $delete_blog_query = "DELETE FROM blog";
  $delete_blog_comment_query = "DELETE FROM blog_comment";
  $delete_circle_query = "DELETE FROM circle";
  $delete_circle_member_query = "DELETE FROM circle_member";
  $delete_do_not_recommend_query = "DELETE FROM do_not_recommend";
  $delete_friendship_query = "DELETE FROM friendship";
  $delete_message_query = "DELETE FROM message";
  $delete_photo_query = "DELETE FROM photo
                         WHERE photo.PhotoID NOT LIKE '1'";
  $delete_photo_collection_query = "DELETE FROM photo_collection
                                    WHERE photo_collection.CollectionID NOT LIKE '1'";
  $delete_photo_comment_query = "DELETE FROM photo_comment";
  
  $reset_user_query = "ALTER TABLE user 
                       AUTO_INCREMENT = 1";
  $reset_blog_query = "ALTER TABLE blog 
                       AUTO_INCREMENT = 1";
  $reset_blog_comment_query = "ALTER TABLE blog_comment 
                               AUTO_INCREMENT = 1";
  $reset_circle_query = "ALTER TABLE circle
                         AUTO_INCREMENT = 1";
  $reset_circle_member_query = "ALTER TABLE circle_member
                                AUTO_INCREMENT = 1";
  $reset_do_not_recommend_query = "ALTER TABLE do_not_recommend
                                   AUTO_INCREMENT = 1";
  $reset_friendship_query = "ALTER TABLE friendship
                             AUTO_INCREMENT = 1";
  $reset_message_query = "ALTER TABLE message
                          AUTO_INCREMENT = 1";
  $reset_photo_query = "ALTER TABLE photo 
                        AUTO_INCREMENT = 1";
  $reset_photo_collection_query = "ALTER TABLE photo_collection
                                   AUTO_INCREMENT = 1";
  $reset_photo_comment_query = "ALTER TABLE photo_comment 
                                AUTO_INCREMENT = 1";
  
  $delete_user_result = mysqli_query($conn, $delete_user_query);
  $delete_blog_result = mysqli_query($conn, $delete_blog_query);
  $delete_blog_comment_result = mysqli_query($conn, $delete_blog_comment_query);  
  $delete_circle_result = mysqli_query($conn, $delete_circle_query);  
  $delete_circle_member_result = mysqli_query($conn, $delete_circle_member_query);  
  $delete_do_not_recommend_result = mysqli_query($conn, $delete_do_not_recommend_query);  
  $delete_friendship_result = mysqli_query($conn, $delete_friendship_query);  
  $delete_message_result = mysqli_query($conn, $delete_message_query);  
  $delete_photo_result = mysqli_query($conn, $delete_photo_query);  
  $delete_photo_collection_result = mysqli_query($conn, $delete_photo_collection_query);  
  $delete_photo_comment_result = mysqli_query($conn, $delete_photo_comment_query);
  
  $reset_user_result = mysqli_query($conn, $reset_user_query);
  $reset_blog_result = mysqli_query($conn, $reset_blog_query);
  $reset_blog_comment_result = mysqli_query($conn, $reset_blog_comment_query);  
  $reset_circle_result = mysqli_query($conn, $reset_circle_query);  
  $reset_circle_member_result = mysqli_query($conn, $reset_circle_member_query);  
  $reset_do_not_recommend_result = mysqli_query($conn, $reset_do_not_recommend_query);  
  $reset_friendship_result = mysqli_query($conn, $reset_friendship_query);  
  $reset_message_result = mysqli_query($conn, $reset_message_query);  
  $reset_photo_result = mysqli_query($conn, $reset_photo_query);  
  $reset_photo_collection_result = mysqli_query($conn, $reset_photo_collection_query);  
  $reset_photo_comment_result = mysqli_query($conn, $reset_photo_comment_query);
}
?>
