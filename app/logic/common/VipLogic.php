<?php
/*
 * @Author: zane
 * @Date: 2020-06-10 11:01:05
 * @LastEditTime: 2020-11-25 15:24:58
 * @Description: 
 */
namespace app\logic\common;


use app\constant\GameConstant;
use app\constant\ModuleConstant;
use app\constant\RedisConstant;
use app\constant\TaskConstant;
use app\exception\LogicException;
use app\logic\BaseLogic;
use app\logic\game\EconomyLogic;
use app\logic\game\FriendLogic;
use app\logic\game\ModuleLogic;
use app\model\character\UserCharacterDataModel;
use app\model\common\CommonWingModel;
use app\model\task\TaskDailyCompleteRecordModel;
use app\model\vip\VipRewardLogModel;
use app\model\vip\VipRewardModel;
use Redis\Redis;

/**
 * VIP逻辑
 * Class VipLogic
 * @package app\logic\common
 */
class VipLogic extends BaseLogic
{
    /**
     * 处理用户VIP升级
     * @param $userId
     */
    public function handleVipUpgrade($userId)
    {
        $info = UserCharacterDataModel::getInstance()->getInfo(['user_id' => $userId],'user_id,vip');

        $vipInfo   = ConfigLogic::getInstance()->getNextVipConfigById($info->vip);
        if (empty($vipInfo)){
            return true;
        }

        $taskCount   = $vipInfo['task'];
        $friendCount = $vipInfo['friend'];
        $videoCount  = $vipInfo['video'];

        //获取用户任务完成数量
        $finishTaskCount    = TaskDailyCompleteRecordModel::getInstance()->where(['user_id' => $userId])->count();
        //获取用户好友数量
        $finishFriendCount  = (int)UserActionLogic::getInstance()->getUserActionCount($userId, TaskConstant::$task_friend_count);
        //获取用户观看视频广告总数
        $finishVideoCount   = UserActionLogic::getInstance()->getUserActionCount($userId,TaskConstant::$task_ad_count);

        if ($finishTaskCount >= $taskCount && $finishFriendCount >= $friendCount && $finishVideoCount >= $videoCount){
            //该升级了，兄弟
            $vip = Redis::del(RedisConstant::$userVipLevel.$userId);
            Redis::set(RedisConstant::$userVipUpgradeCache.$userId, $vipInfo);
            $info->vip = $vipInfo['vip'];
            $info->save();
        }
        return true;
    }

