<?php
declare (strict_types = 1);

namespace app\command;

use app\constant\TaskConstant;
use app\service\QueueService;
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
 * 竞技场赛季排名结算(每周日23点56分执行)
 * Class ArenaSeasonSettle
 * @package app\command
 */
class ArenaSeasonSettle extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('ArenaSeasonSettle')
            ->setDescription('arena season ranking settlement');
    }

    protected function execute(Input $input, Output $output)
    {
        //获取当前结算时间
        $settleTime = time();

        //判断是否在可结算时间区间
        /*$nowDay = date("w",$settleTime);
        if($nowDay!="0"){
            echo '非赛季结算日';return;
        }
        $settleMinTime = strtotime(date('Y-m-d 23:55:30',$settleTime));
        $settleMaxTime = strtotime(date('Y-m-d 23:59:00',$settleTime));
        if($settleTime<$settleMinTime || $settleTime>$settleMaxTime){
            echo '当前时间不可结算';return;
        }*/

        //判断本赛季是否已结算过
        /*$seasonSettleRowKey = 'ArenaSeasonSettleRow_'.date('Ymd',$settleTime);
        $seasonSettleRow = Redis::get($seasonSettleRowKey)??0;
        if($seasonSettleRow){
            echo '本赛季已结算';return;
        }
        Redis::setex($seasonSettleRowKey, 3600, 1);*/

        //获取缓存排名榜数据
        $cacheKey = 'ArenaSettleRankingListCache';
        $rankList = Redis::get($cacheKey);

        //获取赛季排名结算奖励配置
        $rewardList = ArenaRankRewardModel::getInstance()
            ->field('min_rank,max_rank,reward')
            ->where(['type' => 2])
            ->select()
            ->toArray();

        //根据排名发送奖励
        if(!empty($rankList)){
            $userEmailModel = UserEmailModel::getInstance();
            foreach($rankList as $k=>$v){
                if($v['is_npc']){ //NPC不发奖励
                    continue;
                }
                $userRank = $k+1; //用户排名

                //排名结算事件
                QueueService::sendQueue('SceneJob', [
                    'userId' => $v['user_id'],
                    'scene'  => TaskConstant::$scene_arena_settlement,
                    'data'   => [
                        'rank' => $userRank
                    ]
                ], 'sceneQueue');

                foreach($rewardList as $r){
                    if($userRank>=$r['min_rank'] && $userRank<=$r['max_rank']){
                        //发送奖励邮件
                        $emailData = [];
                        $emailData['user_id'] = $v['user_id'];
                        $emailData['title'] = '竞技场赛季奖励';
                        $emailData['call'] = '亲爱的玩家';
                        $emailData['content'] = '恭喜您在本赛季竞技场中获得第'.$userRank.'名';
                        $emailData['sign'] = '系统';
                        $emailData['annex'] = json_encode(json_decode($r['reward'],true));
                        $emailData['create_time'] = $settleTime;
                        echo $userEmailModel->sendEmail($emailData)."\n";
                    }
                }

            }
        }


    }
}
