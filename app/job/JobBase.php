<?php
namespace app\job;

use think\queue\Job;

class JobBase
{
    /**
     * 检查运行时间
     * @param Job $job
     * @param $type
     */
    protected function checkRuntime(Job $job, $type)
    {
        if ($type == 'reload'){
            echo "重启队列".date('Y-m-d H:i:s')."\r\n";
            $job->delete();
            exit();
        }
        return true;
    }
}