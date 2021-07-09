<?php
namespace app\service;

use app\exception\LogicException;
use Redis\Redis;
use think\facade\Queue;

/**
 * 锁服务
 * Class LockService
 * @package app\service
 */
class LockService
{
    /**
     * 抢占锁
     * @param $lockVal 锁ID
     * @param $expire 过期时间
     */
    public static function lock($lockVal,$expire = 10)
    {
        // $cache = Redis::get($lockVal);
        // if ($cache){
        //     return false;
        // }
        return Redis::set($lockVal, 1, 'ex', $expire, 'nx');
    }

    /**
     * 释放锁
     * @param $lockVal
     */
    public static function unlock($lockVal)
    {
        return Redis::del($lockVal);
    }

    /**
     * 获取食物消费次数缓存
     * @param $userId
     */
    public static function getFoodConsumeLock($userId)
    {
        $maxCount = 70;
        $key    = 'user_consume_food_'.$userId;
        $number =  (int) Redis::get($key);
        if (empty($number)){
            Redis::setex($key, 60 , 1);
        }else{
            if ($number + 1 >= $maxCount){
                Redis::setex('user_close_status_'.$userId, 3600 , 1);
            }else{
                Redis::incr($key);
            }
        }
        return true;
    }
}