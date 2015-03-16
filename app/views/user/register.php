<h1>User Registration</h1>

<?php if ($userinfo->hasError()): ?>
    <div class="alert alert-block">                
        <h4 class="alert-heading">Validation error!</h4>

        <?php if (!empty($userinfo->validation_errors['firstname']['length'])): ?>            
            <div><em>First Name</em> must be
            between                
                <?php eh($userinfo->validation['firstname']['length'][1]) ?> and                    
                <?php eh($userinfo->validation['firstname']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>

        <?php if (!empty($userinfo->validation_errors['lastname']['length'])): ?>             
            <div><em>Last Name</em> must be
                between 
                <?php eh($userinfo->validation['lastname']['length'][1]) ?> and                    
                <?php eh($userinfo->validation['lastname']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>
        <?php if (!empty($userinfo->validation_errors['email']['length'])): ?>             
            <div><em>Email Address</em> must be
                between 
                <?php eh($userinfo->validation['email']['length'][1]) ?> and                    
                <?php eh($userinfo->validation['email']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>
        <?php if (!empty($userinfo->validation_errors['username']['length'])): ?>             
            <div><em>Username</em> must be
                between 
                <?php eh($userinfo->validation['username']['length'][1]) ?> and                    
                <?php eh($userinfo->validation['username']['length'][2]) ?> characters in length.                            
            </div>
        <?php endif ?>
        <?php if (!empty($userinfo->validation_errors['password']['length'])): ?>             
            <div><em>Password</em> must be
                between 
                <?php eh($userinfo->validation['password']['length'][1]) ?> and                    
                <?php eh($userinfo->validation['password']['length'][2]) ?> characters in length.          
            </div>            
        <?php endif ?>
          <?php if (!empty($userinfo->validation_errors['password']['match'])): ?>             
            <div><em>Password did not match </em>                            
            </div>            
        <?php endif ?>
    </div>                    
<?php endif ?>
                                
<form class="well" method="post" action="<?php eh(url('')) ?>">

    <input type="text" class="span2" name="firstname" placeholder="firstname" value="<?php eh(Param::get('firstname')) ?>">
    <br>
    <input type="text" class="span2" name="lastname" placeholder="lastname" value="<?php eh(Param::get('lastname')) ?>">
    <br>
    <input type="email" class="span2" name="email" placeholder="email" value="<?php eh(Param::get('email')) ?>">
    <br>
    <input type="text" class="span2" name="username" placeholder="username" value="<?php eh(Param::get('username')) ?>">
    <br>
    <input type="password" class="span2" name="password" placeholder="password" value="<?php eh(Param::get('password')) ?>">
    <br>
    <input type="password" class="span2" name="validate_password" placeholder="re-type password" value="<?php eh(Param::get('validate_password')) ?>">
    <br />
    <input type="hidden" name="user_id" value="<?php eh($user->id) ?>">
    <input type="hidden" name="page_next" value="write_end">

    <button type="submit" class="btn btn-inverse">Register</button>                
</form> 

