<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php"); ?>
<?php require_once("../server/functions_messages.php"); ?>
<?php require_once("../server/db_connection.php"); ?>
<?php $page_title = "Message Outbox" ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

<?php $userid = $_SESSION['UserID']; ?>

<!-- The Jumbotron of the website -->
<section class="jumbotron jumbotron-messages">
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
                        <tr>
                            <th width="40">#</th>
                            <th width="450">Title</th>
                            <th width="250">Receiver</th>
                            <th width="300">Date</th>
                        </tr>
                        <?php
                        $result = check_all_outbox($userid);
                        $count = mysqli_num_rows($result);
                        $msg_counter = 1;
                        while ($rows = mysqli_fetch_array($result)) { ?>
                            <tr>
                                <td width="40"><?php echo $msg_counter++; ?></td>
                                <td width="450"><a href="message_read_outbox.php?out=<?php echo $rows['MessageID']; ?>">
                                        <?php echo $rows['Title']; ?> </a>
                                </td>
                                <td width="250"><?php echo $rows['FirstName'] . " " . $rows['LastName']; ?></td>
                                <?php
                                $date_format = strtotime($rows['TimeSent']);
                                $date_final = date("D, jS F Y, H:i", $date_format);
                                ?>
                                <td width="300"><?php echo $date_final; ?></td>
                            </tr>
                        <?php } ?>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-1"></div>
</div>
<hr/>
<?php include("../includes/footer.php"); ?>
