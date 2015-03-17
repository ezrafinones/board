<?php
class UserController extends AppController
{
    public function register()
    {
        $register_info = new RegisterInfo;
        $user = new User;
        $page = Param::get('page_next', 'register');
        $error = false;

        switch ($page) {
            case 'register':
                break;
            case 'write_end':
                $register_info->firstname = Param::get('firstname');
                $register_info->lastname = Param::get('lastname');
                $register_info->email = Param::get('email');
                $register_info->username = Param::get('username');
                $register_info->password = Param::get('password');
                $register_info->validate_password = Param::get('validate_password');

                $user->firstname = Param::get('firstname');
                $user->lastname = Param::get('lastname');
                $user->email = Param::get('email');
                $user->username = Param::get('username');
                $user->password = Param::get('password');

                if (!$this->isMatchPassword()) {
                    $register_info->validation_errors['password']['match'] = true;
                }

                try {
                    $user->register($register_info);
                } catch (ValidationException $e) {
                    $page = 'register';
                    $error = true;
                }
                break;
            default:
                throw new NotFoundException("{$page} is not found");
                break;
        }
        $this->set(get_defined_vars());
        $this->render($page);
    }

    public function login()
    {
        $login_info = new LoginInfo;
        $user = new User;
        $page = Param::get('page_next', 'login');
        $error = false;

        if (isset($_SESSION['username'])) {
            redirect('/user/profile');
        }

        switch ($page) {
            case 'login':
                break;
            case 'write_end':
                $login_info->username = Param::get('username');
                $login_info->password = Param::get('password');

                $user->username = Param::get('username');
                $user->password = Param::get('password');
                
                try {
                    $user->login($login_info);
                } catch (ValidationException $e) {
                    $page = 'login';
                    $error = true;
                }
                if (!$error) {
                    redirect('/user/profile');
                }
                break;
            default:
                throw new NotFoundException("{$page} is not found");
                break;
        }
        $this->set(get_defined_vars()); 
    }

    public function isMatchPassword()
    {
        $isMatch = Param::get('password') == Param::get('validate_password');
        if ($isMatch) {
            return true;
        }
    }

    public function profile()
    {
        if (!isset($_SESSION['username'])) {
            redirect('/');
        }
        $this->set(get_defined_vars());
    }

    public function logout()
    {
        session_destroy();
        redirect('/');
    }
}
