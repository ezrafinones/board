<h3>Account Settings</h3>
<form class="well" method="post" action="<?php check_string(url('')) ?>">
<h4>User Profile Information</h4>
    <ul style='list-style:none;'>
        <?php foreach ($user as $v): ?>
            <li><?php echo "Edit First Name: "?></li>
            <input type="text" class="span2" name="firstname" placeholder="<?php check_string($v->firstname) ?>" value="<?php check_string(Param::get('firstname')) ?>">
            <br>
            <li><?php echo "Edit Last Name: "?></li>
            <input type="text" class="span2" name="lastname" placeholder="<?php check_string($v->lastname) ?>" value="<?php check_string(Param::get('lastname')) ?>">
            <br>
            <li><?php echo "Edit Email Address: "?></li>
            <input type="email" class="span2" name="email" placeholder="<?php check_string($v->email) ?>" value="<?php check_string(Param::get('email')) ?>">
            <br>
        <?php endforeach ?>
    </ul>
    <input type="hidden" name="user_id" value="<?php check_string($user->id) ?>">
    <input type="hidden" name="page_next" value="write_success">
    <button type="submit" class="btn btn-inverse">Save</button>
</form> 

