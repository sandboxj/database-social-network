<?php require_once("../server/sessions.php");?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/validations_register.php");?>
<?php $page_title="Registration"?>
<?php include("../includes/header.php"); ?>
        <section class="jumbotron">
    <div class="container">
        <div class="row text-center">
            <h1> Register </h1>
            <?php
            echo message();
            //echo $message;
            ?></div>
    </div>
</section>
<div class="container">
    <form action="register_form.php" method="post">
    <div class="row center-block">
<!--        EMPTY COLUMN TO CENTER THE OTHERS-->
        <div class="col-md-2"></div>
    <div class="col-md-4 col-centered">
        <div class="panel panel-default">
            <div class="panel-body">
                <h4>Personal Details:</h4>
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" class="form-control" id="firstName" name="first_name" value="" placeholder="">
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" class="form-control" id="lastName" name="last_name" value="" placeholder="">
                </div>

                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <input type="radio" id="gender" name="gender" value="male" checked/> Male
                    <input type="radio" id="gender" name="gender" value="female"/> Female<br/>
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="date_of_birth" value="" placeholder="<?php echo date("d/m/Y");?>">
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" class="form-control" id="location" name="location" value="" placeholder="">
                </div>
                <div class="form-group">
                    <label for="phonenum">Phone Number</label>
                    <input type="text" class="form-control" id="phonenum" name="phone_number" value="" placeholder="">
                </div>

            </div>
        </div>
    </div>
        <div class="col-md-4 col-centered">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4>Login Credentials:</h4>

                    <div class="form-group">
                        <label for="email_address">E-mail</label>
                        <input type="email" class="form-control" id="email_address" name="email" value="" placeholder="Username@mail.com">
                    </div>
                  <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" value="" placeholder="Password" aria-describedby="password_helper" required>
                        <small id="password_helper" class="form-text text-muted">min 8 characters!</small>
                    </div>
                    <div class="form-group">
                        <label for="password-confirm">Confirm Password</label>
                        <input type="password" class="form-control" id="password-confirm" name="password_confirm" value="" placeholder="Confirm Password" aria-describedby="password_confirm_helper" required>
                        <small id="password_confirm_helper" class="form-text text-muted">Please re-enter your password.</small>
                    </div>

                    <br/>
                    <input type="submit" name="register" value="Register" class="btn btn-primary"/>

                    <br/><br>
                    <a href="login.php">Back</a>
                </div>
            </div>
        </div>

    </div>
    </form>
</div>
</section>


<?php include("../includes/footer.php"); ?>

