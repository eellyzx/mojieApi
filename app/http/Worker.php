<?php


namespace app\http;


use think\worker\Server;

class Worker extends Server
{
    protected $socket = 'ws://192.168.8.99:2345';

    public function onMessage($connection,$data)
    {
        $connection->send(json_encode($data));
    }
}