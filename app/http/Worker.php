<?php


namespace app\http;


use app\logic\cache\tokenLogic;
use app\model\monster\MapMonsterModel;
use GatewayWorker\Lib\Gateway;
use jwt\JwtAuth;
use think\worker\Server;

class Worker extends Server
{
    protected $socket = 'http://0.0.0.0:2345';

    public function onMessage($connection,$data)
    {
        $data = json_decode($data,true);
        $type = $data['type'] ?? '';
        $data = $data['data'] ?? '';
        $result = '';
        if ($type == 'ping'){
            $result = [
                'type' => 'pong',
            ];
        }elseif ($type == 'battle'){
            $this->ack();
        }elseif ($type == 'login'){
            $token = $data['token'] ?? '';
            $token = tokenLogic::getInstance()->tokenToString($token);
            $result = JwtAuth::decode($token);
            $userId = $result['user_id'];
            Gateway::bindUid();
        }

        echo "收到消息>>>>>>>>>>>>\r\n";
        var_export($data);
        echo "\r\n";
        echo "收到消息<<<<<<<<<<<<\r\n";
        $connection->send(json_encode($result));
    }

    public function onWorkerStart($worker)
    {
        $a = [0,1,2,3,4,5,6,7,8,9];
        var_export(array_slice($a,2,8));
    }

    /**
     * 攻击怪物
     * @param $userId
     * @param $mapId
     * @param $monsterId
     */
    public function ack($userId,$mapId,$monsterIds)
    {
        MapMonsterModel::getInstance()->where(['status' => 1,'map_monster_id' => $monsterIds])->update(['status' => 0]);
    }
}