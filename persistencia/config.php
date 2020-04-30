<?php
class Config {
    static $db = [
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'db' => 'poliparqueo' 
    ];

    public static function getConfig(){
        return self::$db;
    }
}
?>