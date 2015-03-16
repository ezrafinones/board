<?php
class User extends AppModel                    
{
    public $validation = array(              
        'username' => array(
            'length' => array(                        
                'validate_between', 1, 254,
            ),        
        ),
        'password' => array(                    
            'length' => array(                
                'validate_between', 1, 254,
            ),
        ),
    );
    
    public function register(UserInfo $userinfo)                    
    {   
        $userinfo->validate();
        if($userinfo->hasError()){
            throw new ValidationException('invalid input');
        }

        $db = DB::conn();
        $db->begin();
        $params = array('firstname' => $this->firstname, 
                        'lastname' => $this->lastname, 
                        'email' => $this->email, 
                        'username' => $this->username,  
                        'password'=> md5($this->password),  
        );   

        $row = $db->row('SELECT * FROM user WHERE username = ?', array($this->username));
        if (!$row) {
            $db->insert('user',$params);
            $db->commit();     
        } 
        else
        {
            throw new RecordFoundException('Record Found');
        }
    }
    
    public function login()
    {
        $this->validate();
        if ($this->hasError()){
            throw new ValidationException('invalid input');
        }

        $db = DB::conn();   
        $db->begin();
        $params = array($this->username, md5($this->password));       
        $row = $db->row('SELECT * FROM user WHERE username = ? AND password = ?', $params);     
        if (!$row) {
            throw new RecordNotFoundException('No Record Found');
        } 
        $_SESSION['username'] = $this->username;
    }
}