<?php


namespace app\model\common;


use app\model\BaseModel;

/**
 * 关卡配置
 * Class CommonLevelModel
 * @package app\model\common
 */
class CommonLevelModel extends BaseModel
{
    protected $table = 'common_level';
    protected $pk    = 'level_id';

    /**
     * 获取关卡普通信息
     * @param $alias
     */
    public function getLevelInfo($alias)
    {
        return $this->where(['alias' => $alias])->findOrEmpty();
    }
}