<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php"); ?>
<?php require_once("../server/db_connection.php"); ?>
<?php require_once("../server/validations_login.php"); ?>
<?php $page_title = "Login" ?>
<?php include("../includes/header.php"); ?>

<section class="jumbotron login-jumbotron">
    <div class="container">
        <div class="row text-center">
            <h1> Login </h1>
            <?php
            echo message();
            echo $message;
            ?></div>
        </div>
</section>
    <div class="container">
        <div class="col-md-4">
            <img class="img-responsive img-circle"  src="img/SoShallNetwork_Logo.png"/>
        </div>
        <div class="col-md-4 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form action="login.php" method="post">
                        <div class="form-group">
                            <label for="email_address">E-mail</label>
                            <input type="text" class="form-control" id="email_address" name="email" value="" placeholder="Username@mail.com">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" value="" placeholder="Password" aria-describedby="password_helper">
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
</section>
<?php include("../includes/footer.php"); ?>
