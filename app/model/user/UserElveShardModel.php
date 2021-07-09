<?php


namespace app\model\user;

use app\model\BaseModel;

/**
 * 精灵碎片
 * Class UserElveShardModel
 * @package app\model\user
 */
class UserElveShardModel extends BaseModel
{
    protected $table = 'user_elve_shard';
    protected $pk    = 'shard_id';

    /**
     * 获取用户精灵碎片
     * @param $userId
     */
    public function getUserElveShard($userId)
    {
        return $this->where(['user_id' => $userId])->order('quality ASC')->field('shard_id,elve_id,quantity,prop_id')->select()->toArray();
    }
}