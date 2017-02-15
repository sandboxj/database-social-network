<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php"); ?>
<?php require_once("../server/db_connection.php"); ?>
<?php require_once("../server/validations_login.php"); ?>
<?php $page_title = "Login" ?>
<?php include("../includes/header.php"); ?>
<?php include("../includes/footer.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<h2>Login</h2>
<?php
echo message();
echo $message;
?><br/>
<body>
<div class="container">
    <div class="col-md-4"></div>
    <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form action="login.php" method="post">
                        <div class="form-group">
                            <!--Escape special chars on userinput-->
                            <label>Username</label>
                            <input type="text" name="email" value="" placeholder="E-Mail">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" value="" placeholder="Password">
                        </div>
                            <br/>
                            <input type="submit" name="login" value="Login" class="btn btn-primary"/>

                    </form>
                    <br/>

                    <?php $link_page = "register_form.php"; ?>
                    <?php $link_text = rawurldecode($link_page); ?>
                    <a href="<?php echo htmlspecialchars($link_text); ?>">Register</a>
                </div>
            </div>
    </div>
    <div class="col-md-4"></div>
</div>
</body>
</html>