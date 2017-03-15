<link rel="stylesheet" href="../styles/fonts">
<?php require_once("../server/validation_search.php");?>

<nav class="navbar-container navbar-color">

    <div class="row text-center">
        <div class="col-md-12">
            <ul class="nav nav-pills">
                <li role="presentation"><a class="so-shall-logo"><i class="glyphicon glyphicon-globe"></i> SoShallNetwork</a></li>
                <li role="presentation" class="pull-right"><a href="logout.php">Logout <i class="glyphicon glyphicon-log-out" aria-hidden="true"></i></a></li>
            </ul>

        </div>
    </div>
        <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills">
                <li role="presentation">
                    <a>Logged in as <?php echo "{$_SESSION['FirstName']} {$_SESSION['LastName']}"?></a></li>
            </ul>
        </div>
        <div class='col-md-9'>
    
    <ul class="nav nav-pills">
        
        <li role="presentation" <?php if ($page_title == "{$_SESSION["FirstName"]} {$_SESSION["LastName"]}'s Profile") { ?> class="active" <?php }; ?> >
            <a href="profile.php"><i class="glyphicon glyphicon-home"></i> Profile</a></li>
        <li role="presentation" <?php if ($page_title == "{$_SESSION["FirstName"]} {$_SESSION["LastName"]}'s Blogs") { ?> class="active" <?php }; ?> >
            <a href="blogs.php"><i class="glyphicon glyphicon-book"></i> Blog</a></li>
        <li role="presentation" <?php if ($page_title == "Photo Collections") { ?> class="active" <?php } 
                    elseif ($page_title == "Photos"){ ?> class="active" <?php }?> ><a
                    href="collections.php"><i class="glyphicon glyphicon-picture"></i> Photos</a></li>
        <li role="presentation" <?php if ($page_title == "Circles") { ?> class="active" <?php }; ?> ><a
                    href="circles.php"><i class="glyphicon glyphicon-record"></i> Circles</a></li>
        <li role="presentation" <?php if ($page_title == "Messages") { ?> class="active" <?php }
                                      elseif ($page_title == "Message Inbox"){ ?> class="active" <?php }
                                      elseif ($page_title == "Message Outbox"){ ?> class="active" <?php }; ?> ><a
                    href="messages.php"><i class="glyphicon glyphicon-envelope"></i> Messages</a></li>
        <li role="presentation" <?php if ($page_title == "{$_SESSION["FirstName"]} {$_SESSION["LastName"]}'s Friends") { ?> class="active" <?php }; ?> >
            <a href="friends.php"><i class="glyphicon glyphicon-user"></i> Friends</a></li>
        <li role="presentation" <?php if ($page_title == "Search") { ?> class="active" <?php }; ?> >
            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i></a></li>

        <li role="presentation" style="width: 300px"><a><form action="search.php" method="post"><input type="text" name="search_result"  class="search_form"></form></a></li>


</div>
    </div>
</nav>
