<link rel="stylesheet" href="../styles/fonts">

<nav class="navbar-container">
    <ul class="nav nav-pills">
        <li role="presentation" <?php if ($page_title == "{$_SESSION["FirstName"]} {$_SESSION["LastName"]}'s Profile") { ?> class="active" <?php }; ?> >
            <a href="profile.php">Profile</a></li>
        <li role="presentation" <?php if ($page_title == "{$_SESSION["FirstName"]} {$_SESSION["LastName"]}'s Blogs") { ?> class="active" <?php }; ?> >
            <a href="blogs.php">Blog</a></li>
        <li role="presentation" <?php if ($page_title == "Photo Collections") { ?> class="active" <?php } 
                    elseif ($page_title == "Photos"){ ?> class="active" <?php }?> ><a
                    href="collections.php">Photos</a></li>
        <li role="presentation" <?php if ($page_title == "Circles") { ?> class="active" <?php }; ?> ><a
                    href="circles.php">Circles</a></li>
        <li role="presentation" <?php if ($page_title == "Messages") { ?> class="active" <?php }
                                      elseif ($page_title == "Message Inbox"){ ?> class="active" <?php }
                                      elseif ($page_title == "Message Outbox"){ ?> class="active" <?php }; ?> ><a
                    href="messages.php">Messages</a></li>
        <li role="presentation" <?php if ($page_title == "{$_SESSION["FirstName"]} {$_SESSION["LastName"]}'s Friends") { ?> class="active" <?php }; ?> >
            <a href="friends.php">Friends</a></li>
        <li role="presentation" <?php if ($page_title == "Search") { ?> class="active" <?php }; ?> ><a
                    href="search.php"><i class="glyphicon glyphicon-search" aria-hidden="true"></i></a></li>
    </ul>
</nav>
