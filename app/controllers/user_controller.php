<?php
class UserController extends AppController
{
    public function register()
    {
        $page = Param::get('page_next', 'register');
        $error = false;

        switch ($page) {
            case 'register':
                break;
            case 'write_end':
                $params = array(
                    'firstname' => Param::get('firstname'),
                    'lastname' => Param::get('lastname'),
                    'email' => Param::get('email'),
                    'username' => Param::get('username'),
                    'password' => Param::get('password'),
                    'validate_password' => Param::get('validate_password')
                );
                $user = new User($params);

                if (!$this->isMatchPassword()) {
                    $user->validation_errors['password']['match'] = true;
                }

                try {
                    $user->register();  
                } catch (ValidationException $e) {
                    $page = 'register';
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
            redirect('/user/login');
        }
        $this->set(get_defined_vars());
    }

    public function logout()
    {
        session_destroy();
        redirect('/user/login');
    }
}
