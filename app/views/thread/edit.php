<form class="well" method="post" action="<?php check_string(url('')) ?>">
<h4>Edit Thread</h4>
    <ul style='list-style:none;'>
        <?php foreach ($threads as $v): ?>
            <li><?php echo "Edit Title "?></li>
            <input type="text" class="span2" name="title" placeholder="<?php echo $v->title?>" value="<?php check_string(Param::get('title')) ?>">
            <br>
        <?php endforeach ?>
    </ul>
    <input type="hidden" name="page_next" value="write_thread">
    <button type="submit" class="btn btn-primary">Edit</button>
</form>
