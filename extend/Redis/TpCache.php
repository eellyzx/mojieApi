<?php
/*
 * @Author: zane
 * @Date: 2020-03-30 16:33:31
 * @LastEditTime: 2020-06-09 10:27:12
 * @Description: 
 */
namespace Redis;

use think\Config;
use think\cache\Driver;
use Redis\RedisManager;

/**
 * 适应于TP框架的
 */
class TpCache
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
        if (is_null(self::$handler)) {
            if (empty($options) && 'complex' == Config::get('cache.type')) {
                $default = Config::get('cache.default');
                // 获取默认缓存配置，并连接
                $options = Config::get('cache.' . $default['type']) ?: $default;
            } elseif (empty($options)) {
                $options = Config::get('cache');
            }
            if (!empty($options)) {
                $options = array_merge(self::$options, $options);
            }
           
            self::$handler = self::connect($options)->getHandler();
           
            self::$options = $options;
        }
       
        return self::$handler;
    }

    public static function connect($options)
    {  
        $redisManager =  new RedisManager($options);
        $handler = $redisManager->getHandler();
        return $handler;
    }
    
    /**
     * 获取实际的缓存标识
     * @access public
     * @param string $name 缓存名
     * @return string
     */
    protected function getCacheKey($name)
    {
        return self::$options['prefix'] . $name;
    }

    /**
     * 读取缓存并删除
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function pull($name)
    {
        $result = $this->get($name, false);
        if ($result) {
            $this->rm($name);
            return $result;
        } else {
            return;
        }
    }

    /**
     * 如果不存在则写入缓存
     * @access public
     * @param string    $name 缓存变量名
     * @param mixed     $value  存储数据
     * @param int       $expire  有效时间 0为永久
     * @return mixed
     */
    public function remember($name, $value, $expire = null)
    {
        if (!$this->has($name)) {
            $time = time();
            while ($time + 5 > time() && $this->has($name . '_lock')) {
                // 存在锁定则等待
                usleep(200000);
            }

            try {
                // 锁定
                $this->set($name . '_lock', true);
                if ($value instanceof \Closure) {
                    $value = call_user_func($value);
                }
                $this->set($name, $value, $expire);
                // 解锁
                $this->rm($name . '_lock');
            } catch (\Exception $e) {
                // 解锁
                $this->rm($name . '_lock');
                throw $e;
            } catch (\throwable $e) {
                $this->rm($name . '_lock');
                throw $e;
            }
        } else {
            $value = $this->get($name);
        }
        return $value;
    }

    /**
     * 缓存标签
     * @access public
     * @param string        $name 标签名
     * @param string|array  $keys 缓存标识
     * @param bool          $overlay 是否覆盖
     * @return $this
     */
    public function tag($name, $keys = null, $overlay = false)
    {
        if (is_null($name)) {

        } elseif (is_null($keys)) {
            $this->tag = $name;
        } else {
            $key = 'tag_' . md5($name);
            if (is_string($keys)) {
                $keys = explode(',', $keys);
            }
            $keys = array_map([$this, 'getCacheKey'], $keys);
            if ($overlay) {
                $value = $keys;
            } else {
                $value = array_unique(array_merge($this->getTagItem($name), $keys));
            }
            $this->set($key, implode(',', $value), 0);
        }
        return $this;
    }

    /**
     * 更新标签
     * @access public
     * @param string $name 缓存标识
     * @return void
     */
    protected function setTagItem($name)
    {
        if ($this->tag) {
            $key       = 'tag_' . md5($this->tag);
            $this->tag = null;
            if ($this->has($key)) {
                $value   = explode(',', $this->get($key));
                $value[] = $name;
                $value   = implode(',', array_unique($value));
            } else {
                $value = $name;
            }
            $this->set($key, $value, 0);
        }
    }

    /**
     * 获取标签包含的缓存标识
     * @access public
     * @param string $tag 缓存标签
     * @return array
     */
    protected function getTagItem($tag)
    {
        $key   = 'tag_' . md5($tag);
        $value = $this->get($key);
        if ($value) {
            return array_filter(explode(',', $value));
        } else {
            return [];
        }
    }
    

    /**
     * 判断缓存
     * @access public
     * @param string $name 缓存变量名
     * @return bool
     */
    public function has($name)
    {
        return self::$handler->exists($this->getCacheKey($name));
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed  $default 默认值
     * @return mixed
     */
    public function get($name, $default = false)
    {
        $value = self::init()->get($this->getCacheKey($name));
        if (is_null($value) || false === $value) {
            return $default;
        }

        try {
            $result = 0 === strpos($value, 'think_serialize:') ? unserialize(substr($value, 16)) : $value;
        } catch (\Exception $e) {
            $result = $default;
        }

        return $result;
    }

    /**
     * 写入缓存
     * @access public
     * @param string            $name 缓存变量名
     * @param mixed             $value  存储数据
     * @param integer|\DateTime $expire  有效时间（秒）
     * @return boolean
     */
    public function set($name, $value, $expire = null)
    {
        if (is_null($expire)) {
            $expire = $this->options['expire'];
        }
        if ($expire instanceof \DateTime) {
            $expire = $expire->getTimestamp() - time();
        }
        // if ($this->tag && !$this->has($name)) {
        //     $first = true;
        // }
        $key   = $this->getCacheKey($name);
        $value = is_scalar($value) ? $value : 'think_serialize:' . serialize($value);
        
        if ($expire) {
            $result = self::init()->setex($key, $expire, $value);
            dump($result);die;
        } else {
            $result = self::init()->set($key, $value);
        }

       // isset($first) && $this->setTagItem($key);
        return $result;
    }

    /**
     * 自增缓存（针对数值缓存）
     * @access public
     * @param  string    $name 缓存变量名
     * @param  int       $step 步长
     * @return false|int
     */
    public function inc($name, $step = 1)
    {
        $key = $this->getCacheKey($name);

        return self::$handler->incrby($key, $step);
    }

    /**
     * 自减缓存（针对数值缓存）
     * @access public
     * @param  string    $name 缓存变量名
     * @param  int       $step 步长
     * @return false|int
     */
    public function dec($name, $step = 1)
    {
        $key = $this->getCacheKey($name);

        return self::$handler->decrby($key, $step);
    }

    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function rm($name)
    {
        //return self::$handler->delete($this->getCacheKey($name));
        return self::$handler->del($this->getCacheKey($name));
    }

    /**
     * 清除缓存
     * @access public
     * @param string $tag 标签名
     * @return boolean
     */
    public function clear($tag = null)
    {
        if ($tag) {
            // 指定标签清除
            $keys = $this->getTagItem($tag);
            foreach ($keys as $key) {
                self::$handler->del($key);
                // self::$handler->delete($key);
            }
            $this->rm('tag_' . md5($tag));
            return true;
        }
        return self::$handler->flushDB();
    }


}
