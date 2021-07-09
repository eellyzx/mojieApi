<?php
/*
 * @Author: zane
 * @Date: 2020-06-28 14:53:35
 * @LastEditors: your name
 * @LastEditTime: 2020-12-16 16:11:29
 * @Description: file content
 */ 

 /**
  * redis数据库配置文件
  */
return [
    'redis' => [

        'client' => 'phpredis',

        'options' => [
            'cluster' => 'redis',
            'prefix' => 'bzel_',
        ],

        'default' => [
            'host' => env('CACHE.HOST'),
            'password' => env('CACHE.PASSWORD'),
            'port' => (int)env('CACHE.PORT'),
           // 'database' => env(),
        ],
    ]
];