<?php

namespace GetMVC\Framework;


class Configs {
    
    public static $appName;
    public static $rootPath;
    
    public static $dbHost;
    public static $dbUser;
    public static $dbPass;
    public static $dbName;
    
    
    /**
     * Return an array of all configs
     * @return array
     */
    public static function GetAll() {
            $c = new \ReflectionClass('\GetMVC\Framework\Configs');
        return $c->getStaticProperties();
    }

}
