<form class="well" method="post" action="<?php check_string(url('')) ?>">
<h4>Edit Comment</h4>
    <ul style='list-style:none;'>
        <?php foreach ($comments as $v): ?>
            <li><?php echo "Comment "?></li>
             <textarea name="body" name="body" placeholder="<?php echo $v->body?>" value="<?php check_string(Param::get('body')) ?>"></textarea>
            <br>
        <?php endforeach ?>
    </ul>
    <input type="hidden" name="page_next" value="write_comment">
    <button type="submit" class="btn btn-primary">Edit</button>
</form>
