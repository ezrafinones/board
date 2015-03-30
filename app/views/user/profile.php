<ul class="nav nav-pills">
    <li class="active"><a href="#">Profile</a></li>
    <li><a href="/thread/index">Threads</a></li>
</ul>

<div class="hero-unit">
    <?php foreach ($user as $v): ?>
        <h1>Welcome <?php check_string($v->firstname) ?>!</h1>
    <?php endforeach ?>
</div>
