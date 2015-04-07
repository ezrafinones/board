<?php
class UserController extends AppController
{
    public function register()
    {
        $page = Param::get('page_next', 'register');
        $default_image = "/image/avatar.png";

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
                    $user->createImage($default_image);
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
        if (!Session::get('username')) {
            redirect(url('user/login'));
        }

        $user = User::getAll();
        $comments = User::getComments();
        $image = User::getImage(); 
        $error = false;
        $this->set(get_defined_vars());

        if (!isset($_FILES["image"])) {
            return;
        }

        $target_dir = "image/";
        $target_file = $image;
        $image_file_type = pathinfo($target_file, PATHINFO_EXTENSION);
        $target_file = $target_dir . $_SESSION['username'].".{$image_file_type}";
        $is_uploaded = true;

        if (!isset($_POST["submit"])) {
            return;
        }
        
        if (getimagesize($_FILES["image"]["tmp_name"]) === false) {
            $is_uploaded = false;
            $error = true;
        }

        if ($_FILES["image"]["size"] > 500000 && $image_file_type != "jpg" && $image_file_type != "png"
        && $image_file_type != "jpeg" && $image_file_type != "gif") {
            $is_uploaded = false;
            $error = true;
        }

        if ($is_uploaded == true && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $target_file = "/" . $target_file;
            User::uploadImage($target_file);
        } else {
            $error = true;
        }
    }

    public function logout()
    {
        session_destroy();
        redirect(url('user/login'));
    }

    public function settings()
    {
        $page = Param::get('page_next', 'settings');
        $user = User::getAll();
        $save = Param::get('save');
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

    public function user_profile()
    {     
        if (!Session::get('username')) {
            redirect(url('user/login'));
        }
        $user_id = Param::get('user_id');
        $user = User::getUserInfo($user_id);
        $comments = Comment::getUserComments($user_id);

        $this->set(get_defined_vars());
    }
}
