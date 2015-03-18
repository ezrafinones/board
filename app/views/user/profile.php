<ul class="nav nav-pills">
    <li class="active"><a href="#">Profile</a></li>
    <li><a href="/thread/index">Threads</a></li>
    <li><a href="/user/logout">Logout</a></li>

    <div class="btn-group pull-right">
    <a class="btn btn-primary" href="#"><i class="icon-user icon-white"></i><?php echo " ".$_SESSION['username']?></a>
    <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
     <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
        <li><a href="#">Log Out</a></li>
    </ul>
    </div>
</ul>

<div class="hero-unit">
    <h1>Welcome <?php echo $_SESSION['username'] ?>!</h1>
</div>

