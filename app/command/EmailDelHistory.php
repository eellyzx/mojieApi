<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use app\model\user\UserEmailModel;
use app\model\user\UserEmailFullServiceLogModel;
use think\facade\Db;

/**
 * 定期删除历史邮件(每日0点1分执行)
 * Class EmailDelHistory
 * @package app\command
 */
class EmailDelHistory extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('EmailDelHistory')
            ->setDescription('email del history');
    }

    protected function execute(Input $input, Output $output)
    {
        $expireTiming = time()-60*60*24*15;
        //删除过期的过期邮件
        UserEmailModel::getInstance()->where('create_time','<',$expireTiming)->delete();
        //删除过期全服邮件用户操作记录
        UserEmailFullServiceLogModel::getInstance()->where('email_create_time','<',$expireTiming)->delete();
    }
}
