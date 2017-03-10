<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php"); ?>
<?php require_once("../server/messages_functions.php"); ?>
<?php require_once("../server/db_connection.php"); ?>
<?php $page_title = "Message Outbox" ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

<?php $userid = $_SESSION['UserID'];?>

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
                            class="badge"><?php echo ($newMessages = check_new_mail_friends($userid) + check_new_mail_circles($userid) )?></span></a></li>
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
            <table class="table" style="width: 100%">
                <form name="form1" method="post" action="message_outbox.php">
                    <tr>
                        <th width="41">#</th>
                        <th width="490">Title</th>
                        <th width="255">Receiver</th>
                        <th width="255">Date</th>
                    </tr>
                    <?php
                    $result = check_all_outbox($userid);
                    $count = mysqli_num_rows($result);
                    $msg_counter = 1;
                    while ($rows = mysqli_fetch_array($result)) { ?>
                        <tr>
                            <td width="41"><?php echo $msg_counter++; ?></td>
                            <td width="490"><a href="message_read_outbox.php?out=<?php echo $rows['MessageID']; ?>">
                                    <?php echo $rows['Title']; ?> </a>
                            </td>
                            <td width="255"><?php if ($rows['FirstName'] == $rows['LastName']) {
                                    echo $rows['FirstName'];
                                } else {
                                    echo $rows['FirstName']." ".$rows['FirstName'];
                                }
                                ; ?></td>
                            <?php
                            $date_format = strtotime($rows['TimeSent']);
                            $date_final = date("D, jS F Y, H:i", $date_format);
                            ?>
                            <td width="255"><?php echo $date_final; ?></td>
                        </tr>
                    <?php } ?>
                </form>
            </table>
        </div>
    </div>
</div>
<div class="col-md-3"></div>

</section>
</html>
<?php include("../includes/footer.php"); ?>
