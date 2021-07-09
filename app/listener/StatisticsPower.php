<?php
declare (strict_types = 1);

namespace app\listener;
use app\logic\combat\CombatLogic;
use think\Exception;

/**
 * 自动统计战力
 * Class StatisticsPower
 * @package app\listener
 */
class StatisticsPower
{
    /**
     * 事件监听处理
     * @param $userId
     */
    public function handle($userId)
    {
        try {
            CombatLogic::getInstance()->computeCombatPower($userId);
        }catch (Exception $e){
        }
        return true;
    }
}
