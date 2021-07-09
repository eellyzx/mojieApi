<?php


namespace app\model\user;

use app\model\BaseModel;

/**
 * Class UserMoneyLogModel
 * @package app\model\user
 */
class UserMoneyLogModel extends BaseModel
{
    protected $table = 'user_money_log';
    protected $pk    = 'log_id';

    /**
     * 记录金钱流水日志
     * @param $userId
     * @param $type
     * @param $value
     * @param string $origin_value
     * @param string $extra_id
     * @return int|string
     */
    public function addLog($userId, $type, $value,$origin_value='',$extra_id='',$desc='')
    {
        $addData = [
            'user_id'       => $userId,
            'type'          => $type,
            'value'         => $value,
            'origin_value'  => $origin_value,
            'extra_id'      => $extra_id,
            'create_time'   => time(),
            'desc'          => $desc
        ];
        return $this->insert($addData);
    }

}