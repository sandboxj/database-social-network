<?php require_once("server/sessions.php");?>
<?php require_once("server/db_connection.php");?>
<?php require_once("server/functions.php");?>
<?php require_once("server/validation_functions.php");?>
<?php require_once("server/validations_login.php");?>
<?php $page_title="Login"?>
<?php include("includes/header.php"); ?>

        <h2>Login</h2>
        <?php
            echo message();
            echo $message;
        ?><br />        
        <form action="login.php" method="post">
            <!--Escape special chars on userinput-->
            Username: <input type="text" name="username" value="" /><br />
            Password: <input type="password" name="password" value="" /><br />
            <br />
            <input type="submit" name="login" value="Login" />
        </form>
        <br />
        
        <?php $link_page = "register_form.php"; ?>
        <?php $link_text = rawurldecode($link_page); ?>
        <a href="<?php echo htmlspecialchars($link_text);?>">Register</a>

<?php include("includes/footer.php"); ?>
