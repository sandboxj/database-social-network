<?php
global $conn;
$check = true;
$check2 = false;

if (isset($_POST['send'])) {
    $userid = $_SESSION['UserID'];
    $message_content = $_POST['message_content'];
    $message_title = $_POST['title'];
    $receiver = $_POST['to_user'];

    if (substr($receiver, 0, 1) === "C") {
        $receiver_type = 0;
        $receiver_id = trim($receiver, "C");

    } elseif (substr($receiver, 0, 1) === "U") {
        $receiver_type = 1;
        $receiver_id = trim($receiver, "U");

    } else {
        $receiver_type = 3;
        $receiver_id = null;
        $message = "No receiver selected.";
    }

    if (!$receiver_id == null) {
        if (strlen(trim($message_title))) {
            if (strlen(trim($message_content))) {
                if (strlen(trim($message_content)) < 2500) {
                    // Gather the content
                    $message_title = mysqli_real_escape_string($conn, $message_title);
                    $message_content = mysqli_real_escape_string($conn, $message_content);
                    $date_time = date('Y-m-d H:i:s');

                    //echo "After escaping and trimming we have: TITLE - " .$message_title. " and MESSAGE - " .$message_content;


                    $sql = "INSERT INTO message
                            (SenderUserID, ReceiverType, ReceiverID, Title, Content, TimeSent)
                            VALUES ('{$userid}', '{$receiver_type}', '{$receiver_id}', '{$message_title}', '{$message_content}', '{$date_time}')";

                    $result = mysqli_query($conn, $sql);
                    $message = "Message was sent!";
                    $check = true;
                    $check2 = true;
                } else {
                    $message = "Message too long. Please reduce length!";
                    $check = false;
                    $check2 = false;
                }
            } else {
                $message = "No message content. Please type in a message!";
                $check = false;
                $check2 = false;
            }

        } else {
            $message = "No message title. Please type in a title!";
            $check = false;
            $check2 = false;
        }
    } else {
        $message = "No receiver selected. Please chose one from the list.";
        $check = false;
        $check2 = false;
    }
}

