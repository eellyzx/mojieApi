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
                'map_id'   => $mapInfo->map_id,
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
            'battle' => [
                'battle_message' => '对极地战狂造成3392.86点伤害+42群体风暴',
                'kill' => '极地战狂',
                'drop' => [
                    ['name' => '小瓶魔力药水','id' => 1],
                    ['name' => '三级木材','id' => 2]
                ],
                'money' => '65',
                'exp'   => '161'
            ]
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

    /**
     * 获取地图怪物列表
     */
    public function getMapMonsterList()
    {
        $mapId = $this->request->param('mapId');
        $monsterId = $this->request->param('monsterId');

        $list = MonsterLogic::getInstance()->getMapMonsterList($mapId,$monsterId,$this->userId);
        return $this->success($list);
    }

    /**
     * 获取怪物信息
     */
    public function getMonsterInfo()
    {
        $mapMonsterId = $this->request->param('mapMonsterId');
        $mapId = $this->request->param('mapId');

        return $this->success([
            'title'   => '燃烧之雪霜之逆风之闪电之 精英冰猿',
            'grade'   => 190,
            'desc'    => '生活在极冷的地狱雪原，全身雪白，嚎声凄悲。',
            'status'  => '正常，看上去精力充沛。',
            'pendant' => [
                ['id' => 1,'location' => '左手','name' => '神话绮丽投枪+24冰+24电'],
                ['id' => 2,'location' => '右手','name' => '神话绮丽之矛+24火+24风'],
                ['id' => 4,'location' => '头部','name' => '神话绮丽角饰+24火+24风'],
                ['id' => 5,'location' => '颈部','name' => '神话绮丽项链+24火+24风'],
                ['id' => 6,'location' => '身体','name' => '神话绮丽铠甲+24火+24风'],
                ['id' => 7,'location' => '背部','name' => '神话绮丽之翼+24火+24风'],
                ['id' => 8,'location' => '手指','name' => '神话绮丽戒指+24火+24风'],
                ['id' => 9,'location' => '手指','name' => '神话绮丽戒指+24火+24风'],
            ],
        ]);
    }
}
