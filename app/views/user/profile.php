<ul class="nav nav-pills">
    <li class="active"><a href="#">Profile</a></li>
    <li><a href="/thread/index">Threads</a></li>
</ul>

<div class="hero-unit">
    <?php foreach ($user as $v): ?>
        <h1>Welcome <?php check_string($v->firstname) ?>!</h1>
    <?php endforeach ?>
</div>

<h3>Your Threads</h3>
<?php foreach ($comments as $k => $v): ?>
    <div class="comment">  
        <div class="meta" style="font-size:16px">
            @<b><?php check_string($v->username) ?></b> 
        </div>
        <div class="meta" style="font-size:12px; color:#5C5C5C">
            <i><?php check_string($v->created) ?></i>
        </div>
        <div class="well" style="min-height:40px; border-radius:8px; background-color:#D9D9D9;">
            <?php echo readable_text($v->body) ?>
        </div>
    </div>
<?php endforeach ?>