    /**
     * 获取VIP列表
     * @param $userId
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getVipList($userId)
    {
        $list = ConfigLogic::getInstance()->getVipConfig();

        $propList = ConfigLogic::getInstance()->getCommonPropConfig();
        foreach ($list as &$item){
            $buffArray    = (array)json_decode($item['buff'],true);
            $item['buff'] = [];
            foreach ($buffArray as $buff){
                $item['buff'][] = [
                    'attr_id'  => $buff['buff_id'],
                    'attr_value' => $buff['quantity'],
                ];
            }
            $upgradeRewardList = (array)json_decode($item['upgrade_reward'],true);
            $item['upgrade_reward'] = [];
            foreach ($upgradeRewardList as $upgradeReward){
                $item['upgrade_reward'][] = [
                    "id" => $propList[$upgradeReward['prop_id']]['id'] ?? '',
                    "prop_id" => $upgradeReward['prop_id'],
                    "quantity" => $upgradeReward['quantity'],
                ];
            }
            $dailyRewardList = (array)json_decode($item['daily_reward'],true);
            $item['daily_reward'] = [];
            foreach ($dailyRewardList as $dailyReward){
                $item['daily_reward'][] = [
                    "id" => $propList[$dailyReward['prop_id']]['id'] ?? '',
                    "prop_id" => $dailyReward['prop_id'],
                    "quantity" => $dailyReward['quantity'],
                ];
            }
            $item['extra_reward'] = (array)json_decode($item['extra_reward'],true);

            $item['tqList'] = [
                [
                    'id'   => 'VIPTQ01',
                    'info' => 'VIP'.$item['vip'].'专属BUFF',
                    'value'=> $item['buff'][0] ?? (object)[],
                    'type' => -1,
                ],
                [
                    'id'   => 'VIPTQ02',
                    'info' => '每日加速收益次数',
                    'value'=> $item['quicken_profit_daily_count'],
                    'type' => 5,
                ],
                [
                    'id'   => 'VIPTQ03',
                    'info' => '每次加速收益时长',
                    'value'=> $item['quicken_profit_minute'],
                    'type' => 3,
                ],
                [
                    'id'   => 'VIPTQ04',
                    'info' => '每日免费召唤次数',
                    'value'=> $item['free_luck_number'],
                    'type' => 5,
                ],
                [
                    'id'   => 'VIPTQ05',
                    'info' => '深渊副本免费扫荡次数',
                    'value'=> $item['hard_sweep_max_count'],
                    'type' => 5,
                ],
                [
                    'id'   => 'VIPTQ06',
                    'info' => '竞技场免费挑战次数',
                    'value'=> $item['arena_battle_max_count'],
                    'type' => 5,
                ],
                [
                    'id'   => 'VIPTQ07',
                    'info' => '冒险委托一次广告可获得体力',
                    'value'=> $item['quest_ad_vit'],
                    'type' => 6,
                ],
                [
                    'id'   => 'VIPTQ08',
                    'info' => '解锁专属翅膀',
                    'value'=> CommonWingModel::getInstance()->where(['wing_id'=> $item['wing_id']])->value('name'),
                    'type' => 0,
                ],
                [
                    'id'   => 'VIPTQ09',
                    'info' => '兑换商店折扣',
                    'value'=> $item['shop_discount'],
                    'type' => 1,
                ],
                [
                    'id'   => 'VIPTQ10',
                    'info' => '杀怪金币收益增加',
                    'value'=> $item['pass_money_rate'],
                    'type' => 1,
                ],
                [
                    'id'   => 'VIPTQ11',
                    'info' => '喂食获得经验增加',
                    'value'=> $item['use_food_exp_rate'],
                    'type' => 1,
                ],
            ];
            $item['levelRewardStatus'] = $this->getLevelRewardLog($userId,$item['vip']);
            unset($item['quicken_profit_daily_count'],$item['quicken_profit_minute'],$item['free_luck_number'],$item['hard_sweep_max_count'],
                $item['arena_battle_max_count'],$item['quest_ad_vit'],$item['wing_id'],$item['shop_discount'],
                $item['pass_money_rate'],$item['use_food_exp_rate'],$item['create_time'],$item['update_time'],$item['vip_id'],$item['buff']);
        }
        return $list;
    }

    /**
     * 获取升级奖励记录
     * @param $userId
     * @param $vip
     */
    public function getLevelRewardLog($userId, $vip)
    {
        if (empty($vip)) {
            return false;
        }
        return (bool)VipRewardLogModel::getInstance()->where(['user_id' => $userId, 'vip' => $vip, 'type' => 1])->count();
    }

    /**
     * 获取日常奖励记录
     * @param $userId
     * @param $vip
     */
    public function getDailyRewardLog($userId, $vip)
    {
        if (empty($vip)) {
            return false;
        }
        return (bool)VipRewardLogModel::getInstance()->where(['user_id' => $userId, 'vip' => $vip, 'date' => date('Ymd'), 'type' => 2])->count();
    }

    /**
     * 领取特殊奖励
     */
    public function getSpecialRewardLog($userId, $vip)
    {
        if (empty($vip)) {
            return false;
        }
        return (bool)VipRewardLogModel::getInstance()->where(['user_id' => $userId, 'vip' => $vip, 'type' => 3])->count();
    }

