<?php

namespace app\exception;


use think\Exception;

class LogicException extends Exception
{
    public $code = 1;
}