<?php
/*
 * @Author: zane
 * @Date: 2020-03-25 11:02:02
 * @LastEditTime: 2020-07-03 12:02:04
 * @Description: 
 */
namespace Redis\Helper;
/**
 * 助手函数
 */
abstract class PhpHelper
{
    public static function tap($value, $callback)
    {
        $callback($value);

        return $value;
    }
}