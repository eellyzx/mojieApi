<?php
namespace app\logic\cache;

use app\logic\BaseLogic;
use think\facade\Cache;

/**
 * token
 * Class tokenLogic
 * @package app\logic\cache
 */
class tokenLogic extends BaseLogic
{
    /**
     * token装缓存key
     * @param $token
     */
    public function tokenToCache($token)
    {
        $key = md5($token);
        Cache::set($key,$token,3600);
        return $key;
    }

    /**
     * token转字符串
     * @param $token
     */
    public function tokenToString($token)
    {
        return (string)Cache::get($token);
    }
}