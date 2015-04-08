<?php
class User extends AppModel
{
    const MIN_LENGTH = 1;

    const MAX_NAME_LENGTH = 254;
    const MAX_EMAIL_LENGTH = 254;
    const MAX_USERNAME_LENGTH = 20;
    const MAX_PASSWORD_LENGTH = 20;

    const MAX_FILE_UPLOAD = 500000;

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
        $db->begin();
        $db->insert('user', $params);
        $db->commit();
    }

    public function login(LoginInfo $login_info)
    {
        $login_info->validate();
        if ($login_info->hasError()) {
            throw new ValidationException('invalid input');
        }
        $db = DB::conn();
        $db->begin();
        $params = array($this->username, md5($this->password));
        $row = $db->row('SELECT * FROM user WHERE username = ? AND password = ?', $params);

        if (!$row) {
            throw new RecordNotFoundException('No Record Found');
        }
        Session::set('username', $this->username);
        Session::set('id', $row['id']);
    }

    public static function getUserById()
    {
        $user = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM user WHERE id = ?", array(Session::get('id')));

        foreach ($rows as $row) {
            $user[] = new self($row);
        }

        if (!$rows) {
            throw new RecordNotFoundException('No Record found');
        }
        return $user;
    }

    public function getById()
    {
        $db = DB::conn();
        $id = $db->row("SELECT id FROM user WHERE id = ?", array(Session::get('id')));
        $this->id = $id['id'];
    }

    public function updateProfile()
    {
        $params = array();
        $temp = array('firstname' => $this->firstname,
                    'lastname' => $this->lastname,
                    'email' => $this->email,
        );

        foreach ($temp as $k => $v) {
            if (!empty($v)) {
                $params[$k] = $v;
            }
        }

        if (!empty($params)) {
            try {
                $db = DB::conn();
                $db->begin();
                $db->update('user', $params, array('id' => $this->id));
                $db->commit();
            } catch (Exception $e) {
                $db->rollback();
                throw $e;
            }
        }
    }

    public function updatePassword()
    {
        if (!empty($this->newpassword) && !empty($this->cnewpassword)) {
            $params = array('password' => md5($this->newpassword),
                        'validate_password' => md5($this->cnewpassword),
            );
            $db = DB::conn();
            $row = $db->row('SELECT password FROM user WHERE id = ?', array(Session::get('id')));

            if ($row['password'] === md5($this->password) && $this->newpassword === $this->cnewpassword) {
                 try {
                    $db->begin();
                    $db->update('user', $params, array('id' => $this->id));
                    $db->commit();
                } catch(Exception $e) {
                    $db->rollback();
                    throw $e;
                }
            } else {
                throw new ValidationException('Password Mismatch');
            }
        } else {
                throw new ValidationException('Password Mismatch');
        }
    }

    public static function getUserByUserId($user_id)
    {
        $user = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM user WHERE id = ?", array($user_id));

        foreach ($rows as $row) {
            $user[] = new self($row);
        }

        if (!$rows) {
            throw new RecordNotFoundException('No Record found');
        }
        return $user;
    }

    public static function getImage()
    {
        $db = DB::conn();

        $image = $db->row('SELECT image FROM user WHERE id = ?', array(Session::get('id')));
        if (!$image) {    
            throw new RecordNotFoundException('Image not found');
        }
        return $image['image'];
    }

    public function createImage($default_image)
    {
        $db = DB::conn();
        try {
            $db->begin();
            $db->update('user', array('image' => $default_image), array('id' => Session::get('id')));
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
    }

    public static function uploadImage($target_file)
    {
        $db = DB::conn();
        try {
            $db->begin();
            $db->update('user', array('image' => $target_file), array('id' => Session::get('id')));
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
    }
}
