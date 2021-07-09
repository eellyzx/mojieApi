<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use app\model\user\UserModel;
use app\model\user\UserEmailModel;
use app\model\user\UserEmailDutyModel;
use think\facade\Db;

/**
 * 监测发送全服邮件(每30s执行一次)
 * Class EmailDutyScan
 * @package app\command
 */
class EmailDutyScan extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('EmailDutyScan')
            ->setDescription('email duty scan');
    }

    protected function execute(Input $input, Output $output)
    {
        //查找任务
        $duty = UserEmailDutyModel::getInstance()
            ->where('type=2 and status=0 and send_time=0')
            ->order('create_time','asc')
            ->find();
        if(empty($duty)){
            echo "暂无任务"."\r\n";die;
        }else{
            $duty = $duty->toArray();
            //执行发送
            UserEmailDutyModel::getInstance()->where(['id'=>$duty['id']])->update(['status'=>1]); //修改状态
            $insData = [];
            $insData['type'] = $duty['type'];
            $insData['title'] = $duty['title'];
            $insData['call'] = $duty['call'];
            $insData['content'] = $duty['content'];
            $insData['sign'] = $duty['sign'];
            $insData['annex'] = $duty['annex'];
            $insData['from'] = $duty['from'];
            $insData['create_time'] = time();
            $insData['update_time'] = time();
            UserEmailModel::getInstance()->insert($insData);
        }

    }
}
