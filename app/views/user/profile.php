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

<div class="hero-unit">
    <?php foreach ($user as $v): ?>
        <h1>Welcome <?php check_string($v->firstname) ?>!</h1>
    <?php endforeach ?>
</div>

<img src=<?php echo $image?> alt="avatar" style="width:150px; height:150px; margin-bottom:5px">

<?php if ($error) : ?>
    <div class="alert alert-block">
        <h5 class="alert-heading">Sorry, your file was not uploaded. There was an error in uploading your image.</h5>
    </div>
<?php endif ?>

<form action="<?php check_string(url('')) ?>" method="post" enctype="multipart/form-data">
    <label class="btn btn-inverse" style="padding:4px 50px; margin-bottom:5px">Browse   
        <input type="file" name="image" id="image" style="display:none;">
    </label>
    <br>
    <input style="padding:2px 28px" type="submit" value="Upload Image" name="submit">
</form>

<h4>User Details</h4>
<ul style='list-style:none;'>
    <?php foreach ($user as $v): ?>
        <li><b><?php echo "First name: "?></b><i><?php check_string($v->firstname) ?></i></li>
        <li><b><?php echo "Last name: "?></b><i><?php check_string($v->lastname) ?></i></li>
        <li><b><?php echo "Email Address: "?></b><i><?php check_string($v->email) ?></i></li>
        <li><b><?php echo "Username: "?></b><i><?php check_string($v->username) ?></i></li>
    <?php endforeach ?>
</ul>

<h3>Your Threads</h3>

<?php foreach ($comments as $k => $v): ?>
    <div class="comment">  
        <div class="meta" style="font-size:16px">
            @<b><?php check_string($v->username) ?></b> 
        </div>
        <div class="meta" style="font-size:12px; color:#5C5C5C">
            <i><?php check_string($v->created) ?></i>
        </div>
        <div class="well" style="min-height:40px; border-radius:8px; background-color:#D9D9D9;">
            <?php echo readable_text($v->body) ?>
        </div>
    </div>
<?php endforeach ?>
