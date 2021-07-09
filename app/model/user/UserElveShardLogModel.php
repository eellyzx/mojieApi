<?php


namespace app\model\user;

use app\model\BaseModel;

/**
 * 精灵碎片
 * Class UserElveShardModel
 * @package app\model\user
 */
class UserElveShardLogModel extends BaseModel
{
    protected $table = 'user_elve_shard_log';
    protected $pk    = 'log_id';

    /**
     * 添加日志
     * @param $userId
     * @param $type
     * @param $value
     * @param $originValue
     * @param $afterValue
     * @param $desc
     * @param $extraId
     * @return int|string
     */
    public function addLog($userId,$type,$value,$originValue,$afterValue,$desc = '',$extraId = 0)
    {
        return $this->insert([
            'user_id'       => $userId,
            'type'          => $type,
            'value'         => $value,
            'origin_value'  => $originValue,
            'after_value'   => $afterValue,
            'extra_id'      => $extraId,
            'desc'          => $desc,
            'create_time'   => time(),
            'update_time'   => time(),
        ]);
    }
}