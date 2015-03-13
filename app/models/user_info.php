<?
class UserInfo extends AppModel
{
    public $validation = array(              
        'firstname' => array(
            'length' => array(                        
                'validate_between', 1, 254,
            ),        
        ),
    'lastname' => array(                    
        'length' => array(                
            'validate_between', 1, 254,
            ),
        ),
    'email' => array(                    
        'length' => array(                
            'validate_between', 1, 254,
            ),
        ),
    'username' => array(                    
        'length' => array(                
            'validate_between', 1, 20,
            ),
        ),
    'password' => array(                    
        'length' => array(                
            'validate_between', 1, 20,
            ),
        ),
    'validate_password' => array(                
        'length' => array(                
            'validate_between', 1, 20,
            ),
        ),
    );
}