<?php
declare (strict_types = 1);

namespace app\command;

use app\constant\ConfigConstant;
use app\constant\RedisConstant;
use app\logic\common\ConfigLogic;
use app\logic\monster\MonsterLogic;
use app\logic\task\EverydayTaskLogic;
use app\model\ad\AdLogModel;
use app\model\ad\AdStatisticsModel;
use app\model\channel\ChannelModel;
use app\model\channel\ChannelStatisticsModel;
use app\model\character\UserCharacterDataModel;
use app\model\task\TaskDailyModel;
use app\model\user\UserModel;
use app\service\QueueService;
use Redis\Redis;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\facade\Config;
use think\facade\Db;
use tools\ArrayHelper;
use tools\Strings;

/**
 * php think commonCommand resetHardBattleCount
 * 通用命令部分-对于简单业务可以放在一起
 * Class CommonCommand
 * @package app\command
 */
class Monster extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('resetMonster')
            ->addArgument('method', Argument::OPTIONAL)
            ->setDescription('重置怪物');
    }

    /**
     * 执行命令
     * @param Input $input
     * @param Output $output
     * @return int|void|null
     */
    protected function execute(Input $input, Output $output)
    {
        MonsterLogic::getInstance()->initMapMonster();
        exit();
    }
}