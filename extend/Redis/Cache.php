<?php
/*
 * @Author: zane
 * @Date: 2020-03-30 14:57:16
 * @LastEditTime: 2020-07-03 11:58:03
 * @Description: 
 */
namespace Redis;

use think\Config;
use redis\TpCache;
use redis\RedisManager;

class Cache
{
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
     * 使用的框架
     *
     * @var
     */
    private static $frameworkNode = 'Tp';

    /**
     * 自动初始化缓存
     * @access public
     * @param  array $options 配置数组
     * @return Driver
     */
    public static function init(array $options = [], $frameworkType)
    {
        // if (is_null(self::$handler)) {
        //     if (empty($options) && 'complex' == Config::get('cache.type')) {
        //         $default = Config::get('cache.default');
        //         // 获取默认缓存配置，并连接
        //         $options = Config::get('cache.' . $default['type']) ?: $default;
        //     } elseif (empty($options)) {
        //         $options = Config::get('cache');
        //     }

        //     self::$handler = self::connect($options);
        // }
        
        self::$frameworkNode = self::initFrameworkNode($frameworkType);

        return self::$frameworkNode;
    }

    /**
     * 获取缓存实例
     *
     * @param  $options
     * @return Redis
     */
    private static function connect($options)
    {
        $redisManager =  new RedisManager($options);
        $handler = $redisManager->getHandler();
        return $handler;
    }

    private static function initFrameworkNode($frameworkType)
    {
        switch ($frameworkType) {
            case 'Tp':
                return new TpCache();
                break;
            
            default:
                throw new \Exception("暂时不支持该框架");
                break;
        }
    }
    
    public static function __callStatic($method, $parameters){  
        //dump([$method, $parameters]);
        // dump(self::init([], 'Tp'));
        self::init([], 'Tp')->$method(...$parameters);
    }  
   
}