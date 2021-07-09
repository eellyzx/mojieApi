<?php 
namespace app\exception;

use think\Exception;

class SignException extends Exception
{
    protected $code = 50001;
    protected $message = '签名异常';
}