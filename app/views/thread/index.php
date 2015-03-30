<ul class="nav nav-pills">
    <li><a href="/user/profile">Profile</a></li>
    <li class="active"><a href="#">Threads</a></li>
</ul>

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
    </li>
    <?php endforeach ?>
</ul>

<a class="btn btn-primary" style="padding: 8px 40px;" href="<?php check_string(url('thread/create')) ?>">Create</a>

<br>
<br>

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
