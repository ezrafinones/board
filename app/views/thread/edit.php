<h4>Edit Thread</h4>

<?php if ($thread->hasError() || $comment->hasError()): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">Validation error!</h4>
        <?php if (!empty($thread->validation_errors['title']['length'])): ?>
            <div><em>Title</em> must be between
                <?php check_string($thread->validation['title']['length'][1]) ?> and
                <?php check_string($thread->validation['title']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>
    </div>
<?php endif ?>

<form class="well" method="post" action="<?php check_string(url('')) ?>">
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
