<p class="alert alert-success">
    You successfully deleted comment.
</p>
<?php foreach ($comments as $v): ?>
    <a href="<?php check_string(url('thread/view', array('thread_id'=>$v->thread_id))) ?>">
<?php endforeach ?>
    &larr; Back to thread
</a>
