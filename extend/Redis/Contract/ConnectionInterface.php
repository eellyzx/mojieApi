<?php declare(strict_types=1);
/*
 * @Author: zane
 * @Date: 2020-03-25 14:19:48
 * @LastEditTime: 2020-07-03 12:01:18
 * @Description: 
 */
namespace Redis\Contract;

/**
 * @since 1.0.0
 */
interface ConnectionInterface
{

    /**
     * @param string $key
     * @param array  $keys
     *
     * @return array
     */
    public function hMGet(string $key, array $keys): array;

    /**
     * @param string $key
     * @param array  $scoreValues
     *
     * @return int
     */
    public function zAdd(string $key, array $scoreValues): int;

    /**
     * @param array $keys
     *
     * @return array
     */
    public function mget(array $keys): array;

    /**
     * @param array $keyValues
     * @param int   $ttl
     *
     * @return bool
     */
    public function mset(array $keyValues, int $ttl = 0): bool;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param string   $key
     * @param mixed    $value
     * @param int|null $timeout
     *
     * @return bool
     */
    public function set(string $key, $value, int $timeout = null): bool;

    /**
     * Execute commands in a pipeline.
     *
     * @param callable $callback
     *
     * @return array
     */
    public function pipeline(callable $callback): array;

    /**
     * Execute commands in a transaction.
     *
     * @param callable $callback
     *
     * @return array
     */
    public function transaction(callable $callback): array;
}