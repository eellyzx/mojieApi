<?php
namespace app\logic;

class BaseLogic
{
    static protected $ins = [];
    /**
     * @return static
     */
    static public function getInstance(){
        $cName = get_called_class();
        if( !isset(static::$ins[$cName]) ){
            static::$ins[$cName] = new static;;
        }
        return static::$ins[$cName];
    }
}