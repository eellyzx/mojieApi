<?php
declare (strict_types = 1);

namespace app\command;

use app\logic\combat\CombatLogic;
use app\logic\task\EverydayTaskLogic;
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
use think\facade\Db;
use Redis\Redis;

/**
 * 每日任务定时生成
 * Class TaskDaily
 * @package app\command
 */
class GenTaskDaily extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('genTaskDaily')
            ->setDescription('每日任务重置');
    }

    /**
     * 定时生成每日任务
     * @param Input $input
     * @param Output $output
     * @return int|void|null
     */
    protected function execute(Input $input, Output $output)
    {
    }
}
