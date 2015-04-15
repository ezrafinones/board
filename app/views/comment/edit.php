<h3>Edit Comment</h3>
<?php if ($comment->hasError()): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">Validation error!</h4>
        <?php if (!empty($comment->validation_errors['body']['length'])): ?>
            <div><em>Comment</em> must be
                between 
                <?php check_string($comment->validation['body']['length'][1]) ?> and
                <?php check_string($comment->validation['body']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>
    </div>
<?php endif ?>

<form class="well" method="post" action="<?php check_string(url('')) ?>">
    <ul style='list-style:none;'>
        <?php foreach ($comments as $v): ?>
            <li><?php echo "Comment "?></li>
                <textarea name="body" name="body" placeholder="<?php echo $v->body?>" value="<?php check_string(Param::get('body')) ?>"></textarea>
            <br>
        <?php endforeach ?>
    </ul>   
    <input type="hidden" name="page_next" value="edit_end">
    <button type="submit" class="btn btn-primary">Edit</button>
    <a type="submit" class="btn btn-inverse" href="<?php check_string(url('thread/view', array('thread_id' => $thread->id))) ?>" >Back</a>
</form>
