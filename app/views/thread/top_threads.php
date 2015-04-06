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

<h4>Top Threads</h4>
<?php foreach ($favorites as $k => $v): ?>
    <div class="comment">  
        <h5><?php echo "Top ".($k+1) ?><i><?php echo "  Likes: " ?><?php check_string($v->total_favorites) ?></i></h5>

        <div class="meta" style="font-size:12px; color:#5C5C5C">
            <i><?php check_string($v->created) ?></i>
        </div>
        <div class="well">
            <?php echo readable_text($v->title) ?>
        </div>
    </div>
<?php endforeach ?>