<?php
class User extends AppModel
{
    const MIN_LENGTH = 1;

    const MAX_NAME_LENGTH = 254;
    const MAX_EMAIL_LENGTH = 30; 
    const MAX_USERNAME_LENGTH = 30;
    const MAX_PASSWORD_LENGTH = 254;

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
        $row = $db->row('SELECT * FROM user 
                    WHERE username = ?', array($this->username));
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

    public static function getAll()
    {
        $user = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM user 
                        WHERE id = ?", array($_SESSION['id']));

        foreach($rows as $row) {
            $user[] = new self($row);
        }
        return $user;
    }

    public function getId()
    {
        $db = DB::conn();
        $id = $db->row("SELECT id FROM user 
                    WHERE id = ?", array($_SESSION['id']));
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
}
