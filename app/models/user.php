<?php
class User extends AppModel
{
    const MIN_LENGTH = 1;
    const MAX_NAME_LENGTH = 254;
    const MAX_EMAIL_LENGTH = 254;
    const MAX_USERNAME_LENGTH = 20;
    const MAX_PASSWORD_LENGTH = 20;
    const MAX_FILE_UPLOAD = 500000;

    const PAGE_NEXT = 'page_next';
    const PAGE_REGISTER = 'register';
    const PAGE_WRITE_END = 'write_end';
    const PAGE_LOGIN = 'login';
    const PAGE_SETTINGS = 'settings';
    const PAGE_WRITE_SUCCESS = 'write_success';

    public $validation = array(
        'firstname' => array(
            'length' => array(
                'validate_between', self::MIN_LENGTH, SELF::MAX_NAME_LENGTH,
            ),
        ),
    'lastname' => array(
        'length' => array(
            'validate_between', self::MIN_LENGTH, SELF::MAX_NAME_LENGTH,
            ),
        ),
    'email' => array(
        'length' => array(
            'validate_between', self::MIN_LENGTH, SELF::MAX_EMAIL_LENGTH,
            ),
        ),
    'username' => array(
        'length' => array(
            'validate_between', self::MIN_LENGTH, SELF::MAX_USERNAME_LENGTH,
            ),
        ),
    'password' => array(
        'length' => array(
            'validate_between', self::MIN_LENGTH, SELF::MAX_PASSWORD_LENGTH,
            ),
        ),
    'validate_password' => array(
        'length' => array(
            'validate_between', self::MIN_LENGTH, SELF::MAX_PASSWORD_LENGTH,
            ),
        ),
    );

    public function register()
    {
        $this->validate();
        $db = DB::conn();
        $row = $db->row('SELECT * FROM user WHERE username = ?', array($this->username));
        
        if ($row) {
            $this->validation_errors['user']['exist'] = true;
        }

        if ($this->hasError()) {
            throw new ValidationException('invalid input');
        }
        $params = array(
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'username' => $this->username,
            'password'=> md5($this->password),
        );
        $db->insert('user', $params);
    }

    public function login(LoginInfo $login_info)
    {
        $login_info->validate();
        if ($login_info->hasError()) {
            throw new ValidationException('invalid input');
        }
        $db = DB::conn();
        $params = array($this->username, md5($this->password));
        $row = $db->row('SELECT * FROM user WHERE username = ? AND password = ?', $params);

        if (!$row) {
            throw new RecordNotFoundException('No Record Found');
        }
        return $row['id'];
    }

    public static function getRowsById($id)
    {
        $user = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM user WHERE id = ?", array($id));

        if (!$rows) {
            throw new RecordNotFoundException('No Record found');
        }

        foreach ($rows as $row) {
            $user[] = new self($row);
        }
        return $user;
    }

    public function getById($id)
    {
        $db = DB::conn();
        $id = $db->row("SELECT id FROM user WHERE id = ?", array($id));
        $this->id = $id['id'];
    }

    public function updateProfile()
    {
        $params = array();
        $profile = array('firstname' => $this->firstname,
                    'lastname' => $this->lastname,
                    'email' => $this->email,
        );

        foreach ($profile as $k => $v) {
            if (!empty($v)) {
                $params[$k] = $v;
            }
        }

        if (!empty($params)) {
            try {
                $db = DB::conn();
                $db->update('user', $params, array('id' => $this->id));
            } catch (Exception $e) {
                throw $e;
            }
        }
    }

    public function updatePassword($id)
    {
        if (!empty($this->newpassword) && !empty($this->confirm_new_password)) {
            $params = array('password' => md5($this->newpassword),
                        'validate_password' => md5($this->confirm_new_password),
            );
            $db = DB::conn();
            $row = $db->row('SELECT password FROM user WHERE id = ?', array($id));

            if ($row['password'] === md5($this->password) && $this->newpassword === $this->confirm_new_password) {
                 try {
                    $db->update('user', $params, array('id' => $this->id));
                } catch(Exception $e) {
                    throw $e;
                }
            } else {
                throw new ValidationException('Password Mismatch');
            }
        } else {
                throw new ValidationException('Password Mismatch');
        }
    }

    public static function getByUserId($user_id)
    {
        $user = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM user WHERE id = ?", array($user_id));

        if (!$rows) {
            throw new RecordNotFoundException('No Record found');
        }

        foreach ($rows as $row) {
            $user[] = new self($row);
        }
        return $user;
    }

    public static function getImage($id)
    {
        $db = DB::conn();

        $image = $db->row('SELECT image FROM user WHERE id = ?', array($id));
        if (!$image) {
            throw new RecordNotFoundException('Image not found');
        }
        return $image['image'];
    }

    public function createImage($default_image, $id)
    {
        try {
            $db = DB::conn();
            $db->update('user', array('image' => $default_image), array('id' => $id));
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function uploadImage($target_file, $id)
    {
        try {
            $db = DB::conn();
            $db->update('user', array('image' => $target_file), array('id' => $id));
        } catch (Exception $e) {
            throw $e;
        }
    }
}
