<link rel="stylesheet" href="../styles/fonts">
<?php require_once("../server/validation_search.php");?>

<nav class="navbar-container">

    <div class="container-fluid">
    <div class="row text-center">
        <div class="col-md-12">
            <ul class="nav nav-pills">
                <li role="presentation" class="so-shall-logo"><i class="glyphicon glyphicon-globe"></i> SoShallNetwork</li>
                <li role="presentation" class="pull-right"><a href="logout.php">Logout <i class="glyphicon glyphicon-log-out" aria-hidden="true"></i></a></li>
            </ul>

        </div>
    </div>
    </div>
    <div class="container-fluid">
        <div class="row">

            <ul class="nav nav-pills col-md-2">
                <li role="presentation">
                    <a id="disabled-anchor">Logged in as <?php echo "{$_SESSION['FirstName']} {$_SESSION['LastName']}"?></a></li>
            </ul>


    
    <ul class="nav nav-pills col-md-7 ">
        
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
        <li role="presentation" <?php if ($page_title == "{$_SESSION["FirstName"]} {$_SESSION["LastName"]}'s Notifications") { ?> class="active" <?php }; ?> >
            <a href="notifications.php"><i class="glyphicon glyphicon-exclamation-sign"></i> Notifications</a></li>


    </ul>



                <form action="search.php" method="post">
                    <ul class="nav nav-pills col-md-3  ">
    <div class="input-group">
                        <div class="row ">
                            <div class="col-md-2 ">


                    <li role="presentation" <?php if ($page_title == "Search") { ?> class="active" <?php }; ?> >
                        <div class="input-btn-group">
                        <a><button class="btn btn-primary btn-sm" type="submit"><i class="glyphicon glyphicon-search" aria-hidden="true"></i></button> </a>
                        </div>
                    </li>
                            </div>
                            <div class="col-md-10 ">

                        <input type="text" name="search_result"  class="search_form">
                            </div>
                        </div>
    </div>
                </ul>
                </form>



    </div>
</nav>
