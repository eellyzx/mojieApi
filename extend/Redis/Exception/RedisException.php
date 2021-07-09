<?php 
/*
 * @Date: 2020-06-28 14:52:19
 * @LastEditors: your name
 * @LastEditTime: 2020-07-10 12:14:44
 * @Description: file content
 * @Author: zane
 * @FilePath: /youling/glts/xcx/extend/Redis/Exception/RedisException.php
 */ 


namespace Redis\Exception;

use Exception;

/**
 * redis异常捕获
 * 
 * @author zane 
 */
class RedisException extends Exception
{
    public function __construct($message, $config= '', $result = '')
    {
        $this->message = $message;

        if(!empty($config)){
            $this->data['config'] = config('redis')['redis'];
        }
        if(!empty($result)){
            $this->data['request_result'] = $result;
        }
    }
}