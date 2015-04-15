<h3>Account Settings</h3>
<form class="well" method="post" action="<?php check_string(url('')) ?>">
<h4>User Profile Information</h4>
<?php if (isset($name)): ?>
    <?php if ($name->hasError()): ?>
        <div class="alert alert-block">
            <h4 class="alert-heading">Validation error!</h4>
            <?php if (!empty($name->validation_errors['name']['notmatch'])): ?>
                <div><em>Invalid input</em></div>
            <?php endif ?>
        </div>
    <?php endif ?>
<?php endif ?>

    <ul style='list-style:none;'>
        <?php foreach ($user as $v): ?>
            <li><?php echo "Edit First Name: "?></li>
            <input type="text" class="span2" name="firstname" value="<?php check_string($v->firstname) ?>" value="<?php check_string(Param::get('firstname')) ?>">
            <br>
            <li><?php echo "Edit Last Name: "?></li>
            <input type="text" class="span2" name="lastname" value="<?php check_string($v->lastname) ?>" value="<?php check_string(Param::get('lastname')) ?>">
            <br>
            <li><?php echo "Edit Email Address: "?></li>
            <input type="email" class="span2" name="email" value="<?php check_string($v->email) ?>" value="<?php check_string(Param::get('email')) ?>">
            <br>
        <?php endforeach ?>
    </ul>
    <input type="hidden" name="user_id" value="<?php check_string($user->id) ?>">
    <input type="hidden" name="page_next" value="write_success">
    <button type="submit" name="save" class="btn btn-inverse">Save</button>
</form> 

<form class="well" method="post" action="<?php check_string(url('')) ?>">
<h4>Change Password</h4>

<?php if (isset($users)): ?>
    <?php if ($users->hasError()): ?>
        <div class="alert alert-block">
            <?php if (!empty($users->validation_errors['password']['notmatch'])): ?>
                <div><em>Password Mismatch</em></div>
            <?php endif ?>
        </div>
    <?php endif ?>
<?php endif ?>

    <ul style='list-style:none;'>
        <li><?php echo "Old Password: "?></li>
        <input type="password" class="span2" name="password" placeholder="Old Password" value="<?php check_string(Param::get('password')) ?>">
        <br>
        <li><?php echo "New Password: "?></li>
        <input type="password" class="span2" name="newpassword" placeholder="New Password" value="<?php check_string(Param::get('password')) ?>">
        <br>
        <li><?php echo "Confirm Password: "?></li>
        <input type="password" class="span2" name="confirm_new_password" placeholder="Re-type Password" value="<?php check_string(Param::get('valdate_password')) ?>">
            <br>
    </ul>
    <input type="hidden" name="user_id" value="<?php check_string($user->id) ?>">
    <input type="hidden" name="page_next" value="write_success">
    <button type="submit" class="btn btn-inverse">Change Password</button>
</form>
