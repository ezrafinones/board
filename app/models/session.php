<?php
class Session extends AppModel
{
    public static function setSession($session_name, $value)
    {
        $_SESSION["$session_name"] = $value;
    }
    public static function getSession($session_name)
    {
        return $_SESSION["$session_name"];
    }
}
