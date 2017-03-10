<link rel="stylesheet" href="../styles/fonts">

<nav class="navbar-container">
            <ul class="nav nav-pills">
            <li role="presentation" <?php if ($page_title == "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Profile") { ?> class="active" <?php }; ?> >
            <a href="user_profile.php?id=<?php echo $_GET['id']; ?>">Profile</a></li>
            <li role="presentation" <?php if ($page_title == "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Blogs") { ?> class="active" <?php }; ?> >
            <a href="user_blogs.php?id=<?php echo $_GET['id']; ?>">Blogs</a></li>
            <li role="presentation" <?php if ($page_title == "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Collections") { ?> class="active" <?php }; ?> >
            <a href="user_collections.php?id=<?php echo $_GET['id']; ?>">Photos</a></li>
            <li role="presentation" <?php if ($page_title == "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Friends") { ?> class="active" <?php }; ?> >
            <a href="user_friends.php?id=<?php echo $_GET['id']; ?>">Friends</a></li>
            </ul>
</nav>