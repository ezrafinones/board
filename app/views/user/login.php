<h1>Login</h1>
 
<?php if ($error): ?>
    <div class="alert alert-block">                
        <h4 class="alert-heading">Not a registered user</h4>
    </div>
<?php endif ?>

<?php if ($user->hasError()): ?>
    <div class="alert alert-block">                
        <h4 class="alert-heading">Validation error!</h4>

        <?php if (!empty($user->validation_errors['username']['length'])): ?>            
            <div><em>First Name</em> must be
            between                
                <?php eh($user->validation['username']['length'][1]) ?> and                    
                <?php eh($user->validation['username']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>

        <?php if (!empty($user->validation_errors['password']['length'])): ?>             
            <div><em>Last Name</em> must be
                between 
                <?php eh($user->validation['password']['length'][1]) ?> and                    
                <?php eh($user->validation['password']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>
    </div>
<?php endif ?>

<form class="well" method="post" action="<?php eh(url('')) ?>">
    <div class="input-prepend">
        <span class="add-on"><span class="icon-user"></span></span>
        <input type="text" class="span2" name="username" placeholder="username" value="<?php eh(Param::get('username')) ?>">
    </div>
    <div class="input-prepend">
        <span class="add-on"><span class="icon-briefcase"></span></span>
        <input type="password" class="span2" name="password" placeholder="password" value="<?php eh(Param::get('password')) ?>">
    </div>
    <input type="hidden" name="user_id" value="<?php eh($user->id) ?>">
    <input type="hidden" name="page_next" value="user_profile">
    <button type="submit" class="btn btn-inverse">Login</button>   
    <input type="hidden" name="user_id" value="<?php eh($user->id) ?>">
    <input type="hidden" name="page_next" value="write_end">
    <a href="/user/register" class="btn">Sign Up</a>              
</form>     