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
use app\model\arena\ArenaUserModel;
use app\model\arena\ArenaRankRewardModel;
use app\logic\arena\ArenaLogic;
use think\facade\Db;
use Redis\Redis;

/**
 * 竞技场每日排名结算(每日23点55分执行)
 * Class ArenaDailySettle
 * @package app\command
 */
class ArenaDailySettle extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('ArenaDailySettle')
            ->setDescription('arena daily ranking settlement');
    }

    protected function execute(Input $input, Output $output)
    {
        //获取当前结算时间
        $settleTime = time();

        //判断是否在可结算时间区间
        /*$settleMinTime = strtotime(date('Y-m-d 23:54:00',$settleTime));
        $settleMaxTime = strtotime(date('Y-m-d 23:59:00',$settleTime));
        if($settleTime<$settleMinTime || $settleTime>$settleMaxTime){
            echo '当前时间不可结算';return;
        }*/

        //判断今日是否已结算过
        /*$todaySettleRowKey = 'ArenaTodaySettleRow_'.date('Ymd',$settleTime);
        $todaySettleRow = Redis::get($todaySettleRowKey)??0;
        if($todaySettleRow){
            echo '今日已结算';return;
        }
        Redis::setex($todaySettleRowKey, 3600, 1);*/

        //获取排名榜数据
        $ranking = ArenaLogic::getInstance()->getRankingData(0,10000000);
        $rankList = $ranking['rankList'];

        //缓存排行榜数据
        $cacheKey = 'ArenaSettleRankingListCache';
        Redis::setex($cacheKey, 3600 * 24, $rankList);

        //赛季日重置用户积分
        $nowDay = date("w",$settleTime);
        if($nowDay=="0"){
            ArenaUserModel::getInstance()->where('id','>',0)->update(['score'=>0,'update_time'=>time()]);
        }

        //获取每日排名结算奖励配置
        $rewardList = ArenaRankRewardModel::getInstance()
            ->field('min_rank,max_rank,reward')
            ->where(['type' => 1])
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
                foreach($rewardList as $r){
                    if($userRank>=$r['min_rank'] && $userRank<=$r['max_rank']){
                        //发送奖励邮件
                        $emailData = [];
                        $emailData['user_id'] = $v['user_id'];
                        $emailData['title'] = '竞技场每日奖励';
                        $emailData['call'] = '亲爱的玩家';
                        $emailData['content'] = '恭喜您在当天竞技场中获得第'.$userRank.'名';
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
