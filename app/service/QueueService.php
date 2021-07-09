<?php


namespace app\service;


use think\facade\Queue;

class QueueService
{
    /**
     * 写入队列
     * @param $taskJob 执行任务名称
     * @param $data 数据
     * @param null $queue 队列名
     */
    public static function sendQueue($taskJob,$data,$queue = null)
    {
        try {
            return Queue::push($taskJob, $data, $queue);
        }catch (Exception $e){
        }
        return true;
    }
}