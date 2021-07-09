<?php


namespace app\model\user;

use app\model\BaseModel;

/**
 * Class UserFoodLogModel
 * @package app\model\user
 */
class UserFoodLogModel extends BaseModel
{
    protected $table = 'user_food_log';
    protected $pk    = 'log_id';

    /**
     * 记录用户食物记录
     * @param $userId
     * @param $type 1购买 2合成 3消耗
     * @param $food_id
     * @param string $desc
     * @return int|string
     */
    public function addLog($userId, $type, $food_id, $desc='')
    {
        $addData = [
            'user_id' => $userId,
            'type' => $type,
            'food_id' => $food_id,
            'desc'  => $desc,
            'create_time' => time()
        ];
        return $this->insert($addData);
    }

    /**
     * 获取用户对某一个食物操作次数
     * @param $userId
     * @param $foodId
     * @param int $type
     */
    public function getCountByFoodId($userId,$foodId,$type = 3)
    {
        return $this->where(['user_id' => $userId,'food_id' => $foodId,'type' => $type])->count();
    }

    /**
     * 获取用户对所有食物操作次数
     * @param $userId
     * @param $foodId
     * @param int $type
     */
    public function getFoodCount($userId,$type = 3)
    {
        return $this->where(['user_id' => $userId,'type' => $type])->count();
    }

}