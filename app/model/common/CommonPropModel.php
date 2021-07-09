<?php


namespace app\model\common;


use app\model\BaseModel;

class CommonPropModel extends BaseModel
{
    protected $table = 'common_prop';
    protected $pk    = 'prop_id';

    /**
     * 获取列表
     * @param $propIds
     */
    public function getPropList($propIds)
    {
        return $this->where(['prop_id' => $propIds])->field('prop_id,alias,sub_type,elve_id,quality')->select()->toArray();
    }
}