<?php

namespace tools;

class Strings
{
    /**
     * 数学加法
     * @param mixed ...$strings
     * @return string
     */
    public static function mathAdd(...$strings)
    {
        $sum = '0';
        foreach ($strings as $string){
            $sum = bcadd((string)$sum, (string)$string, 2);
        }
        return self::rtrimInvalidZero($sum);
    }

    /**
     * 数学减法
     * @param $string1
     * @param $string2
     */
    public static function mathSub($string1, $string2)
    {
        $string = bcsub((string)$string1, (string)$string2, 2);
        return self::rtrimInvalidZero($string);
    }

    /**
     * 数学除法，左边除以右边
     * @param $string1
     * @param $string2
     */
    public static function mathDiv($string1, $string2)
    {
        if (empty($string1) || empty($string2)){
            return 0;
        }
        $string = bcdiv((string)$string1, (string)$string2, 2);
        return self::rtrimInvalidZero($string);
    }

    /**
     * 数学乘法
     * @param $string1
     * @param $string2
     * @param $decimalPlace 保留几位小数
     * @return string
     */
    public static function mathMul($string1, $string2, $decimalPlace = 2)
    {
        $string = bcmul((string)$string1, (string)$string2, $decimalPlace);
        return self::rtrimInvalidZero($string);
    }

    /**
     * 比较两个数字
     * @param $string1
     * @param $string2
     */
    public static function mathComp($string1, $string2)
    {
        return bccomp((string)$string1, (string)$string2, 4);
    }

    /**
     * 取随机数,可取小数
     * @param $minNumber 最小值
     * @param $maxNumber 最大值
     * @param int $decimalPlace 多少位小数,浮点数随机要传位数
     */
    public static function randomNumber($minNumber,$maxNumber,$decimalPlace = 0)
    {
        $double = (string)pow(10,$decimalPlace);

        $minNumber = bcmul((string)$minNumber,$double);
        $maxNumber = bcmul((string)$maxNumber,$double);
        if ($minNumber > $maxNumber){
            list($minNumber,$maxNumber) = [$maxNumber,$minNumber];
        }
        $rand = mt_rand($minNumber,$maxNumber);
        $string = bcdiv((string)$rand,$double,$decimalPlace);

        return self::rtrimInvalidZero($string);
    }

    /**
     * 获取图片的完整地址
     * @return string
     */
    public static function getImg($file)
    {
        if (empty($file)) {
            return '';
        }

        if (strpos($file, "http") === 0) {
            return $file;
        } else if (strpos($file, "/") === 0) {
            return $file;
        } else {
            $picUrl = config('system.pic_url');
            return $picUrl.$file;
        }
    }

    /**
     * 去除无效0
     * @param $string
     * @return string
     */
    public static function rtrimInvalidZero($string)
    {
        if ($string == '0'){
            return $string;
        }else{
            $offset = strpos($string,'.');
            if ($offset === false){
                return $string;
            }else{
                return trim(rtrim($string, '0'),'.');
            }
        }
    }
}