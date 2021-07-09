<?php
/*
 * @Author: zane
 * @Date: 2020-01-22 15:39:12
 * @LastEditTime: 2020-07-10 12:08:38
 * @Description: 
 */
namespace Redis;

use redis\Connectors\PhpRedisConnector;
use redis\Connectors\PredisConnector;
use redis\Connection\connection;
use redis\Exception\RedisException;

class RedisManager
{

    const PHP_REDIS = 'phpredis';

    const P_REDIS = 'predis';

    private $driver = self::PHP_REDIS;

    protected $connection;

    protected $config;

    /**
     * 初始化Redis实例
     * 
     * @param array $options 缓存参数
     * @access public
     */
    public function __construct(array $options = [])
    {
        if(empty($options)){
            throw new RedisException("未配置Redis配置参数");          
        }

        $this->driver = $options['driver'] ?? self::PHP_REDIS;
        $this->config = $options;

        switch ($this->driver) {
            case self::PHP_REDIS:
                $this->connection = $this->connection(new PhpRedisConnector()); 
                break;
            case self::P_REDIS:
                $this->connection = $this->connection(new PredisConnector());
                break;
            default:
                throw new RedisException("暂不支持的Redis扩展类型");               
                break;
        }
        
    }

    public function getHandler()
    {
        return $this->connection;
    }

    public function setDriver(string $driver)
    {
        $this->driver = $driver;
    }

    private function connection($connector)
    {
        return $connector->connect($this->config);
    }
}