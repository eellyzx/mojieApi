<?php
declare (strict_types = 1);

namespace app\command;

use app\logic\combat\CombatLogic;
use app\model\character\UserCharacterDataModel;
use app\model\character\UserCharacterModel;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use app\model\user\UserModel;
use app\model\user\UserEmailModel;
use app\model\arena\ArenaUserModel;
use app\model\arena\ArenaRankRewardModel;
use app\logic\arena\ArenaLogic;
use think\Exception;
use think\facade\Db;
use Redis\Redis;

/**
 * 战力统计
 * Class CombatPowerStatistics
 * @package app\command
 */
class CombatPowerStatistics extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('combatPowerStatistics')
            ->setDescription('战力统计');
    }

    protected function execute(Input $input, Output $output)
    {
        try {
            echo "统计用户战力start".date('Y-m-d H:i:s')."\r\n";
            $page     = 1;
            $pageSize = 1000;
            while (true){
                $offset = ($page - 1) * $pageSize;
                $userList = UserCharacterModel::getInstance()->alias('c')->leftjoin('user_character_data d','c.user_id = d.user_id')
                    ->whereBetween('c.user_id',[$offset,$offset + $pageSize])
                    ->field('c.character_id,c.character_type,c.user_id,d.character_id as curr_character_id')->select()->toArray();
                if (empty($userList)){
                    break;
                }
                $userMap = [];
                foreach ($userList as $item){
                    $userMap[$item['user_id']][] = $item;
                }
                foreach ($userMap as $userId => $userArr){
                    CombatLogic::getInstance()->computeTotalPower($userId,$userArr);
                }
                $page++;
            }
        }catch (Exception $e){
            echo "error----------------------";
            echo $userId."\r\n";
            echo $e->getFile();
            echo $e->getLine();
            echo "----------------------error";
        }
        echo "统计用户战力end".date('Y-m-d H:i:s')."\r\n";
    }
}
