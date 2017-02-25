<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php"); ?>
<?php require_once("../server/db_connection.php"); ?>
<?php require_once("../server/validations_register.php"); ?>
<?php $page_title = "Registration" ?>
<?php include("../includes/header.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" >
    <link rel="stylesheet" type="text/css" href="../styles/css/login.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<section class="jumbotron">
    <div class="container">
        <div class="row text-center">
            <h1> Register </h1>
            <?php
            echo message();
            echo $message;
            ?></div>
    </div>
</section>
<div class="container">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <form action="register_form.php" method="post">
                    <div class="form-group">
                        <label for="email_address">Username</label>
                        <input type="email" class="form-control" id="email_address" name="email" value="" placeholder="Username@mail.com">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" value="" placeholder="Password" aria-describedby="password_helper" required>
                        <small id="password_helper" class="form-text text-muted">min 8 characters!</small>
                    </div>
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="first_name" value="" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="last_name" value="" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="date_of_birth" value="" placeholder="<?php echo date("d/m/Y");?>">
                    </div>
                    <div class="form-group">
                        <input type="radio" name="gender" value="male"/> Male    <input type="radio" name="gender" value="female" checked/> Female<br/>
                    </div>
                    <br/>
                    <input type="submit" name="register" value="Register" class="btn btn-primary"/>
                </form>
                <br/>
                <a href="login.php">Back</a>
                <?php include("../includes/footer.php"); ?>
            </div>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
</section>
</body>
</html>