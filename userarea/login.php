<<<<<<< HEAD
<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php"); ?>
<?php require_once("../server/db_connection.php"); ?>
<?php require_once("../server/validations_login.php"); ?>
<?php $page_title = "Login" ?>
<?php include("../includes/header.php"); ?>
<?php include("../includes/footer.php"); ?>

<section class="jumbotron">
    <div class="container">
        <div class="row text-center">
            <h1> Login </h1>
            <?php
            echo message();
            echo $message;
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> e976a29... addressed comments of last merge, uncached image of updated profile page, reorganized css, session fix outstanding
            ?></div>
        </div>
</section>
    <div class="container">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form action="login.php" method="post">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="email" value="" placeholder="E-Mail">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" value="" placeholder="Password">
                        </div>
                        <br/>
                        <input type="submit" name="login" value="Login" class="btn btn-primary"/>
<<<<<<< HEAD
=======
        ?><br />        
        <form action="login.php" method="post">
            <!--Escape special chars on userinput-->
            Email: <input type="text" name="email" value="" /><br />
            Password: <input type="password" name="password" value="" /><br />
            <br />
            <input type="submit" name="login" value="Login" />
        </form>
        <br />
        
        <?php $link_page = "register_form.php"; ?>
        <?php $link_text = rawurldecode($link_page); ?>
        <a href="<?php echo htmlspecialchars($link_text);?>">Register</a>
>>>>>>> d303cd2... connected to azure, changed login to use email instead, created skeleton
=======
>>>>>>> e976a29... addressed comments of last merge, uncached image of updated profile page, reorganized css, session fix outstanding

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
</body>
</html>
