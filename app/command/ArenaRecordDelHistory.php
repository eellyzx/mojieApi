<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use app\model\arena\ArenaBattleRecordModel;
use think\facade\Db;

/**
 * 定期删除竞技场挑战记录(每日0点1分执行)
 * Class ArenaRecordDelHistory
 * @package app\command
 */
class ArenaRecordDelHistory extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('ArenaRecordDelHistory')
            ->setDescription('battle record del history');
    }

    protected function execute(Input $input, Output $output)
    {
        $expireTiming = time()-60*60*24*15;
        //删除历史的挑战记录
        ArenaBattleRecordModel::getInstance()->where('create_time','<',$expireTiming)->delete();
    }
}
