<?php


namespace app\model\common;


use app\model\BaseModel;

class CommonQuestPlotsModel extends BaseModel
{
    protected $table = 'quest_map_plots';
    protected $pk    = 'id';


    /**
     * 获得剧情列表
     * @param $where
     * @param  string  $field
     *
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCommonQuestPlotList($where, $field = ''){
        $defaultField = '*';
        $field && $defaultField .= ','.$defaultField;
        return $this->where($where)->field($defaultField)->select()->toArray();
    }
}