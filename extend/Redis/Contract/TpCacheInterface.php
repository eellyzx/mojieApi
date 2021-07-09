<?php
/*
 * @Author: zane
 * @Date: 2020-03-30 15:08:31
 * @LastEditTime: 2020-07-03 12:01:25
 * @Description: 
 */
namespace Redis\Contract;

interface TpCacheInterface
{
    /**
     * 连接缓存驱动
     * @access public
     * @param  array       $options 配置数组
     * @param  bool|string $name    缓存连接标识 true 强制重新连接
     * @return Driver
     */
    public static function connect(array $options = [], $name = false);

    /**
     * 自动初始化缓存
     * @access public
     * @param  array $options 配置数组
     * @return Driver
     */
    public static function init(array $options = []);

    /**
     * 切换缓存类型 需要配置 cache.type 为 complex
     * @access public
     * @param  string $name 缓存标识
     * @return Driver
     */
    public static function store($name = '');

    /**
     * 判断缓存是否存在
     * @access public
     * @param  string $name 缓存变量名
     * @return bool
     */
    public static function has($name);

    /**
     * 读取缓存
     * @access public
     * @param  string $name    缓存标识
     * @param  mixed  $default 默认值
     * @return mixed
     */
    public static function get($name, $default = false);

    /**
     * 写入缓存
     * @access public
     * @param  string   $name   缓存标识
     * @param  mixed    $value  存储数据
     * @param  int|null $expire 有效时间 0为永久
     * @return boolean
     */
    public static function set($name, $value, $expire = null);

    /**
     * 自增缓存（针对数值缓存）
     * @access public
     * @param  string $name 缓存变量名
     * @param  int    $step 步长
     * @return false|int
     */
    public static function inc($name, $step = 1);

    /**
     * 自减缓存（针对数值缓存）
     * @access public
     * @param  string $name 缓存变量名
     * @param  int    $step 步长
     * @return false|int
     */
    public static function dec($name, $step = 1);

    /**
     * 删除缓存
     * @access public
     * @param  string $name 缓存标识
     * @return boolean
     */
    public static function rm($name);

    /**
     * 清除缓存
     * @access public
     * @param  string $tag 标签名
     * @return boolean
     */
    public static function clear($tag = null);

    /**
     * 读取缓存并删除
     * @access public
     * @param  string $name 缓存变量名
     * @return mixed
     */
    public static function pull($name);

    /**
     * 如果不存在则写入缓存
     * @access public
     * @param  string $name   缓存变量名
     * @param  mixed  $value  存储数据
     * @param  int    $expire 有效时间 0为永久
     * @return mixed
     */
    public static function remember($name, $value, $expire = null);

    /**
     * 缓存标签
     * @access public
     * @param  string       $name    标签名
     * @param  string|array $keys    缓存标识
     * @param  bool         $overlay 是否覆盖
     * @return Driver
     */
    public static function tag($name, $keys = null, $overlay = false);
}