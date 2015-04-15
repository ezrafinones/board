<?php if ($userinfo->hasError()): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">Validation error!</h4>
        <?php if (!empty($userinfo->validation_errors['firstname']['length'])): ?>
            <div><em>First Name</em> must be
            between
                <?php check_string($userinfo->validation['firstname']['length'][1]) ?> and
                <?php check_string($userinfo->validation['firstname']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>
        <?php if (!empty($userinfo->validation_errors['lastname']['length'])): ?>
            <div><em>Last Name</em> must be
                between 
                <?php check_string($userinfo->validation['lastname']['length'][1]) ?> and
                <?php check_string($userinfo->validation['lastname']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>
        <?php if (!empty($userinfo->validation_errors['email']['length'])): ?>
            <div><em>Email Address</em> must be
                between
                <?php check_string($userinfo->validation['email']['length'][1]) ?> and
                <?php check_string($userinfo->validation['email']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>
        <?php if (!empty($userinfo->validation_errors['username']['length'])): ?>
            <div><em>Username</em> must be
                between
                <?php check_string($userinfo->validation['username']['length'][1]) ?> and
                <?php check_string($userinfo->validation['username']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>
        <?php if (!empty($userinfo->validation_errors['password']['length'])): ?>
            <div><em>Password</em> must be
                between 
                <?php check_string($userinfo->validation['password']['length'][1]) ?> and
                <?php check_string($userinfo->validation['password']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>
    </div>
<?php endif ?>
