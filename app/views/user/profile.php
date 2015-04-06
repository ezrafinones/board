<div class="hero-unit">
    <?php foreach ($user as $v): ?>
        <h1>Welcome <?php check_string($v->firstname) ?>!</h1>
    <?php endforeach ?>
</div>

<div class="row-fluid">
    <div class="span4 pull-left">
        <img src=<?php echo $image?> alt="avatar" style="width:150px; height:150px; margin-bottom:5px; position:absolute 3px">

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
        <ul style="list-style:none">
            <?php foreach ($user as $v): ?>
                <li><b><?php echo "First name: "?></b><i><?php check_string($v->firstname) ?></i></li>
                <li><b><?php echo "Last name: "?></b><i><?php check_string($v->lastname) ?></i></li>
                <li><b><?php echo "Email Address: "?></b><i><?php check_string($v->email) ?></i></li>
                <li><b><?php echo "Username: "?></b><i><?php check_string($v->username) ?></i></li>
            <?php endforeach ?>
        </ul>
    </div>
    <div class="span8 pull-right">
    <h3>Your Threads</h3>

<?php foreach ($comments as $k => $v): ?>
    <article class="comment" style="margin:20px; padding:15px; box-shadow:0px 0px 5px gray; background:none repeat scroll 0% 0% #FFF">
        <div class="row-fluid">
            <div>
                @<b><?php check_string($v->username) ?></b> 
            </div>
            <div>
                <i><?php check_string($v->created) ?></i>
            </div>
            <div>
                <?php echo readable_text($v->body) ?>
            </div>
        </div>
    </article>
<?php endforeach ?>
  
    </div>
</div>

