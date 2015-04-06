<h4>Top Comments</h4>
<?php foreach ($favorites as $k => $v): ?>
    <div class="comment">  
        <h5><?php echo "Top ".($k+1) ?><i><?php echo "  Favorites: " ?><?php check_string($v->total_favorites) ?></i></h5>

        <div class="meta" style="font-size:16px">
            <a href="/user/user_profile?user_id=<?php echo $v->user_id ?>"><b><?php check_string($v->username) ?></b></a>
        </div>
        <div class="meta" style="font-size:12px; color:#5C5C5C">
            <i><?php check_string($v->created) ?></i>
        </div>
        <div class="well">
            <?php echo readable_text($v->body) ?>
        </div>
    </div>
<?php endforeach ?>
