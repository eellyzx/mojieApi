<?php
namespace app\validate\box;

use app\constant\ModuleConstant;
use app\constant\RedisConstant;
use app\logic\common\ConfigLogic;
use app\logic\game\ModuleLogic;
use app\validate\BaseValidate;
use Redis\Redis;

class FlyBoxValidate extends BaseValidate
{
    protected $rule = [
        'user_id' => 'require|isUnlock',
        'sequence' => 'require|checkReceiveParam',
    ];

    protected $message = [
    ];

    protected $scene = [
        'receiveReward'=>['user_id', 'sequence'],
        'getConfig'=>['user_id']
    ];

    /**
     * 领取飞行宝箱奖励时 检测参数
     *
     * 必须未领取该宝箱
     * 必须是下一个宝箱
     * 隔间时间必须满足
     *
     */
    protected function checkReceiveParam($value, $rule, $data, $field)
    {
        // 获得用户当天领取记录
        $record = Redis::get(RedisConstant::$userReceiveFlyBox.'_'.date('Ymd').'_'.$data['user_id']) ?? [];

        // 获得飞行宝箱配置
        $config =  ConfigLogic::getInstance()->getFlyBoxConfig();

        // 是否已领取当天的飞行宝箱奖励 或 没有该配置
        if (isset($record[$data['sequence']]) || empty($config[$data['sequence']])) {
            return '当天已领取该飞行宝箱，请明天再来~';
        }

        // 获得用户已领取的最后一个奖励的key
        $lastRecordKey = array_key_last($record);

        // key 存在时
        if ($lastRecordKey) {
            // 获得下一个宝箱key
            $keys = array_keys($config);
            $nextKey = $keys[array_search($lastRecordKey, $keys) + 1] ?? null;
            // 下一个宝箱key必须相等
            if ($nextKey !== $data['sequence']) {
                return '未能领取该飞行宝箱，请先领取上一个宝箱';
            }

            // 最后领取奖励的时间 + 下一个宝箱的间隔时间 必须小于当前时间
            if (end($record) + $config[$nextKey]['interval'] >= time()) {
                return '未能领取该飞行宝箱，间隔时长不足';
            }
        }

        // key 不存在时 必须是第一个宝箱 否则错误
        if (empty($lastRecordKey) && reset($config)['sequence'] != $data['sequence']) {
            return '请先领取第一个宝箱';
        }

        return true;
    }

    /**
     * 根据用户id 判断飞行宝箱功能模是否解锁
     */
    protected function isUnlock($value)
    {
        $isUnlock = ModuleLogic::getInstance()->checkFeatureModuleUnlock($value,ModuleConstant::fly_box);

        if (! $isUnlock) {
            return '飞行宝箱未解锁';
        }

        return true;
    }
}