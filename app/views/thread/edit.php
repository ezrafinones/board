<form class="well" method="post" action="<?php check_string(url('')) ?>">
<h4>Edit Thread</h4>

<?php if ($error): ?>
        <h5 class="alert alert-block">Invalid input</h5>
<?php endif ?>

    <ul style='list-style:none;'>
        <?php foreach ($threads as $v): ?>
            <li><?php echo "Title"?></li>
            <input type="text" class="span2" name="title" placeholder="<?php echo $v->title?>" value="<?php check_string(Param::get('title')) ?>">
            <br>
        <?php endforeach ?>
    </ul>
    <input type="hidden" name="page_next" value="write_thread">
    <button type="submit" class="btn btn-primary">Edit</button>
    <a type="submit" class="btn btn-inverse" href="<?php check_string(url('thread/index')) ?>" >Back</a>
</form>
