<?php
class User extends AppModel                    
{
    public function register(UserInfo $userinfo)                    
    {
        if(!$userinfo->validate()){
            throw new ValidationException('invalid comment');
        }

        $db = DB::conn();
        $db->begin();
        $params = array('firstname' => $this->firstname, 
                        'lastname' => $this->lastname, 
                        'email' => $this->email, 
                        'username' => $this->username,  
                        'password'=> md5($this->password),  
        );   
        $db->insert('user',$params);
        $db->commit();
    }
    
    public static function login()
    {
        $db = DB::conn();
                      
    }
}