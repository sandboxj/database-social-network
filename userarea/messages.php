<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php"); ?>
<?php require_once("../server/messages_functions.php"); ?>
<?php require_once("../server/db_connection.php"); ?>
<?php $page_title = "Messages" ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>


<?php
$current_userid = $_SESSION['UserID'];
$message = "";
$options1 = "";
$options2 = "";
$message_title = "";
$message_content = "";
$result1 = search_circles($current_userid);
$result2 = search_recipient($current_userid);

while ($row = mysqli_fetch_array($result1)) {

    $CircleID = "C" . $row['CircleID'];
    $CircleTitle = $row['CircleTitle'];
    $options1 .= "<option value=$CircleID>" . $CircleTitle . "</option>";
}

while ($row = mysqli_fetch_array($result2)) {

    $UserID = $row['UserID'];
    $FirstName = $row['FirstName'];
    $LastName = $row['LastName'];

    if ($UserID == $_SESSION['UserID']) {
        // Do nothing
    } else {
        $UserID = "U" . $row['UserID'];
        $options2 .= "<option value=$UserID>" . $FirstName . " " . $LastName . "</option>";
    }
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
                            class="badge"><?php echo($newMessages = check_new_mail_friends($current_userid) + check_new_mail_circles($current_userid)) ?></span></a>
            </li>
            <li role="presentation"><a href="message_outbox.php">Outbox</a></li>
            <li role="presentation" class="active"><a href="messages.php">New Message</a></li>
    </div>
    <div class="col-sm-1">
    </div>
</div>

<!-- This section is for the Message area-->
<div class="container">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
        <div class="panel panel-primary">
            <div class="panel-heading"></div>
            <div class="panel-body">
                <table class="col-md-10">
                    <form name="form_message" method="post" action="messages.php">
                        <tr>
                            <td>
                                <label class="message_label" for="search">Recipient:</label>
                            </td>
                            <td>
                                <select class="form-control" name="to_user" id="to_user">
                                    <option value=0>
                                    <option disabled><b>-------Circles-------</b></option>
                                    <?php echo $options1; ?>
                                    <option disabled><b>-------Friends-------</b></option>
                                    <?php echo $options2; ?>
                                    </option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td height="10"></td>
                        </tr>
                        <tr>
                            <td><label class="message_label" for="search">Title:</label></td>
                            <td><input type="text" class="form-control" id="title" name="title" value=""
                                       required<?php echo $message_title ?>"
                                placeholder="Subject">
                            </td>
                        </tr>
                        <tr>
                            <td height="10"></td>
                        </tr>
                        <tr>
                            <td align="top"><label class="message_label" for="search">Message:</label></td>
                            <td><textarea class="form-control" contenteditable="true" id="message_content_field"
                                          rows="5" style="width: 100%" aria-describedby="message_helper"
                                          name="message_content" required<?php echo htmlentities($message_content)?>"></textarea>
                                <small id="message_helper" class="form-text text-muted">Max. 2500 Characters</small>
                            </td>

                        </tr>
                        <tr><br>
                            <td height="10"></td>
                            <td>
                                <input type="submit" name="send" value="Send Message" class="btn btn-primary"/><br>
                                <?php
                                require_once("../server/validation_message.php");
                                ?>
                            </td>
                        </tr>
                    </form>
                </table>
            </div>
            <?php
            if ($check == false) { ?>
                <div class="alert alert-danger"><?php echo "$message" ?></div>
            <?php };
            if ($check2 == true) { ?>
                <div class="alert alert-success"><?php echo "$message" ?></div>
            <?php }; ?>
        </div>
    </div>
    <div class="col-sm-1"></div>
</div>
<hr/>
<a href="logout.php">Logout</a>
<?php include("../includes/footer.php"); ?>