    /**
     * 领取升级奖励
     */
    public function receiveLevelReward($userId,$vip)
    {
        $sysVip = UserCharacterDataModel::getInstance()->where(['user_id' => $userId])->value('vip');
        if ($sysVip < $vip){
            throw new LogicException('VIP等级没达到');
        }
        $isExist = $this->getLevelRewardLog($userId,$vip);
        if ($isExist){
            throw new LogicException('你已经领取过该奖励');
        }
        //获取奖励配置
        $upgradeReward = VipRewardModel::getInstance()->where(['vip_id' => $vip])->value('upgrade_reward');
        $upgradeRewardList = (array)json_decode($upgradeReward,true);
        if (!empty($upgradeRewardList)){
            $list = EconomyLogic::getInstance()->addPropList($userId,$upgradeRewardList,'VIP'.$vip.'升级奖励');
            //记录日志
            VipRewardLogModel::getInstance()->insert([
                'user_id' => $userId,
                'vip'     => $vip,
                'type'    => 1,
                'date'    => date('Ymd'),
                'create_time' => time()
            ]);
            return $list;
        }
        return [];
    }

    /**
     * 领取日常奖励
     */
    public function receiveDailyReward($userId)
    {
        $sysVip = UserCharacterDataModel::getInstance()->where(['user_id' => $userId])->value('vip');
        if (empty($sysVip)){
            throw new LogicException('你不是VIP会员');
        }
        $isExist = $this->getDailyRewardLog($userId,$sysVip);
        if ($isExist){
            throw new LogicException('你已经领取过该奖励');
        }
        //获取奖励配置
        $rewardList = VipRewardModel::getInstance()->where(['vip_id' => $sysVip])->value('daily_reward');
        $rewardList = (array)json_decode($rewardList,true);
        if (!empty($rewardList)){
            $list = EconomyLogic::getInstance()->addPropList($userId,$rewardList,'VIP'.$sysVip.'日常奖励');
            //记录日志
            VipRewardLogModel::getInstance()->insert([
                'user_id' => $userId,
                'vip'     => $sysVip,
                'type'    => 2,
                'date'    => date('Ymd'),
                'create_time' => time()
            ]);
            return $list;
        }
        return [];
    }

    /**
     * 领取特殊奖励
     * @param $userId
     * @param $vip
     * @param $elveId
     * @return array[]
     * @throws LogicException
     */
    public function receiveSpecialReward($userId,$vip,$elveId)
    {
        $sysVip = UserCharacterDataModel::getInstance()->where(['user_id' => $userId])->value('vip');
        if (empty($sysVip)){
            throw new LogicException('你不是VIP会员');
        }
        if ($vip > $sysVip){
            throw new LogicException('VIP级别没达到');
        }
        $isExist = $this->getSpecialRewardLog($userId,$vip);
        if ($isExist){
            throw new LogicException('你已经领取过该奖励');
        }

        //获取奖励配置
        $elveInfo = [];
        $propList = ConfigLogic::getInstance()->getCommonPropConfig();
        foreach ($propList as $prop){
            if ($prop['sub_type'] == GameConstant::ECONOMY_JL && $prop['elve_id'] == $elveId){
                $elveInfo = $prop;
                break;
            }
        }
        $elveInfo['quantity'] = 1;
        $list = EconomyLogic::getInstance()->handleUserProp($userId,$elveInfo['sub_type'],$elveInfo['quantity'],$elveInfo,'VIP'.$vip.'特权奖励');

        //记录日志
        VipRewardLogModel::getInstance()->insert([
            'user_id' => $userId,
            'vip'     => $vip,
            'type'    => 3,
            'date'    => date('Ymd'),
            'create_time' => time()
        ]);
        return [$elveInfo];
    }

    /**
     * 获取用户VIP等级
     */
    public function getUserVipLevel($userId)
    {
        $key = RedisConstant::$userVipLevel.$userId;
        $vip = Redis::get($key);
        if ($vip === null){
            $isUnlock =  ModuleLogic::getInstance()->checkFeatureModuleUnlock($userId,ModuleConstant::vip);
            if ($isUnlock){
                $vip = UserCharacterDataModel::getInstance()->where(['user_id' => $userId])->value('vip');
            }else{
                $vip = 0;
            }
            Redis::setex($key,3600,$vip);
        }
        return (int)$vip;
    }

    /**
     * 获取用户当前特权
     * @param $userId
     */
    public function getUserVipTq($userId)
    {
        $vip = $this->getUserVipLevel($userId);
        if (empty($vip)){
            return [];
        }

        $vipInfo = ConfigLogic::getInstance()->getVipConfigById($vip);

        return $vipInfo;
    }
}




