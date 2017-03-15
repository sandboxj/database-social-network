<link rel="stylesheet" href="../styles/fonts">


<nav class="navbar-container">
    <div class="row text-center">



            <ul class="nav nav-pills nav-stacked user-navbar" >
            <li role="presentation" <?php if ($page_title == "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Profile") { ?> class="active" <?php }; ?> >
            <a href="user_profile.php?id=<?php echo $_GET['id']; ?>"><i class="glyphicon glyphicon-home"></i>   Profile</a></li>
            <li role="presentation" <?php if ($page_title == "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Blogs") { ?> class="active" <?php }; ?> >
                <a href="user_blogs.php?id=<?php echo $_GET['id']; ?>"><i class="glyphicon glyphicon-book"></i>   Blogs</a></li>
            <li role="presentation" <?php if ($page_title == "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Collections" || $page_title == "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Photos") { ?> class="active" <?php }; ?> >
                <a href="user_collections.php?id=<?php echo $_GET['id']; ?>"><i class="glyphicon glyphicon-picture"></i>   Photos</a></li>
            <li role="presentation" <?php if ($page_title == "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Friends") { ?> class="active" <?php }; ?> >
                <a href="user_friends.php?id=<?php echo $_GET['id']; ?>"><i class="glyphicon glyphicon-user"></i>   Friends</a></li>
            </ul>

    </div>
</nav>