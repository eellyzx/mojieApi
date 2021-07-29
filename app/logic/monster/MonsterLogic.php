<?php


namespace app\logic\monster;


use app\logic\BaseLogic;
use app\model\monster\MapMonsterConfigModel;
use app\model\monster\MapMonsterModel;

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
        $mapMonsterList = MapMonsterConfigModel::getInstance()->select()->toArray();

        foreach ($mapMonsterList as $mapMonster){
            for ($i = 0;$i < $mapMonster['quantity'];$i++){
                MapMonsterModel::getInstance()->insert([
                    'map_id'        => $mapMonster['map_id'],
                    'config_id'     => $mapMonster['id'],
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
}