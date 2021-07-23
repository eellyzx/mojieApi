<?php
namespace app\controller;

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

        return $this->success([
            'task' => (object)[
                'task_name' => '打开城堡大门',
            ],
            'map' => [
                'map_name' => '幽暗墓场',
                'map_desc' => '这里是一片昏暗的墓场，地面上到处是半掩的墓穴，空气里充满了死亡的气味。',
                'weather'  => '夏末的上午，太阳越升越高'
            ],
            'monster' => [
                ['monster_name' => '木乃伊','monster_num' => 10,],
                ['monster_name' => '骷髅','monster_num' => 10,],
                ['monster_name' => '骷髅射手','monster_num' => 10,],
            ],
            'sundries' => [
                ['name' => '小瓶魔力药水'],
                ['name' => '三级木材']
            ],
            'move' => [
                'n' => ['name' => '暮色河岸一','map_id' => 1],
                's' => ['name' => '暮色河岸二','map_id' => 1],
                'w' => ['name' => '暮色河岸三','map_id' => 1],
                'e' => ['name' => '暮色河岸四','map_id' => 1],
            ],
        ]);
    }

}
