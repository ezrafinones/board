<h2><?php check_string($thread->title) ?></h2>
 
<p class="alert alert-success">
    You successfully created.
</p>

<a href="<?php check_string(url('thread/view', array('thread_id' => $thread->id))) ?>">
    &larr; Go to thread
</a>
