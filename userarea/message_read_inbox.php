<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php"); ?>
<?php require_once("../server/messages_functions.php"); ?>
<?php require_once("../server/db_connection.php"); ?>
<?php $page_title = "Message Inbox" ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>


<?php

if (!$_GET['in']) {
    echo "Internal error! Please go back to the inbox!";
    $pageid2 = 0;
} else {
    $pageid2 = preg_replace("[^0-9]", "", $_GET['in']);
}


$userid = $_SESSION['UserID'];
$query = retrieve_message_inbox($pageid2, $userid);

$check = true;
$check2 = false;

while ($row = mysqli_fetch_array($query)) {
    $Iid = $row['MessageID'];
    $Ititle = $row['Title'];
    $Icontent = $row['Content'];
    $Istatus = $row['Status'];
    $Itimesent = strtotime($row['TimeSent']);

    // Changing date format.
    $date_final = date("D, jS F Y, H:i", $Itimesent);

    $Isenderid = $row['SenderUserID'];
    $Ireceiverid = $row['ReceiverID'];
    $Ifirstname = $row['FirstName'];
    $Ilastname = $row['LastName'];
}

update_status($pageid2);


?>


<!-- The Jumbotron of the website -->
<section class="jumbotron">
    <div class="container">
        <div class="row text-center">
            <h1> Messages </h1>
        </div>
    </div>
</section>

<!-- This section includes the navigation-->
<div class="container">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
        <ul class="nav nav-pills nav-justified">
            <li role="presentation" class="active"><a href="message_inbox.php">Inbox <span
                            class="badge"><?php echo($newMessages = check_new_mail_friends($userid) + check_new_mail_circles($userid)) ?></span></a>
            </li>
            <li role="presentation"><a href="message_outbox.php">Outbox</a></li>
            <li role="presentation"><a href="messages.php">New Message</a></li>
    </div>
    <div class="col-sm-2"></div>
</div>


<!-- This section is for the actual chat room / Message area-->
<div class="col-sm-2"></div>
<div class="col-sm-8">
    <div class="panel panel-primary">
        <div class="panel-heading"></div>
        <div class="panel-body">
            <div class="col-sm-2">
                <label class="message_label" for="message_from">From:</label><br>
                <label class="message_label" for="message_title">Subject:</label><br>
                <label class="message_label" for="message_content">Message:</label><br>
            </div>
            <div class="col-sm-6">
                <p id="message_from"><?php print $Ifirstname . " " . $Ilastname ?></p>
                <p id="message_title"><?php print $Ititle ?></p>
                <p id="message_content"><?php print $Icontent ?></p>
            </div>
            <div class="col-sm-4">
                <p class="message_date"><?php print $date_final ?></p><br>
            </div>
            <div class="col-sm-8">
                <td><br></td>
                <td></td>
                <form name="form1" method="post">
                    <td align="top"><label class="message_label" for="message_reply">Reply:</label></td>
                    <td><textarea class="form-control" contenteditable="true" id="reply_content" rows="5"
                                  style="width: 100%" aria-describedby="message_helper"
                                  name="reply_content" required></textarea>
                        <small id="message_helper" class="form-text text-muted">Max. 2500 Characters</small>
                    </td>
                    <br>
                    <input type="submit" name="send_reply" value="Send Message" class="btn btn-primary"/><br>

                    <?php

                    $reply_title = "RE: ".$Ititle;
                    $reply_receiver = $Isenderid;
                    if(isset($_POST['send_reply'])) {
                        if (strlen(trim($_POST['reply_content']))) {
                            if (strlen(trim($_POST['reply_content'])) < 2500) {
                                echo "Test working";
                                echo $reply_content = $_POST['reply_content'];
                                $receiver_type = 1;
                                send_reply($userid, $reply_title, $reply_content, $receiver_type, $reply_receiver);
                                $check2 = true;
                                $message = "Message was successfully sent!";
                            } else {
                                $check1 = false;
                                $message = "Message was not sent!";
                            }
                        } else {
                            $check1 = false;
                            $message = "Message was not sent!";
                        }
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>
    <?php
    if ($check == false) { ?>
        <div class="alert alert-danger"><?php echo "$message" ?></div>
    <?php };
    if ($check2 == true) { ?>
        <div class="alert alert-success"><?php echo "$message" ?></div>
    <?php };?>
</div>
<div class="col-md-3"></div>


</section>
<a href="logout.php">Logout</a>
</html>
<?php include("../includes/footer.php"); ?>
