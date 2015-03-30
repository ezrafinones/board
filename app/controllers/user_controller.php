<?php
class UserController extends AppController
{
    public function register()
    {
        $page = Param::get('page_next', 'register');

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
        $page = Param::get('page_next', 'login');
        $error = false;

        if (isset($_SESSION['username'])) {
            redirect(url('user/profile'));
        }

        switch ($page) {
            case 'login':
                break;
            case 'write_end':
                $params = array(
                    'username' => Param::get('username'),
                    'password' => Param::get('password')
                );
                $user = new User($params);
                $login_info = new LoginInfo($params);

                try {
                    $user->login($login_info);
                } catch (ValidationException $e) {
                    $page = 'login';
                    $error = true;
                }
                if (!$error) {
                    redirect(url('user/profile'));
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
        return $isMatch = Param::get('password') == Param::get('validate_password');
    }

    public function profile()
    {
        if (!$_SESSION['username']) {
            redirect(url('user/login'));
        }

        $user = User::getAll();
        $comments = User::getComments();
        $this->set(get_defined_vars());
    }

    public function logout()
    {
        session_destroy();
        redirect(url('user/login'));
    }

    public function settings()
    {
        $users = new User;
        $page = Param::get('page_next', 'settings');
        $user = User::getAll();
        $error = false;

        switch ($page) {
            case 'settings':
                break;
            case 'write_success':
                $params = array(
                    'firstname' => Param::get('firstname'),
                    'lastname' => Param::get('lastname'),
                    'email' => Param::get('email'),
                    'password' => Param::get('password'),
                    'newpassword' => Param::get('newpassword'),
                    'cnewpassword' => Param::get('cnewpassword')
                );
                $users = new User($params);
                $users->getId();

                try {
                    $users->updateProfile();
                    $users->updatePassword();

                } catch (ValidationException $e) {
                        $page = 'settings';
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
}
