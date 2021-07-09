<?php


namespace app\model\user;

use app\model\BaseModel;

/**
 * Class UserElveModel
 * @package app\model\user
 */
class UserElveAttrModel extends BaseModel
{
    protected $table = 'user_elve_attr';
    protected $pk    = 'id';

    /**
     * 根据列表获取精灵的属性
     * @param $elveIds
     */
    public function getElveAttrByIds($elveIds)
    {
        $data = $this->where(['user_elve_id' => $elveIds])->field('id,user_elve_id,attr_id,attr_value,curr_attr_value,attr_type')->select()->toArray();
        $result = [];
        foreach ($data as $item){
            $result[$item['user_elve_id']][] = $item;
        }
        unset($data);
        return $result;
    }

    /**
     * 根据用户精灵ID获取属性
     * @param $userElveId
     */
    public function getElveAttrById($userElveId)
    {
        $field = 'id,user_elve_id,attr_id,attr_value,curr_attr_value,attr_type';
        return $this->where(['user_elve_id' => $userElveId])->field($field)->select()->toArray();
    }
}