<?php


namespace app\service;


use GatewayClient\Gateway;
use think\Exception;
use think\facade\Log;

/**
 * 推送服务
 * Class GatewayService
 * @package app\service
 */
class GatewayService
{
    /**
     * 发送到Gateway推送给用户
     * @param int $userId
     * @param array $data
     * @return bool|void
     */
    public static function sendToGateway($userId = 0 ,array $data = [])
    {
        if (empty($userId) || empty($data)){
            return ;
        }
        try {
            Gateway::$registerAddress = config('system.gateway_register_id');
            Gateway::sendToUid($userId, json_encode($data));
        }catch (Exception $e){
            //记录日志
            Log::record($e->getMessage(),'error');
        }
        return true;
    }
}