<?php


namespace app\logic\monster;


use app\logic\BaseLogic;
use app\model\monster\MapMonsterConfigModel;
use app\model\monster\MapMonsterModel;
use think\facade\Db;

/**
 * 怪物逻辑
 * Class MonsterLogic
 * @package app\logic\monster
 */
class MonsterLogic extends BaseLogic
{
    /**
     * 初始化怪物
     */
    public function initMapMonster()
    {
        // 读取配置
        $mapMonsterList = MapMonsterConfigModel::getInstance()->alias('c')->leftjoin('monster m','c.monster_id = m.monster_id')
            ->select()->toArray();

        Db::Query('TRUNCATE map_monster;');
        foreach ($mapMonsterList as $mapMonster){
            for ($i = 0;$i < $mapMonster['quantity'];$i++){
                MapMonsterModel::getInstance()->insert([
                    'map_id'        => $mapMonster['map_id'],
                    'monster_id'     => $mapMonster['monster_id'],
                    'monster_name'  => $mapMonster['monster_name'],
                    'grade'         =>  rand($mapMonster['min_grade'],$mapMonster['max_grade']),
                    'status'        => 1,
                    'create_time'   => time(),
                    'update_time'   => time(),
                ]);
            }
        }
        exit();
    }

    /**
     * 获取地图怪物
     * @param $mapId
     */
    public function getMapMonster($mapId)
    {
        $list = MapMonsterModel::getInstance()->where(['map_id' => $mapId,'status' => 1])->group('monster_id')
            ->field('monster_id,monster_name,count(map_monster_id) as monster_num')->select()->toArray();

        return $list;
    }
}