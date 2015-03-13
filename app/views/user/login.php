<h1>Login</h1>
                                
<form class="well" method="post" action="<?php eh(url('')) ?>">
    <input type="text" class="span2" name="username" placeholder="username" value="<?php eh(Param::get('username')) ?>">
    <br>
    <input type="text" class="span2" name="password" placeholder="password" value="<?php eh(Param::get('password')) ?>">
    <br />
    <input type="hidden" name="user_id" value="<?php eh($user->id) ?>">
    <input type="hidden" name="page_next" value="user_profile">
    <button type="submit" class="btn btn-inverse">Login</button>   
    <a href="/user/register" class="btn tn-primary disabled">Sign Up</a>              
</form> 