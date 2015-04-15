<form class="well" method="post" action="<?php check_string(url('')) ?>">
<h4>Confirm Delete</h4>
    <a class="btn btn-primary" href="<?php check_string(url('thread/confirm_delete', array('id'=>$thread_id))) ?>">Yes</a>
    <a class="btn btn-primary" href="<?php check_string(url('thread/index')) ?>">No</a>
</form>
