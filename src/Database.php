<?php
namespace GetMVC\Framework;


class Database {
    
    public static $PDO;
    public static $host;
    public static $user;
    public static $pass;
    public static $dbname;
    
    /**
     * @return PDO
     */
    public static function getPDO(){
        
        if (!(self::$PDO instanceof \PDO)){
            self::$PDO = new \PDO('mysql:host='.self::$host.';dbname='.self::$dbname.';charset=utf8', self::$user, self::$pass);
            self::$PDO->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$PDO->exec('SET NAMES UTF8');
        }
        
        return self::$PDO;
    }
    
    /**
     * Generate a string corresponding to current date time in a format acceped by mysql
     * Exemple: 2022-12-25 09:30:00
     */
    public static function get_datetime_now(){
        return date("Y-m-d H:i:s", time());
    }
    
}   
