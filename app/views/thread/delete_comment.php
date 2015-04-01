<form class="well" method="post" action="<?php check_string(url('')) ?>">
<h4>Confirm Delete</h4>
    <?php foreach ($comments as $v): ?>
        <a class="btn btn-primary" href="<?php check_string(url('thread/view', array('thread_id'=>$v->thread_id))) ?>">Yes</a>  
        <a class="btn btn-primary" href="<?php check_string(url('thread/view', array('thread_id'=>$v->thread_id))) ?>">no</a>
    <?php endforeach ?>
</form>
