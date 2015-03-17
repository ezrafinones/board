<?php
class LoginInfo extends AppModel
{
    const MIN_LENGTH = 1;

    const MAX_USERNAME_LENGTH = 30;
    const MAX_PASSWORD_LENGTH = 254;

    public $validation = array(
        'username' => array(
            'length' => array(
                'validate_between', self::MIN_LENGTH, self::MAX_USERNAME_LENGTH,
            ),
        ),
        'password' => array(
            'length' => array(
                'validate_between', self::MIN_LENGTH, self::MAX_PASSWORD_LENGTH,
            ),
        ),
    );
}