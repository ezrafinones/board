<h1>Create a thread</h1>

<?php if ($thread->hasError() || $comment->hasError()): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">Validation error!</h4>
        <?php if (!empty($thread->validation_errors['title']['length'])): ?>
            <div><em>Title</em> must be between
                <?php check_string($thread->validation['title']['length'][1]) ?> and
                <?php check_string($thread->validation['title']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>

        <?php if (!empty($comment->validation_errors['username']['length'])): ?>
            <div><em>Your name</em> must be between
                <?php check_string($comment->validation['username']['length'][1]) ?> and
                <?php check_string($comment->validation['username']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>

        <?php if (!empty($comment->validation_errors['body']['length'])): ?>
            <div><em>Comment</em> must be between
                <?php check_string($comment->validation['body']['length'][1]) ?> and
                <?php check_string($comment->validation['body']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>
    </div>
<?php endif ?>

<form class="well" method="post" action="<?php check_string(url('')) ?>">
    <label>Title</label>
        <input type="text" class="span2" name="title" value="<?php check_string(Param::get('title')) ?>">
        <input type="hidden" class="span2" name="$_SESSION['username']" value="<?php check_string(Param::get('username')) ?>">
    <label>Comment</label>
    <textarea name="body"><?php check_string(Param::get('body')) ?></textarea>
    <br />
    <input type="hidden" name="page_next" value="create_end">
    <button type="submit" class="btn btn-primary">Submit</button>
    <a type="submit" class="btn btn-inverse" href="<?php check_string(url('thread/index')) ?>" >Back</a>
</form>
