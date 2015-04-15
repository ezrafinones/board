<p class="alert alert-success">
    You have updated your comment.
</p>
<?php foreach ($comments as $v): ?>
<a href="<?php check_string(url('thread/view', array('thread_id'=>$v->thread_id))) ?>">
<?php endforeach ?>
&larr; Back to Comments page
</a>
