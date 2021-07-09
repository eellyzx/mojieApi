<?php
/*
 * @Date: 2020-06-28 14:52:19
 * @LastEditors: your name
 * @LastEditTime: 2020-07-03 12:01:34
 * @Description: file content
 * @Author: zane
 */ 

namespace Redis\Exception;

use redis\Exception\RedisException;

interface InvalidArgumentException extends RedisException
{
}
