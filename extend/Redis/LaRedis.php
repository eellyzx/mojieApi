<?php
/*
 * @Author: zane
 * @Date: 2020-03-30 16:33:31
 * @LastEditTime: 2020-12-16 16:43:53
 * @Description: 
 */
namespace Redis;

use think\Config;
use think\cache\Driver;
use Redis\RedisManager;
use Redis\Support\Arr;
use Redis\Exception\RedisException;

class LaRedis
{
    protected static $options = [
        'host'       => '127.0.0.1',
        'port'       => 6379,
        'password'   => '',
        'select'     => 0,
        'timeout'    => 0,
        'expire'     => 0,
        'persistent' => false,
        'prefix'     => '',
    ];
    
    /**
     * @var array 缓存的实例
     */
    public static $instance = [];

    /**
     * @var int 缓存读取次数
     */
    public static $readTimes = 0;

    /**
     * @var int 缓存写入次数
     */
    public static $writeTimes = 0;

    /**
     * @var object 操作句柄
     */
    public static $handler;

    /**
     * 自动初始化缓存
     * @access public
     * @param  array $options 配置数组
     * @return Driver
     */
    public static function init(array $options = [])
    {   
        if(empty($options)){
            $options = config('redis')['redis'];
            //throw new RedisException("缺少Redis必要配置");
        }
       // $options = array_merge(self::$options, $options);
        return self::connect($options);
    }

    public static function connect($options)
    {  
        return new RedisManager('phpredis', $options);
    }

    public  function __call($method, $parameters){  
      
        return self::init()->$method(...$parameters);
    }  
}
