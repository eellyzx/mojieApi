<?php


namespace app\model\user;

use app\model\BaseModel;

/**
 * Class UserRechargeModel
 * @package app\model\user
 */
class UserRechargeModel extends BaseModel
{
    protected $table = 'user_recharge_log';
    protected $pk    = 'id';

    /**
     * 充值记录日志
     * @param $userId
     * @param $money
     * @return int|string
     */
    public function addLog($userId,$money)
    {
        $addData = [
            'user_id' => $userId,
            'money' => $money,
            'create_month' => date('Ym'),
            'create_day' => date('Ymd'),
            'create_time' => time()
        ];
        return $this->insert($addData);
    }

}