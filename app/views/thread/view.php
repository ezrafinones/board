<h1><?php check_string($thread->title) ?></h1>

<?php foreach ($threads as $k => $v): ?>
    <div class="comment">
        <div class="meta">
            <?php check_string($k + 1) ?>: <?php check_string($v->username) ?> <?php check_string($v->created) ?>
        </div>
        <?php echo readable_text($v->body) ?>
    </div>
<?php endforeach ?>

<ul class="pager">
<?php if ($pagination->current > 1): ?>
    <li><a href='?thread_id=<?php echo $thread_id ?>&page=<?php echo $pagination->prev ?>'>Previous</a></li>
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
    <li><a href='?thread_id=<?php echo $thread_id ?>&page=<?php echo $pagination->next ?>'>Next</a></li>
<?php else: ?>
    Next
<?php endif ?>
</ul>

<hr>
<form class="well" method="post" action="<?php check_string(url('thread/write')) ?>">
    <label>Your name</label>
        <input type="text" class="span2" name="username" value="<?php check_string(Param::get('username')) ?>">
    <label>Comment</label>
    <textarea name="body"><?php check_string(Param::get('body')) ?></textarea>
    <br />
    <input type="hidden" name="thread_id" value="<?php check_string($thread->id) ?>">
    <input type="hidden" name="page_next" value="write_end">
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
