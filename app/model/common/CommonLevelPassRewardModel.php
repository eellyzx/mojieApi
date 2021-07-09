<?php


namespace app\model\common;


use app\model\BaseModel;

/**
 * 通关奖励配置
 * Class CommonLevelPassRewardModel
 * @package app\model\common
 */
class CommonLevelPassRewardModel extends BaseModel
{
    protected $table = 'common_level_pass_reward';
    protected $pk    = 'id';

    /**
     * 获取关卡的奖励配置
     * @param $levelId
     */
    public function getLevelInfo($levelId)
    {
        return $this->where(['level_id' => $levelId])->field('prop_id,quantity')->select()->toArray();
    }
}