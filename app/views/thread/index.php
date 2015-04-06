<h1>All threads</h1>

<ul style='list-style:none;'>
    <?php foreach ($threads as $v): ?>
    <li>
        <div class="well" style="background-color:white; margin-bottom:7px;">
            <a href="<?php check_string(url('thread/view', array('thread_id' => $v->id))) ?>">
            <div class="container" style="font-size:18px">
                <?php check_string($v->title) ?></a> 
            </div> 
        </div>
        <?php if (Session::get('id') === $v->user_id): ?>
        <a class="btn btn-small" name="edit" href="/thread/edit_thread?id=<?php echo $v->id ?>"><i class="icon-pencil"></i></a>
        <a class="btn btn-small" name="delete" href="/thread/delete_thread?id=<?php echo $v->id ?>" ><i class="icon-trash"></i></a>
        <?php endif ?>
    </li> 
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
