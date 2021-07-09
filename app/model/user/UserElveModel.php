<?php


namespace app\model\user;

use app\constant\Constant;
use app\model\BaseModel;

/**
 * Class UserElveModel
 * @package app\model\user
 */
class UserElveModel extends BaseModel
{
    protected $table = 'user_elve';
    protected $pk    = 'user_elve_id';

    /**
     * 添加精灵
     * @param $userId 用户ID
     * @param $elveId 精灵ID
     * @param $level  精灵等级
     * @param $star   精灵星级
     * @param array $initAttr 初始属性
     * @param array $refineAttr 洗练属性
     */
    public function addElve($userId,$elveId,$level,$star,array $initAttr, array $refineAttr)
    {
        return $this->insert([
            'user_id'       => $userId,
            'elve_id'       => $elveId,
            'level'         => $level,
            'star'          => $star,
            'base_attr'     => json_encode($initAttr),
            'refine_attr'   => json_encode($refineAttr),
            'create_time'   => time()
        ]);
    }

    /**
     * 获取用户所有精灵
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAllUserElve($userId)
    {
        $field = 'user_elve_id,elve_id,level,star,is_enable,quality,consume_soul,consume_forg';
        return $this->where(['user_id' => $userId,'is_destroy' => 0])->field($field)->order('quality ASC,elve_id DESC,star DESC,level DESC')->select()->toArray();
    }

    /**
     * 获取用户升级过的，进化过的,没分解的精灵
     * @param array $userElveIds
     */
    public function getUserUpgradedElve($userId,array $userElveIds)
    {
        $where = 'level > 1 OR star > 1';
        return $this->where(['user_elve_id' => $userElveIds,'user_id' => $userId,'is_destroy' => 0,'is_enable' => 0])->whereRaw($where)
            ->field('user_elve_id,elve_id,consume_soul,consume_forg,star')->select();
    }

    /**
     * 根据ID获取用户未出战未分解的精灵
     * @param $userId
     * @param array $userElveIds
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserNotEnableElve($userId,array $userElveIds)
    {
        return $this->where(['user_elve_id' => $userElveIds,'user_id' => $userId,'is_destroy' => 0,'is_enable' => 0])
            ->field('user_elve_id,elve_id,star,consume_soul,consume_forg')->select();
    }

    /**
     * 获取用户某品质的精灵有多少
     * @param $userId
     * @param $quality
     */
    public function getUserElveTypeCount($userId,$quality)
    {
        return $this->where(['user_id' => $userId,'quality' => $quality,'is_destroy' => Constant::STATUS_CLOSE])->count('DISTINCT elve_id');
    }

    /**
     * 获取用户的精灵达到的最大等级
     * @param $userId
     * @return mixed
     */
    public function getElveMaxLevel($userId)
    {
        return $this->where(['user_id' => $userId,'is_destroy' => 0])->order('level DESC')->value('level');
    }

    /**
     * 获取用户的精灵达到的最大星级
     * @param $userId
     * @return mixed
     */
    public function getElveMaxStar($userId)
    {
        return $this->where(['user_id' => $userId])->order('star DESC')->value('star');
    }
}