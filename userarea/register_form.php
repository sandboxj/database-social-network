<?php require_once("../server/sessions.php");?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/validations_register.php");?>
<?php $page_title="Registration"?>
<?php include("../includes/header.php"); ?>

        <section class="jumbotron register-jumbo">
    <div class="container">
        <div class="row text-center">
            <h1> Register </h1>
           </div>
    </div>
</section>

<?php
if ($check == true) { ?>
    <div class="alert alert-danger">  <?php
        echo message();
        echo $message;
        ?></div>
<?php } ?>

<div class="container">
    <form action="register_form.php" method="post">
    <div class="row center-block">
<!--        EMPTY COLUMN TO CENTER THE OTHERS-->
        <div class="col-md-2"></div>
    <div class="col-md-4 col-centered">
        <div class="panel panel-default">
            <div class="panel-body">
                <h4>Personal Details:</h4>
                <div class="form-group required">
                    <label class="control-label" for="firstName">First Name </label>
                    <input type="text" class="form-control" id="firstName" name="first_name" value="<?php echo $first_name?>" placeholder="">
                </div>
                <div class="form-group required">
                    <label class="control-label" for="lastName">Last Name </label>
                    <input type="text" class="form-control" id="lastName" name="last_name" value="<?php echo $last_name?>" placeholder="">
                </div>

                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <input type="radio" id="gender" name="gender" value="male" <?php echo ($ismale) ? "checked" : "" ?>/> Male
                    <input type="radio" id="gender" name="gender" value="female" <?php echo (!$ismale) ? "checked" : "" ?>/> Female<br/>
                </div>
                <div class="form-group required">
                    <label class="control-label" for="dob">Date of Birth </label>
                    <input type="date" class="form-control" id="dob" name="date_of_birth" value="<?php echo $date_of_birth?>" placeholder="<?php echo date("Y-m-d");?>">
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" class="form-control" id="location" name="location" value="<?php echo $location?>" placeholder="">
                </div>
                <div class="form-group">
                    <label for="phonenum">Phone Number</label>
                    <input type="tel" class="form-control" id="phonenum" name="phone_number" value="<?php echo $phone_number?>" placeholder="">
                </div>
                <div class="form-group">
                    <label for="interest-label">Interests</label>
                <select name="interests" class="form-control" id="interest-label">
                    <option value="Politics" <?php echo ($interest=='Politics') ? "selected" : "" ?>>Politics</option>
                    <option value="Music" <?php echo ($interest=='Music') ? "selected" : "" ?>>Music</option>
                    <option value="Database Systems" <?php echo ($interest=='Database Systems') ? "selected" : "" ?>>Database Systems</option>
                    <option value="Food" <?php echo ($interest=='Food') ? "selected" : "" ?>>Food</option>
                    <option value="Philosophy" <?php echo ($interest=='Philosophy') ? "selected" : "" ?>>Philosophy</option>
                    <option value="Movies" <?php echo ($interest=='Movies') ? "selected" : "" ?>>Movies</option>
                    <option value="Sports" <?php echo ($interest=='Sports') ? "selected" : "" ?>>Sports</option>
                    <option value="Travelling" <?php echo ($interest=='Travelling') ? "selected" : "" ?>>Travelling</option>
                    <option value="Gaming" <?php echo ($interest=='Gaming') ? "selected" : "" ?>>Gaming</option>
                    <option value="Reading" <?php echo ($interest=='Reading') ? "selected" : "" ?>>Reading</option>
                </select>
                </div>
            </div>
        </div>
    </div>
        <div class="col-md-4 col-centered">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4>Login Credentials:</h4>

                    <div class="form-group required">
                        <label class="control-label" for="email_address">E-mail </label>
                        <input type="email" class="form-control" id="email_address" name="email" value="<?php echo $user_email?>" placeholder="Username@mail.com">
                    </div>
                    <div class="form-group required">
                        <label class="control-label" for="password">Password </label>
                        <input type="password" class="form-control" id="password" name="password" value="" placeholder="Password" aria-describedby="password_helper" required>
                    </div>
                    <div class="form-group required">
                        <label class="control-label" for="password-confirm">Confirm Password </label>
                        <input type="password" class="form-control" id="password-confirm" name="password_confirm" value="" placeholder="Confirm Password" aria-describedby="password_confirm_helper" required>
                        <small id="password_confirm_helper" class="form-text text-muted">Please re-enter your password.</small>
                    </div>

                    <div>
                        <label style="color: red"> * Required Fields</label>
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

