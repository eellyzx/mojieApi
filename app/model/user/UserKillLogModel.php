<?php


namespace app\model\user;

use app\model\BaseModel;

/**
 * 击杀日志
 * Class UserKillLogModel
 * @package app\model\user
 */
class UserKillLogModel extends BaseModel
{
    protected $table = 'user_kill_log';
    protected $pk    = 'log_id';

    /**
     * 获取击杀总量
     * @param $userId
     */
    public function getKillCount($userId)
    {
        return $this->where(['user_id' => $userId])->count();
    }
}