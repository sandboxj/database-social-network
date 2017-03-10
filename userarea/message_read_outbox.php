<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php"); ?>
<?php require_once("../server/messages_functions.php"); ?>
<?php require_once("../server/db_connection.php"); ?>
<?php $page_title = "Message Inbox" ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>


<?php

if (!$_GET['out']) {
    $pageid2 = '1';
} else {
    $pageid2 = preg_replace("[^0-9]", "", $_GET['out']);
}

echo $pageid2;
$query = retrieve_message_outbox($pageid2);


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
    $Ireceivertype = $row['ReceiverType'];
    $Ifirstname = $row['FirstName'];
    $Ilastname = $row['LastName'];
    $IcircleTitle = $row['CircleTitle'];
}

if ($Ireceivertype == 0) {
    $message_receveiver = $IcircleTitle;
} else {
    $message_receveiver = $Ifirstname."".$Ilastname;
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
            <li role="presentation"><a href="message_inbox.php">Inbox <span
                            class="badge"><?php echo($newMessages = check_new_mail_friends() + check_new_mail_circles()) ?></span></a>
            </li>
            <li role="presentation" class="active"><a href="message_outbox.php">Outbox</a></li>
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
                <label class="message_label" for="message_from">To:</label><br>
                <label class="message_label" for="message_title">Subject:</label><br>
                <label class="message_label" for="message_content">Message:</label><br>
            </div>
            <div class="col-sm-6">
                <p id="message_from"><?php print $message_receveiver?></p>
                <p id="message_title"><?php print $Ititle ?></p>
                <p id="message_content"><?php print $Icontent ?></p>
            </div>
            <div class="col-sm-4">
                <p class="message_date"><?php print $date_final?></p><br>
            </div>

        </div>
    </div>
</div>
<div class="col-md-3"></div>

</section>
</html>
<?php include("../includes/footer.php"); ?>
