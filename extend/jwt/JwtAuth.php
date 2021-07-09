<?php


namespace jwt;

use Firebase\JWT\JWT;

class JwtAuth
{
    public static $key = 'example_key';
    public static $iss = 'youling';
    public static $aud = 'youling';

    public static function encode(array $array)
    {
        $payload = array(
            "iss" => self::$iss,
            "aud" => self::$aud,
            "iat" => time(),
            //"exp" => 0//过期时间
        );
        $payload = array_merge($payload,$array);
        return JWT::encode($payload, self::$key);
    }

    public static function decode($token)
    {
        return JWT::decode($token, self::$key, array('HS256'));
    }


}