<?php 
namespace app\exception;

use think\Exception;

class DingDingException extends Exception
{
    protected $code = 40001;
    protected $message = '钉钉执行错误';
}