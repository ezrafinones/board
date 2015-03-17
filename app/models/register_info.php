<?php
class RegisterInfo extends AppModel
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
}
