<ul class="nav nav-pills">
    <li><a href="/user/profile">Profile</a></li>
    <li><a href="/thread/index">Threads</a></li>

    <div class="btn-group pull-right">
        <a class="btn btn-primary" href="#"><i class="icon-user icon-white"></i><?php echo " ".Session::get('username')?></a>
        <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
            <li><a href="/user/logout">Log Out</a></li>
        </ul>
    </div>
</ul>

<h1><?php check_string($thread->title) ?></h1>

<?php foreach ($threads as $k => $v): ?>
    <div class="comment">  
        <div class="meta" style="font-size:16px">
            <b><?php check_string($v->username) ?></b> 
        </div>
        <div class="meta" style="font-size:12px; color:#5C5C5C">
            <i><?php check_string($v->created) ?></i>
        </div>
        <div class="well" style="min-height:60px; border-radius:8px; border: 2px dashed #5C5C5C; background-color:#D9D9D9; color:#008AE6">
            <?php echo readable_text($v->body) ?>
        </div>
    </div>
<?php endforeach ?>

<ul class="pager">
    <?php if ($pagination->current > 1): ?>
        <li><a href='<?php echo url('thread/view', array('thread_id' => $thread_id, 'page' => $pagination->prev)); ?>'>Previous</a></li>
    <?php else: ?>
        Previous
    <?php endif ?>

    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <?php if ($i == $page): ?>
            <?php echo $i ?>
        <?php else: ?>
            <a href='?thread_id=<?php echo $thread_id ?>&page=<?php echo $i ?>'><?php echo $i ?></a>
        <?php endif; ?>
    <?php endfor; ?>

    <?php if (!$pagination->is_last_page): ?>
        <li><a href='<?php echo url('thread/view', array('thread_id' => $thread_id, 'page' => $pagination->next)); ?>'>Next</a></li>
    <?php else: ?>
        Next
    <?php endif ?>
</ul>

<hr>
<form class="well" method="post" action="<?php check_string(url('thread/write')) ?>">
    <input type="hidden" class="span2" name="<?php Session::get('username')?>" value="<?php check_string(Param::get('username')) ?>">
    <label>Comment</label>
    <textarea name="body"><?php check_string(Param::get('body')) ?></textarea>
    <br />
    <input type="hidden" name="thread_id" value="<?php check_string($thread->id) ?>">
    <input type="hidden" name="page_next" value="write_end">
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
