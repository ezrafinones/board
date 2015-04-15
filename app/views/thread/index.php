<h1>All threads</h1>

<ul style='list-style:none;'>
    <?php foreach ($threads as $v): ?>
    <article class="comment" style="margin:20px; padding:15px; box-shadow:0px 0px 5px gray; background:none repeat scroll 0% 0% #FFF">
    <li>
        <div class="meta">
            <a href="<?php check_string(url('thread/view', array('thread_id' => $v->id))) ?>">
            <div class="container" style="font-size:18px">
                <?php check_string($v->title) ?></a> 
            </div> 
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
        <?php if (Session::get('id') === $v->user_id): ?>
        <a class="btn btn-small" name="edit" href="/thread/edit?id=<?php echo $v->id ?>"><i class="icon-pencil"></i></a>
        <a class="btn btn-small" name="delete" href="/thread/delete?id=<?php echo $v->id ?>" ><i class="icon-trash"></i></a>
        <?php endif ?>
    </li> 
    </article>
    <?php endforeach ?>
</ul>

<a class="btn btn-primary" style="padding: 8px 40px;" href="<?php check_string(url('thread/create')) ?>">Create</a>
<br>
<br>

<?php if ($total > 5): ?>
    <ul class="pager">
        <?php if($pagination->current > 1): ?>
            <li><a href='?page=<?php echo $pagination->prev ?>'>Previous</a></li>
        <?php else: ?>
            Previous
        <?php endif ?>

        <?php for($i = 1; $i <= $pages; $i++): ?>
            <?php if($i == $page): ?>
                <?php echo $i ?>
            <?php else: ?>
                <a href='?page=<?php echo $i ?>'><?php echo $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if(!$pagination->is_last_page): ?>
            <li><a href='?page=<?php echo $pagination->next ?>'>Next</a></li>
        <?php else: ?>
            Next
        <?php endif ?>
    </ul>
<?php endif ?>
