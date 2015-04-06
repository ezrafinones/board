<h1>User Registration</h1>

<?php if (isset($user)): ?>
    <?php if ($user->hasError()): ?>
        <div class="alert alert-block">
            <h4 class="alert-heading">Validation error!</h4>
            <?php if (!empty($user->validation_errors['user']['exist'])): ?>
                <div><em>You have already have existing account</em>
                </div>
            <?php endif ?>
            <?php if (!empty($user->validation_errors['firstname']['length'])): ?>
                <div><em>First Name</em> must be
                between                
                    <?php check_string($user->validation['firstname']['length'][1]) ?> and
                    <?php check_string($user->validation['firstname']['length'][2]) ?> characters in length.
                </div>
            <?php endif ?>

            <?php if (!empty($user->validation_errors['lastname']['length'])): ?>
                <div><em>Last Name</em> must be
                    between 
                    <?php check_string($user->validation['lastname']['length'][1]) ?> and
                    <?php check_string($user->validation['lastname']['length'][2]) ?> characters in length.
                </div>
            <?php endif ?>
            <?php if (!empty($user->validation_errors['email']['length'])): ?> 
                <div><em>Email Address</em> must be
                    between 
                    <?php check_string($user->validation['email']['length'][1]) ?> and
                    <?php check_string($user->validation['email']['length'][2]) ?> characters in length.
                </div>
            <?php endif ?>
            <?php if (!empty($user->validation_errors['username']['length'])): ?>
                <div><em>Username</em> must be
                    between 
                    <?php check_string($user->validation['username']['length'][1]) ?> and
                    <?php check_string($user->validation['username']['length'][2]) ?> characters in length.
                </div>
            <?php endif ?>
            <?php if (!empty($user->validation_errors['password']['length'])): ?>
                <div><em>Password</em> must be
                    between 
                    <?php check_string($user->validation['password']['length'][1]) ?> and
                    <?php check_string($user->validation['password']['length'][2]) ?> characters in length.
                </div>
            <?php endif ?>
              <?php if (!empty($user->validation_errors['password']['match'])): ?>
                <div><em>Password did not match </em>
                </div>
            <?php endif ?>
        </div>
    <?php endif ?>
<?php endif ?>

<form class="well" method="post" action="<?php check_string(url('')) ?>">
    <input type="text" class="span2" name="firstname" placeholder="firstname" value="<?php check_string(Param::get('firstname')) ?>">
    <br>
    <input type="text" class="span2" name="lastname" placeholder="lastname" value="<?php check_string(Param::get('lastname')) ?>">
    <br>
    <input type="email" class="span2" name="email" placeholder="email" value="<?php check_string(Param::get('email')) ?>">
    <br>
    <input type="text" class="span2" name="username" placeholder="username" value="<?php check_string(Param::get('username')) ?>">
    <br>
    <input type="password" class="span2" name="password" placeholder="password" value="<?php check_string(Param::get('password')) ?>">
    <br>
    <input type="password" class="span2" name="validate_password" placeholder="re-type password" value="<?php check_string(Param::get('validate_password')) ?>">
    <br />
    <input type="hidden" name="user_id" value="<?php check_string($user->id) ?>">
    <input type="hidden" name="page_next" value="write_end">
    <button type="submit" class="btn btn-inverse">Register</button>
</form>
