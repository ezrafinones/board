<?php
class UserController extends AppController
{
    public function register()
    {
        $page = Param::get(User::PAGE_NEXT, User::PAGE_REGISTER);
        $default_image = "/image/avatar.png";
        $error = false;

        switch ($page) {
            case User::PAGE_REGISTER:
                break;
            case User::PAGE_WRITE_END:
                $params = array(
                    'firstname' => Param::get('firstname'),
                    'lastname' => Param::get('lastname'),
                    'email' => Param::get('email'),
                    'username' => Param::get('username'),
                    'password' => Param::get('password'),
                    'validate_password' => Param::get('validate_password')
                );
                $user = new User($params);
                $name = $params['firstname'].$params['lastname'];

                if (!$this->isMatchPassword()) {
                    $user->validation_errors['password']['match'] = true;
                }
                try {
                    $user->createImage($default_image, Session::get('id'));
                    if((preg_match("/^[a-zA-Z0-9 ]+$/", Param::get('username'))) || (preg_match("/^[a-zA-Z0-9]+$/", $name))
                    || (preg_match("/^[a-zA-Z ]+$/", $params['password']))) {
                        $error = true;
                    }
                    $user->register();
                } catch (ValidationException $e) {
                    $page = User::PAGE_REGISTER;
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
        if (isset($_SESSION['username'])) {
            redirect(url('user/profile'));
        }

        $page = Param::get(User::PAGE_NEXT, User::PAGE_LOGIN);
        $error = false;

        switch ($page) {
            case User::PAGE_LOGIN:
                break;
            case User::PAGE_WRITE_END:
                $params = array(
                    'username' => Param::get('username'),
                    'password' => Param::get('password')
                );
                $user = new User($params);
                $login_info = new LoginInfo($params);

                try {
                    $user->login($login_info);
                    $id = $user->login($login_info);
                    Session::set('username', Param::get('username'));
                    Session::set('id', $id);
                } catch (ValidationException $e) {
                    $page = User::PAGE_LOGIN;
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

        $user = User::getUserById(Session::get('id'));
        $comments = Comment::getCommentsByUsername(Session::get('username'));
        $this->set(get_defined_vars());
        $this->upload_photo();
    }

    public function upload_photo()
    {
        $error = false;
        try {
            $image = User::getImage(Session::get('id')); 
        } catch (RecordNotFoundException $e) {
            $error = true;
        }
        $this->set(get_defined_vars());

        if ((!isset($_FILES["image"])) && (!isset($_POST["submit"]))) {
            return;
        }

        $target_dir = "image/";
        $target_file = $image;
        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
        $target_file = $target_dir . $_SESSION['username'].".{$file_type}";
        $is_uploaded = true;
        $info =  $_FILES["image"]["tmp_name"];

        if (getimagesize($info) === false && $info > user::MAX_FILE_UPLOAD && $file_type != "jpg" &&
        $file_type != "png" && $file_type != "jpeg" && $file_type != "gif") {
            $is_uploaded = false;
            $error = true;
        }

        if ($is_uploaded == true && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $target_file = "/" . $target_file;
            User::uploadImage($target_file, Session::get('id'));
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
        $page = Param::get(User::PAGE_NEXT, User::PAGE_SETTINGS);
        $user = User::getUserById(Session::get('id'));
        $save = Param::get('save');
        $error = $error_input = false;

        switch ($page) {
            case User::PAGE_SETTINGS:
                break;
            case User::PAGE_WRITE_SUCCESS:
                $params = array(
                    'firstname' => Param::get('firstname'),
                    'lastname' => Param::get('lastname'),
                    'email' => Param::get('email'),
                    'password' => Param::get('password'),
                    'newpassword' => Param::get('newpassword'),
                    'cnewpassword' => Param::get('cnewpassword')
                );
                $users = new User($params);
                $users->getById(Session::get('id'));
                $name = Param::get('firstname').Param::get('lastname');

                try {
                    if(!(preg_match("/^[a-zA-Z ]+$/", $name))) {
                        $error_input = true;
                    }
                    $users->updateProfile();
                    $users->updatePassword(Session::get('id'));
                } catch (ValidationException $e) {
                        $page = User::PAGE_SETTINGS;
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
        $user = User::getUserByUserId($user_id);
        $comments = Comment::getCommentsByUserId($user_id);

        $this->set(get_defined_vars());
        $this->upload_photo();
    }
}
