<?php
class UserController extends AppController
{
    public function register()
    {
        $page = Param::get(User::PAGE_NEXT, User::PAGE_REGISTER);
        $default_image = "/image/avatar.png";

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
                User::getAll($params['username'], $user);

                if (!$this->isMatchPassword()) {
                    $user->validation_errors['password']['match'] = true;
                }

                if (!((preg_match("/^[a-zA-Z0-9]+$/", $params['username'])) || (preg_match("/^[a-zA-Z0-9 ]+$/", $name))
                || (preg_match("/^[a-zA-Z]+$/", $params['password'])))) {
                    $user->validation_errors['userinfo']['match'] = true;
                }

                try {
                    $user->createImage($default_image, Session::get('id'));
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
                    redirect(url('user/profile'));
                } catch (ValidationException $e) {
                    $page = User::PAGE_LOGIN;
                    $user->validation_errors['username']['notexist'] = true;
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

        $user = User::getRowsById(Session::get('id'));
        $comments = Comment::getByUsername(Session::get('username'));
        $this->set(get_defined_vars());
        $this->upload_photo();
    }

    public function upload_photo()
    {
        $users = new User();
        try {
            $image = User::getImage(Session::get('id'));
        } catch (RecordNotFoundException $e) {
            $users->validation_errors['image']['error'] = true;
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
            $users->validation_errors['image']['error'] = true;
        }

        if ($is_uploaded == true && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $target_file = "/" . $target_file;
            User::uploadImage($target_file, Session::get('id'));
        } else {
            $users->validation_errors['image']['error'] = true;
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
        $user = User::getRowsById(Session::get('id'));
        $save = Param::get('save');
        $name =  new User();
        $users = new User();

        switch ($page) {
            case User::PAGE_SETTINGS:
                break;
            case User::PAGE_WRITE_SUCCESS:
                $users->password = Param::get('password');
                $users->newpassword = Param::get('newpassword');
                $users->confirm_new_password = Param::get('confirm_new_password');

                $email = Param::get('email');
                $name->firstname = Param::get('firstname');
                $name->lastname = Param::get('lastname');
                $name->getById(Session::get('id'));
                try {
                    if(!(preg_match("/^[a-zA-Z ]+$/", $name->firstname)) || !(preg_match("/^[a-zA-Z ]+$/", $name->lastname))) {
                        $name->validation_errors['name']['notmatch'] = true;
                    }
                    $name->updateProfile($email);
                    $users->updatePassword(Session::get('id'));
                } catch (ValidationException $e) {
                        $page = User::PAGE_SETTINGS;
                        $users->validation_errors['password']['notmatch'] = true;
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
        $user = User::getByUserId($user_id);
        $comments = Comment::getByUserId($user_id);

        $this->set(get_defined_vars());
        $this->upload_photo();
    }
}
