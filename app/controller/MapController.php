<?php
namespace app\controller;

use app\logic\monster\MonsterLogic;
use app\model\common\MapModel;
use app\model\user\UserGameDataModel;

/**
 * 地图控制器
 * Class MapController
 * @package app\controller
 */
class MapController extends Base
{
    /**
     * 获取当前地图
     */
    public function getMap()
    {
        //地图ID
        $userId = $this->userId;
        //获取用户当前所处位置
        $mapId  = $this->userInfo['map_id'] ?? 75;

        $mapInfo = MapModel::getInstance()->getInfo(['map_id' => $mapId]);
        $mapIds  = [$mapInfo->e,$mapInfo->w,$mapInfo->n,$mapInfo->s];
        $mapList = MapModel::getInstance()->where(['map_id' => $mapIds])->field('map_id,map_name')->select()->toArray();
        $mapList = array_column($mapList,'map_name','map_id');

        //获取怪物
        $monsterList = MonsterLogic::getInstance()->getMapMonster($mapId);
        return $this->success([
            'task' => (object)[
                'task_name' => '打开城堡大门',
            ],
            'map' => [
                'map_name' => $mapInfo->map_name,
                'map_desc' => $mapInfo->map_desc,
                'weather'  => '夏末的上午，太阳越升越高'
            ],
            'monster' => $monsterList,
            'sundries' => [
                ['name' => '小瓶魔力药水','id' => 1],
                ['name' => '三级木材','id' => 2]
            ],
            'move' => [
                'n' => ['name' => $mapList[$mapInfo->n] ?? '','map_id' => $mapInfo->n],
                's' => ['name' => $mapList[$mapInfo->s] ?? '','map_id' => $mapInfo->s],
                'w' => ['name' => $mapList[$mapInfo->w] ?? '','map_id' => $mapInfo->w],
                'e' => ['name' => $mapList[$mapInfo->e] ?? '','map_id' => $mapInfo->e],
            ],
        ]);
    }

    /**
     * 角色移动
     */
    public function move()
    {
        //地图ID
        $mapId = $this->request->param('mapId/d',75);

        $userInfo = UserGameDataModel::getInstance()->getInfo(['user_id' => $this->userId]);
        $userInfo->map_id = $mapId;
        $userInfo->save();
        return $this->success($userInfo);
    }

}
