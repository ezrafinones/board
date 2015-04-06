<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>DietCake</title>
        <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                padding-top: 60px;
            }
        </style>
    </head>

    <body>
        <div class="navbar navbar-fixed-top navbar-inverse">
            <div class="navbar-inner">
                <div class="container">
                    <a class="brand " href="/">DietCake</a>
                    <?php if (isset($_SESSION['username'])): ?>
                    <ul class="nav nav-pills">
                        <li><a href="/user/profile">Profile</a></li>
                        <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="/thread/index"> Threads <b class="caret"></b></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <li><a href="/thread/index">Threads</a></li>
                                <li><a href="/thread/top_comments"> Top Comments</a></li>
                                <li><a href="/thread/top_threads"> Top Threads</a></li>
                            </ul>
                        </li>
                    </ul>
                    <div class="btn-group pull-right">
                        <a class="btn btn-primary" href="/user/profile"><i class="icon-user icon-white"></i><?php echo " ".$_SESSION['username']?></a>
                        <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            <li><a href="/user/settings"><i class="icon-wrench"></i> Account Settings</a></li>
                            <li><a href="/user/logout"><i class="icon-off"></i> Log Out</a></li>
                         </ul>
                    </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <div class="container">
            <?php echo $_content_ ?>
        </div>
        <script>
            console.log(<?php check_string(round(microtime(true) - TIME_START, 3)) ?> + 'sec');
        </script>
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>
