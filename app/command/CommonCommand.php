<?php
declare (strict_types = 1);

namespace app\command;

use app\constant\ConfigConstant;
use app\constant\RedisConstant;
use app\logic\common\ConfigLogic;
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
class CommonCommand extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('commonCommand')
            ->addArgument('method', Argument::OPTIONAL)
            ->setDescription('通用命令');
    }

    /**
     * 执行命令
     * @param Input $input
     * @param Output $output
     * @return int|void|null
     */
    protected function execute(Input $input, Output $output)
    {
        $method = trim((string)$input->getArgument('method'));

        if (empty($method) || !method_exists($this,$method)){
            echo "方法不存在{$method}\r\n";
            return false;
        }
        echo "#########{$method}#########start#########".date('Y-m-d H:i:s')."\r\n";
        $this->$method();
        echo "#########{$method}#########end###########".date('Y-m-d H:i:s')."\r\n";
    }

    /**
     * 重置深渊模式挑战次数
     */
    public function resetHardBattleCount()
    {
        $list = ConfigLogic::getInstance()->getGameConfig();
        $challengeHardCount = $list[ConfigConstant::$hard_battle_max_count]['value'] ?? 0;
        $sweepHardCount     = $list[ConfigConstant::$hard_sweep_max_count]['value'] ?? 0;
        $count = UserCharacterDataModel::getInstance()->where('user_id','>',0)->update(['challenge_hard_count' => $challengeHardCount,'sweep_hard_count' => $sweepHardCount]);
        echo "重置深渊挑战次数共{$count}条\r\n";
    }

    /**
     * 删除几天前的每日任务
     */
    public function delHistoryTask()
    {
        $date = date('Ymd',strtotime('-2 day',time()));
        $count = TaskDailyModel::getInstance()->where('date','<', $date)->delete();
        echo "清除用户每日任务共{$count}条\r\n";
    }

    /**
     * 重置每日任务
     */
    public function resetEverydayTask()
    {
        echo "重置每日任务start\r\n";
        $res = EverydayTaskLogic::getInstance()->resetTask();
        echo $res."\r\n";
        echo "重置每日任务end\r\n";
    }

    /**
     * 定时重启队列
     */
    public function reloadQueue()
    {
        echo "发送重启队列信号start\r\n";
        //重启场景队列
        QueueService::sendQueue('SceneJob',['type' => 'reload',],'sceneQueue');
        //重启成就任务队列
        QueueService::sendQueue('AchievementTaskJob',['type' => 'reload',],'achievementQueue');
        //重启称号任务队列
        QueueService::sendQueue('TitleTaskJob',['type' => 'reload',],'titleQueue');
        echo "发送重启队列信号end\r\n";
    }

    /**
     * 执行用户统计
     */
    public function userStatistics()
    {
        echo "统计用户数start\r\n";
        //开始时间
        $startTime = strtotime(date('Y-m-d',strtotime('-1 day',time())));
        $endTime   = strtotime('+1 day',$startTime);
        //活跃人数
        $where = [
            ['last_login_time','>',$startTime],
            ['last_login_time','<',$endTime],
        ];
        $userActiveCount = UserModel::getInstance()->where($where)->count();

        //新注册人数
        $where = [
            ['create_time','>',$startTime],
            ['create_time','<',$endTime],
        ];
        $userNewCount = UserModel::getInstance()->where($where)->count();

        //总人数
        $userTotalCount = UserModel::getInstance()->count();

        Db::name('user_statistics')->insert([
            'date'              => date('Ymd',$startTime),
            'user_active_count' => $userActiveCount,
            'user_new_count'    => $userNewCount,
            'user_total_count'  => $userTotalCount,
        ]);
        echo "统计用户数end\r\n";
    }

    /**
     * 渠道统计
     */
    public function channelStatistics()
    {
        //统计日期
        $date       = date('Ymd',strtotime('-1 day'));
        // 昨天23点59分时间戳
        $endTime    = strtotime(date('Y-m-d'));
        // 昨天0点时间戳
        $startTime  = strtotime('-1 day',$endTime);
        //初始化今日数据
        $channelIds = ChannelModel::getInstance()->column('channel_id');
        foreach ($channelIds as $channelId){
            $info = ChannelStatisticsModel::getInstance()->getInfo(['channel_id' => $channelId,'date' => $date]);
            if ($info->isEmpty()){
                $info->channel_id = $channelId;
                $info->date = $date;
                $info->save();
            }
        }

        //渠道新用户start
        $channelNewUserList = UserModel::getInstance()->whereBetweenTime('create_time',$startTime,$endTime)->group('channel_id')
            ->field('channel_id,count(user_id) count,sum(online_time) online_time')->select()->toArray();
        foreach ($channelNewUserList as $channelUser){
            //新增-人均在线时长
            ChannelStatisticsModel::getInstance()->updateInfo(['channel_id' => $channelUser['channel_id'],'date' => $date],[
                'new_users' => $channelUser['count'],
                'user_average_online' => $channelUser['online_time'],
            ]);
        }
        //渠道新用户end

        //当日活跃用户start
        $channelActiveList = Db::name('user_login_log')->alias('ll')->leftJoin('user u','ll.user_id = u.user_id')
            ->where(['ll.date' => $date])->group('u.channel_id')->field('u.channel_id,count(DISTINCT(ll.user_id)) count')->select()->toArray();
        foreach ($channelActiveList as $channelActive){
            ChannelStatisticsModel::getInstance()->updateInfo(['channel_id' => $channelUser['channel_id'],'date' => $date],['user_active' => $channelActive['count']]);
        }
        //当日活跃用户end

        //当日广告-活跃用户start
        $adSceneId = [1045,1046,1067,1068,1084,1095];
        $channelActiveList = Db::name('user_login_log')->alias('ll')->leftJoin('user u','ll.user_id = u.user_id')
            ->where(['ll.date' => $date,'u.scene_id' => $adSceneId])->group('u.channel_id')->field('u.channel_id,count(DISTINCT(ll.user_id)) count')->select()->toArray();
        foreach ($channelActiveList as $channelActive){
            ChannelStatisticsModel::getInstance()->updateInfo(['channel_id' => $channelUser['channel_id'],'date' => $date],['ad_active' => $channelActive['count']]);
        }
        //当日广告-活跃用户end

        // 获取新用户看的视频广告总数start
        // 获取视频场景
        $videoScenes = Db::name('ad_config')->where(['type' => [5,6]])->column('scenes');
        $newAdList = Db::name('ad_log')->alias('al')->leftJoin('user u','al.user_id = u.user_id')->where(['al.date' => $date,'al.is_finish' => 1,'scenes' => $videoScenes])
            ->whereBetweenTime('u.create_time',$startTime,$endTime)->group('u.channel_id')->field('u.channel_id,count(al.log_id) count')->select()->toArray();

        foreach ($newAdList as $newAd){
            ChannelStatisticsModel::getInstance()->updateInfo(['channel_id' => $newAd['channel_id'],'date' => $date],['new_user_video' => $newAd['count']]);
        }
        // 获取新用户看的视频广告总数end

        // 渠道活跃用户视频广告总数start
        $activeUserSql = Db::name('user_login_log')->alias('ll')->leftJoin('user u','ll.user_id = u.user_id')
            ->where(['ll.date' => $date])->field('DISTINCT(u.user_id) as user_id')->buildSql();

        $activeUserAdList = Db::name('ad_log')->alias('al')->leftJoin('user u','al.user_id = u.user_id')
            ->leftJoin($activeUserSql.' a','u.user_id = a.user_id')
            ->where(['al.date' => $date,'al.is_finish' => 1,'al.scenes' => $videoScenes])
            ->where('a.user_id', '>', 0)
            ->whereBetweenTime('u.create_time',$startTime,$endTime)->group('u.channel_id')->field('u.channel_id,count(al.log_id) count')->select()->toArray();
        foreach ($activeUserAdList as $activeUserAd){
            ChannelStatisticsModel::getInstance()->updateInfo(['channel_id' => $activeUserAd['channel_id'],'date' => $date],['active_user_video' => $activeUserAd['count']]);
        }
        // 渠道活跃用户视频广告总数end

        //处理人均start
        $channelStatisticslist =  Db::name('channel_statistics')->where(['date' => $date])->select()->toArray();
        foreach ($channelStatisticslist as $channelStatistics){
            //新增-人均视频
            ChannelStatisticsModel::getInstance()->updateInfo(['id' => $channelStatistics['id']],[
                'new_user_average_video'    => Strings::mathDiv($channelStatistics['new_user_video'],$channelStatistics['new_users']),
                'active_user_average_video' => Strings::mathDiv($channelStatistics['active_user_video'],$channelStatistics['user_active']),
            ]);
        }
        //处理人均end
        echo "end\r\n";

        echo "统计AD start\r\n";
        // 获取所有广告场景
        $scenesList = Db::name('ad_config')->order('scenes asc')->column('scenes');
        $adList = AdLogModel::getInstance()->where(['date' => $date])->group('scenes')
            ->field('scenes,COUNT(IF(is_finish = 1,1,NULL)) as finishCount,COUNT(IF(is_finish = 0,1,NULL)) as notFinishCount')->select()->toArray();
        $adList = ArrayHelper::arrayExtractMap($adList,'scenes');
        foreach ($scenesList as $scenes){
            $finishCount = $adList[$scenes]['finishCount'] ?? 0;
            $notFinishCount = $adList[$scenes]['notFinishCount'] ?? 0;
            AdStatisticsModel::getInstance()->insert([
                'date' => $date,
                'scenes' => $scenes,
                'finish_count' => $finishCount,
                'not_finish_count' => $notFinishCount
            ]);
        }
        echo "统计AD end\r\n";
        exit();
    }
}