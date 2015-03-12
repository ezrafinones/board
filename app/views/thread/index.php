<h1>All threads</h1>
        
<ul>
    <?php foreach ($threads as $v): ?>
    <li>
        <a href="<?php eh(url('thread/view', array('thread_id' => $v->id))) ?>">
        <?php eh($v->title) ?></a>
    </li>
    <?php endforeach ?>               
</ul>

<a class="btn btn-large btn-primary" href="<?php eh(url('thread/create')) ?>">Create</a>

<br>    
<br>
<?php if($pagination->current > 1): ?>
    <a href='?page=<?php echo $pagination->prev ?>'>Previous</a>
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
    <a href='?page=<?php echo $pagination->next ?>'>Next</a>
<?php else: ?>
    Next
<?php endif ?>
