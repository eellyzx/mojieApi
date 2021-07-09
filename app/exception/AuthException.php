<?php 
namespace app\exception;

use think\Exception;

/**
 * 用户权限异常
 */
class AuthException extends Exception
{
    protected $code = 30001;
    protected $message = '未登录';
}