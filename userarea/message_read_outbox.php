<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php"); ?>
<?php require_once("../server/functions_messages.php"); ?>
<?php require_once("../server/db_connection.php"); ?>
<?php $page_title = "Message Outbox" ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>


<?php

if (!$_GET['out']) {
    $pageid2 = '1';
} else {
    $pageid2 = preg_replace("[^0-9]", "", $_GET['out']);
}
$userid = $_SESSION['UserID'];

$query = retrieve_message_outbox($pageid2, $userid);


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
    $message_receveiver = $Ifirstname . "" . $Ilastname;
}

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
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
        <ul class="nav nav-pills nav-justified">
            <li role="presentation"><a href="message_inbox.php">Inbox <span
                            class="badge"><?php echo($newMessages = check_new_mail_friends($userid) + check_new_mail_circles($userid)) ?></span></a>
            </li>
            <li role="presentation" class="active"><a href="message_outbox.php">Outbox</a></li>
            <li role="presentation"><a href="messages.php">New Message</a></li>
    </div>
    <div class="col-sm-1"></div>
</div>


<!-- This section is for the actual chat room / Message area-->
<div class="container">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
        <div class="panel panel-primary">
            <div class="panel-heading"></div>
            <div class="panel-body">
                <table class="table" style="width: 100%">
                    <tr class="noBorder">
                        <td class="noBorder" width="200">Sent To:</td>
                        <td width="500"><?php print $message_receveiver ?></td>
                        <td width="280"><?php print $date_final ?></td>
                    </tr>
                    <tr class="noBorder">
                        <td width="200">Subject:</td>
                        <td width="500"><?php print $Ititle ?></td>
                    </tr>
                    <tr class="noBorder">
                        <td width="200">Message:</td>
                        <td width="500"><?php
                            echo nl2br(htmlentities(strip_tags($Icontent)));?>
                            </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-1"></div>
</div>
<div class="container">

</div>
<hr/>
<?php include("../includes/footer.php"); ?>
