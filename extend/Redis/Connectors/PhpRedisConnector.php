<?php
/*
 * @Author: zane
 * @Date: 2020-03-25 11:29:55
 * @LastEditTime: 2020-07-03 11:58:19
 * @Description: 
 */
namespace Redis\Connectors;

use Redis\Contract\ConnectorInterface;
use Redis\Connections\PhpRedisClusterConnection;
use Redis\Connections\PhpRedisConnection;
use Redis;
use RedisCluster;
use Redis\Exception\RedisException;
use Redis\Support\Arr;

/**
 * @since 1.0.0
 */
class PhpRedisConnector 
//implements ConnectorInterface
{
    // /**
    //  * 创建PhpRedis连接
    //  *
    //  * @param array $config
    //  * @param array $options
    //  * @return PhpRedisConnection
    //  */
    // public function connect(array $config, array $options = [])
    // {
        
    //     $client = new Redis();

    //     $this->establishConnection($client, $config);

    //     if (! empty($config['password'])) {
    //         $client->auth($config['password']);
    //     }

    //     if (isset($config['database'])) {
    //         $client->select((int) $config['database']);
    //     }

    //     if (! empty($config['prefix'])) {
    //         $client->setOption(Redis::OPT_PREFIX, $config['prefix']);
    //     }

    //     if (! empty($config['read_timeout'])) {
    //         $client->setOption(Redis::OPT_READ_TIMEOUT, $config['read_timeout']);
    //     }

    //     return new PhpRedisConnection($client);
        
    // }

    // /**
    //  * Create a new clustered PhpRedis connection.
    //  *
    //  * @param  array  $config
    //  * @param  array  $clusterOptions
    //  * @param  array  $options
    //  * @return \Illuminate\Redis\Connections\PhpRedisClusterConnection
    //  */
    // public function connectToCluster(array $config, array $clusterOptions, array $options)
    // {
    //     $options = array_merge($options, $clusterOptions, Arr::pull($config, 'options', []));

    //     return new PhpRedisClusterConnection($this->createRedisClusterInstance(
    //         array_map([$this, 'buildClusterConnectionString'], $config), $options
    //     ));
    // }

    // /**
    //  * Build a single cluster seed string from array.
    //  *
    //  * @param  array  $server
    //  * @return string
    //  */
    // protected function buildClusterConnectionString(array $server)
    // {
    //     return $server['host'].':'.$server['port'].'?'.Arr::query(Arr::only($server, [
    //         'database', 'password', 'prefix', 'read_timeout',
    //     ]));
    // }

    // /**
    //  * Establish a connection with the Redis host.
    //  *
    //  * @param  \Redis  $client
    //  * @param  array  $config
    //  * @return void
    //  */
    // protected function establishConnection($client, array $config)
    // {
    //     $persistent = $config['persistent'] ?? false;
    //     $persistentId = $config['persistent_id'] ?? null;
    //     $parameters = [
    //         $config['host'],
    //         $config['port'],
    //         $config['timeout'],
    //         $persistentId,
    //     ];
    //     if(isset($config['retry_interval'])){
    //         $parameters[] = $config['retry_interval'];
    //     }

    //     if (version_compare(phpversion('redis'), '3.1.3', '>=')) {
    //         if(isset($config['read_timeout'])){
    //             $parameters[] = $config['read_timeout'];
    //         }
    //     }

    //     $result = $client->{($persistent ? 'pconnect' : 'connect')}(...$parameters);
    //     if ($result === false) {
    //         throw new RedisException(
    //             sprintf('Redis connect error(%s)',
    //             json_decode($parameters, JSON_UNESCAPED_UNICODE)
    //             )
    //         );
    //     }
    // }

    // /**
    //  * Create a new redis cluster instance.
    //  *
    //  * @param  array  $servers
    //  * @param  array  $options
    //  * @return \RedisCluster
    //  */
    // protected function createRedisClusterInstance(array $servers, array $options)
    // {
    //     $parameters = [
    //         null,
    //         array_values($servers),
    //         $options['timeout'] ?? 0,
    //         $options['read_timeout'] ?? 0,
    //         isset($options['persistent']) && $options['persistent'],
    //     ];

    //     if (version_compare(phpversion('redis'), '4.3.0', '>=')) {
    //         $parameters[] = $options['password'] ?? null;
    //     }

    //     return tap(new RedisCluster(...$parameters), function ($client) use ($options) {
    //         if (! empty($options['prefix'])) {
    //             $client->setOption(RedisCluster::OPT_PREFIX, $options['prefix']);
    //         }
    //     });
    // }

        /**
     * Create a new clustered PhpRedis connection.
     *
     * @param  array  $config
     * @param  array  $options
     * @return \Illuminate\Redis\Connections\PhpRedisConnection
     */
    public function connect(array $config, array $options)
    {
        $connector = function () use ($config, $options) {
            return $this->createClient(array_merge(
                $config, $options, Arr::pull($config, 'options', [])
            ));
        };

        return new PhpRedisConnection($connector(), $connector);
    }

    /**
     * Create a new clustered PhpRedis connection.
     *
     * @param  array  $config
     * @param  array  $clusterOptions
     * @param  array  $options
     * @return \Illuminate\Redis\Connections\PhpRedisClusterConnection
     */
    public function connectToCluster(array $config, array $clusterOptions, array $options)
    {
        $options = array_merge($options, $clusterOptions, Arr::pull($config, 'options', []));

        return new PhpRedisClusterConnection($this->createRedisClusterInstance(
            array_map([$this, 'buildClusterConnectionString'], $config), $options
        ));
    }

    /**
     * Build a single cluster seed string from array.
     *
     * @param  array  $server
     * @return string
     */
    protected function buildClusterConnectionString(array $server)
    {
        return $server['host'].':'.$server['port'].'?'.Arr::query(Arr::only($server, [
            'database', 'password', 'prefix', 'read_timeout',
        ]));
    }

    /**
     * Create the Redis client instance.
     *
     * @param  array  $config
     * @return \Redis
     *
     * @throws \LogicException
     */
    protected function createClient(array $config)
    {
        return tap(new Redis, function ($client) use ($config) {
            if ($client instanceof RedisFacade) {
                throw new LogicException(
                        extension_loaded('redis')
                                ? 'Please remove or rename the Redis facade alias in your "app" configuration file in order to avoid collision with the PHP Redis extension.'
                                : 'Please make sure the PHP Redis extension is installed and enabled.'
                );
            }

            $this->establishConnection($client, $config);

            if (! empty($config['password'])) {
                $client->auth($config['password']);
            }

            if (isset($config['database'])) {
                $client->select((int) $config['database']);
            }

            if (! empty($config['prefix'])) {
                $client->setOption(Redis::OPT_PREFIX, $config['prefix']);
            }

            if (! empty($config['read_timeout'])) {
                $client->setOption(Redis::OPT_READ_TIMEOUT, $config['read_timeout']);
            }
        });
    }

    /**
     * Establish a connection with the Redis host.
     *
     * @param  \Redis  $client
     * @param  array  $config
     * @return void
     */
    protected function establishConnection($client, array $config)
    {
        $persistent = $config['persistent'] ?? false;

        $parameters = [
            $config['host'],
            $config['port'],
            Arr::get($config, 'timeout', 0.0),
            $persistent ? Arr::get($config, 'persistent_id', null) : null,
            Arr::get($config, 'retry_interval', 0),
        ];

        if (version_compare(phpversion('redis'), '3.1.3', '>=')) {
            $parameters[] = Arr::get($config, 'read_timeout', 0.0);
        }

        $client->{($persistent ? 'pconnect' : 'connect')}(...$parameters);
    }

    /**
     * Create a new redis cluster instance.
     *
     * @param  array  $servers
     * @param  array  $options
     * @return \RedisCluster
     */
    protected function createRedisClusterInstance(array $servers, array $options)
    {
        $parameters = [
            null,
            array_values($servers),
            $options['timeout'] ?? 0,
            $options['read_timeout'] ?? 0,
            isset($options['persistent']) && $options['persistent'],
        ];

        if (version_compare(phpversion('redis'), '4.3.0', '>=')) {
            $parameters[] = $options['password'] ?? null;
        }

        return tap(new RedisCluster(...$parameters), function ($client) use ($options) {
            if (! empty($options['prefix'])) {
                $client->setOption(RedisCluster::OPT_PREFIX, $options['prefix']);
            }
        });
    }
}

function tap($value, $callback = null)
{
    // if (is_null($callback)) {
    //     return new HigherOrderTapProxy($value);
    // }

    $callback($value);

    return $value;
}