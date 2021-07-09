<?php
/*
 * @Author: zane
 * @Date: 2020-03-30 14:57:16
 * @LastEditTime: 2020-12-16 16:42:50
 * @Description: 
 */
namespace Redis;

use think\Config;
use Redis\TpCache;
use Redis\LaRedis;
use Redis\RedisManager;
use Redis\Exception\RedisException;

class Redis
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
     * 使用的框架(La 或 Tp)
     *
     * @var
     */
    private static $frameworkType = 'La';

    private static $frameworkNode;

    //private $options;

    /**
     * 自动初始化缓存
     * @access public
     * @param  array $options 配置数组
     * @return Driver
     */
    public static function init(array $options = [], $frameworkType)
    {     
        self::$frameworkNode = self::initFrameworkNode($frameworkType);

        //$this->options = $options;
        return self::$frameworkNode;
    }

    /**
     * 初始化框架节点
     *
     * @param string $frameworkType
     * @return Redis
     */
    private static function initFrameworkNode($frameworkType)
    {
        switch ($frameworkType) {
            // case 'Tp':
            //     return new TpCache();
            //     break;
            case 'La':
                return new LaRedis();
            default:
                throw new RedisException("暂时不支持该框架", json_encode(['a' => 'b']));
                break;
        }
    }
    
    public static function __callStatic($method, $parameters){  
        $config = config('redis')['redis'];
        return self::init($config, self::$frameworkType)->$method(...$parameters);
    }  

    public function __call($method, $parameters){  
        $config = config('redis')['redis'];
        return self::init($config, self::$frameworkType)->$method(...$parameters);
    }  
   
}