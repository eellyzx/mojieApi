<?php
/*
 * @Author: zane
 * @Date: 2020-01-22 16:01:50
 * @LastEditTime: 2020-07-03 12:02:29
 * @Description: 
 */

namespace Redis\connections;

use Redis\Exception\RedisException;
use Redis\Contract\ConnectionInterface;
use Closure;
use Redis;
use RedisCluster;
use PhpHelper;

abstract class Connection 
//implements ConnectionInterface
{
    /**
     * 执行Redis可使用的方法
     *
     * @param string $method
     * @param array $parameters
     * @param boolean $reconnect
     * @return void
     */
    public function command(string $method, array $parameters = [], bool $reconnect = false)
    {
        try {
            $lowerMethod = strtolower($method);
            if (!in_array($lowerMethod, $this->supportedMethods, true)) {
                throw new RedisException(
                    sprintf('Method(%s) is not supported!', $method)
                );
            }
            
            $result = $this->client->{$method}(...$parameters);
           // dump([$method, $result]);
        } catch (RedisException $e) {
            // if (!$reconnect && $this->reconnect()) {
            //     return $this->command($method, $parameters, true);
            // }
            throw new RedisException(
                sprintf('Redis command reconnect error(%s)', $e->getMessage())
            );
        }

        return $result;
    }

    // use Macroable {
    //     __call as macroCall;
    // }

    /**
     * The Redis client.
     *
     * @var \Redis
     */
    protected $client;

    /**
     * The Redis connection name.
     *
     * @var string|null
     */
    protected $name;

    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * Subscribe to a set of given channels for messages.
     *
     * @param  array|string  $channels
     * @param  \Closure  $callback
     * @param  string  $method
     * @return void
     */
    abstract public function createSubscription($channels, Closure $callback, $method = 'subscribe');

    // /**
    //  * Funnel a callback for a maximum number of simultaneous executions.
    //  *
    //  * @param  string  $name
    //  * @return \Illuminate\Redis\Limiters\ConcurrencyLimiterBuilder
    //  */
    // public function funnel($name)
    // {
    //     return new ConcurrencyLimiterBuilder($this, $name);
    // }

    // /**
    //  * Throttle a callback for a maximum number of executions over a given duration.
    //  *
    //  * @param  string  $name
    //  * @return \Illuminate\Redis\Limiters\DurationLimiterBuilder
    //  */
    // public function throttle($name)
    // {
    //     return new DurationLimiterBuilder($this, $name);
    // }

    /**
     * Get the underlying Redis client.
     *
     * @return mixed
     */
    public function client()
    {
        return $this->client;
    }

    /**
     * Subscribe to a set of given channels for messages.
     *
     * @param  array|string  $channels
     * @param  \Closure  $callback
     * @return void
     */
    public function subscribe($channels, Closure $callback)
    {
        return $this->createSubscription($channels, $callback, __FUNCTION__);
    }

    /**
     * Subscribe to a set of given channels with wildcards.
     *
     * @param  array|string  $channels
     * @param  \Closure  $callback
     * @return void
     */
    public function psubscribe($channels, Closure $callback)
    {
        return $this->createSubscription($channels, $callback, __FUNCTION__);
    }

    /**
     * Fire the given event if possible.
     *
     * @param  mixed  $event
     * @return void
     */
    protected function event($event)
    {
        if (isset($this->events)) {
            $this->events->dispatch($event);
        }
    }

    /**
     * Register a Redis command listener with the connection.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function listen(Closure $callback)
    {
        if (isset($this->events)) {
            $this->events->listen(CommandExecuted::class, $callback);
        }
    }

    /**
     * Get the connection name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the connections name.
     *
     * @param  string  $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the event dispatcher used by the connection.
     *
     * @return \Illuminate\Contracts\Events\Dispatcher
     */
    public function getEventDispatcher()
    {
        return $this->events;
    }

    /**
     * Set the event dispatcher instance on the connection.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function setEventDispatcher(Dispatcher $events)
    {
        $this->events = $events;
    }

    /**
     * Unset the event dispatcher instance on the connection.
     *
     * @return void
     */
    public function unsetEventDispatcher()
    {
        $this->events = null;
    }

    // /**
    //  * Pass other method calls down to the underlying client.
    //  *
    //  * @param  string  $method
    //  * @param  array  $parameters
    //  * @return mixed
    //  */
    // public function __call($method, $parameters)
    // {
    //     if (static::hasMacro($method)) {
    //         return $this->macroCall($method, $parameters);
    //     }

    //     return $this->command($method, $parameters);
    // }
}