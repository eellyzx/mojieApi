<?php


namespace app\model\monster;


use app\model\BaseModel;

/**
 * 怪物模型
 * Class monster
 * @package app\model\monster
 */
class MapMonsterModel extends BaseModel
{
    protected $table = 'map_monster';
    protected $pk    = 'monster_id';
}