<h1><?php check_string($thread->title) ?></h1>

<?php foreach ($comments as $k => $v): ?>
    <div class="comment">
        <div class="meta" style="font-size:16px">
            <a href="/user/user_profile?user_id=<?php echo $v->user_id ?>"><b><?php check_string($v->username) ?></b></a>
        </div>
        <?php if ($v->updated === NULL): ?>
            <div class="meta" style="font-size:12px; color:#5C5C5C">
                <i><?php check_string($v->created) ?></i>
            </div>
        <?php else: ?>
            <div class="meta" style="font-size:12px; color:#5C5C5C">
                <i>updated <?php check_string($v->updated) ?></i>
            </div>
        <?php endif ?>
        <div class="well" style="min-height:60px; border-radius:8px; border: 2px dashed #5C5C5C; background-color:#D9D9D9; color:#008AE6">
            <?php echo readable_text($v->body) ?>
        </div>
        <?php if (in_array(Session::get('id'), $v->favorites)): ?>
            <a class="btn btn-small btn-inverse" name="favorites" href="<?php check_string(url('comment/favorites', array('thread_id' => $v->thread_id, 'comment_id'=>$v->id, 'action' => false)))?>">
                <i class="icon-star" style="background-image:url('/bootstrap/img/glyphicons-halflings-yellow.png')"></i> <?php echo $v->total_favorites ?></a>
        <?php else: ?>
            <a class="btn btn-small btn-inverse" name="favorites" href="<?php check_string(url('comment/favorites', array('thread_id' => $v->thread_id, 'comment_id'=>$v->id, 'action' => true)))?>">
                <i class="icon-star icon-white"></i> <?php echo $v->total_favorites ?></a>
        <?php endif ?>
        <?php if (Session::get('id') === $v->user_id): ?>
            <a class="btn btn-small" name="edit" href="/comment/edit?id=<?php echo $v->id ?>"><i class="icon-pencil"></i></a>
            <a class="btn btn-small" name="delete" href="/comment/delete?id=<?php echo $v->id ?>" ><i class="icon-trash"></i></a>
        <?php endif ?>
    </div>
<?php endforeach ?>

<?php if ($total > 5): ?>
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
<?php endif ?>

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
