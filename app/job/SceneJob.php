<?php
namespace app\job;

use app\constant\TaskConstant;
use app\logic\box\CellBoxLogic;
use app\logic\common\UserActionLogic;
use app\logic\hard\HardLogic;
use app\service\QueueService;
use think\facade\Event;
use think\queue\Job;

/**
 * 场景任务分发队列
 * Class TitleTaskJob
 * @package app\job
 */
class SceneJob extends JobBase
{
    /**
     * 执行任务
     * @param Job $job 任务对象
     * @param $data 数据
     */
    public function fire(Job $job,$data)
    {
    	if($job->attempts() > 3){
    	    return $job->delete();
    	}

        //类型
        $type = $data['type'] ?? '';

        //检查重启
        $this->checkRuntime($job, $type);

    	//场景值
        $scene = $data['scene'] ?? 0;
    	//用户ID
    	$userId = $data['userId'] ?? 0;
        if (empty($scene)){
            return $job->delete();
        }

        //分发任务
        $cdnTaskTypes = [];
        switch ($scene){
            case TaskConstant::$scene_ad:
                //看广告场景
                $cdnTaskTypes = [
                    TaskConstant::$task_ad_count,
                ];
                break;
            case TaskConstant::$scene_online:
                //在线时长场景
                $cdnTaskTypes = [
                    TaskConstant::$task_online_count,
                ];
                break;
            case TaskConstant::$scene_login:
                //登录场景
                $cdnTaskTypes = [
                    TaskConstant::$task_login_count,
                ];
                break;
            case TaskConstant::$scene_friend:
                //好友数量变化场景
                $cdnTaskTypes = [
                    TaskConstant::$task_friend_count,
                ];
                break;
            case TaskConstant::$scene_friend_box:
                //挑战宝箱怪场景
                $cdnTaskTypes = [
                    TaskConstant::$task_friend_box_count,
                ];
                break;
            case TaskConstant::$scene_share_game:
                //游戏分享场景
                $cdnTaskTypes = [
                    TaskConstant::$task_share_count,
                ];
                break;
            case TaskConstant::$scene_coin:
                //金币变化场景
                $cdnTaskTypes = [
                    TaskConstant::$task_money_max_count,
                ];
                $money = $data['data']['money'] ?? 0;
                UserActionLogic::getInstance()->setUserMoney($userId,TaskConstant::$task_money_max_count,$money);
                break;
            case TaskConstant::$scene_get_wing:
                //翅膀解锁场景
                $cdnTaskTypes = [
                    TaskConstant::$task_cb_unlock_count,
                ];
                break;
            case TaskConstant::$scene_get_sq:
                //神器解锁场景
                $cdnTaskTypes = [
                    TaskConstant::$task_sq_unlock_count,
                ];
                break;
            case TaskConstant::$scene_sq_upgrade_level:
                //神器升级场景
                $cdnTaskTypes = [
                    TaskConstant::$task_sq_max_grade,
                ];
                break;
            case TaskConstant::$scene_use_food:
                //食用食物场景
                $cdnTaskTypes = [
                    TaskConstant::$task_food_id_use_count,
                    TaskConstant::$task_food_use_count,
                ];
                break;
            case TaskConstant::$scene_merge_food:
                //合成食物场景
                $cdnTaskTypes = [
                    TaskConstant::$task_food_merge_count,
                    TaskConstant::$task_food_unlock_count,
                ];
                break;
            case TaskConstant::$scene_character_upgrade:
                //角色升级场景
                $cdnTaskTypes = [
                    TaskConstant::$task_character_max_grade,
                    TaskConstant::$task_character_all_max_grade,
                    TaskConstant::$task_character_1_max_grade,
                    TaskConstant::$task_character_2_max_grade,
                    TaskConstant::$task_character_3_max_grade,
                ];
                // 触发计算战力
                Event::trigger('statisticsPower',$userId);
                break;
            case TaskConstant::$scene_arms_upgrade:
                //武器升级场景
                $cdnTaskTypes = [
                    TaskConstant::$task_zb_upgrade_count,
                    TaskConstant::$task_zb_all_max_grade,
                    TaskConstant::$task_zb_max_grade,
                    TaskConstant::$task_zb_unlock_count,
                ];
                break;
            case TaskConstant::$scene_skill_upgrade:
                //技能升级场景
                $cdnTaskTypes = [
                    TaskConstant::$task_skill_max_grade,
                ];
                break;
            case TaskConstant::$scene_draw_card:
                //抽卡场景
                $cdnTaskTypes = [
                    TaskConstant::$task_luckdraw_card_count,
                    TaskConstant::$task_luckdraw_card_elve_not_ss,
                    TaskConstant::$task_luckdraw_card_elve_quality,
                    TaskConstant::$task_luckdraw_card_elve_ss,
                ];
                break;
            case TaskConstant::$scene_elve_get:
                //获得精灵场景
                $cdnTaskTypes = [
                    TaskConstant::$task_have_elve_quality_count,
                    TaskConstant::$task_have_elve_type_count,
                ];
                break;
            case TaskConstant::$scene_elve_upgrade_level:
                //精灵升级场景
                $cdnTaskTypes = [
                    TaskConstant::$task_elve_upgrade_count,
                    TaskConstant::$task_elve_max_grade,
                ];
                break;
            case TaskConstant::$scene_elve_upgrade_star:
                //精灵升星场景
                $cdnTaskTypes = [
                    TaskConstant::$task_elve_max_star,
                ];
                break;
            case TaskConstant::$scene_arena_battle:
                //竞技场挑战场景
                $cdnTaskTypes = [
                    TaskConstant::$task_arena_active_win,
                    TaskConstant::$task_arena_active_max_count,
                    TaskConstant::$task_arena_unactive_win,
                ];
                break;
            case TaskConstant::$scene_hard_pass:
                //通关深渊模式场景
                $cdnTaskTypes = [
                    TaskConstant::$task_hard_pass_level,
                ];
                //发送助战奖励
                HardLogic::getInstance()->handleHardHelpReward($data['data']['log_id'] ?? 0);
                break;
            case TaskConstant::$scene_normal_pass:
                //通关普通关卡
                $cdnTaskTypes = [
                    TaskConstant::$task_main_pass_level,
                    TaskConstant::$task_kill_monster_count,
                ];
                break;
            case TaskConstant::$scene_quicken_profit:
                //使用加速收益
                $cdnTaskTypes = [
                    TaskConstant::$task_quicken_count,
                ];
                break;
            case TaskConstant::$scene_hard_sweep:
                //扫荡深渊模式
                $cdnTaskTypes = [
                    TaskConstant::$task_hard_sweep_count,
                ];
                break;
            case TaskConstant::$scene_arena_settlement:
                //竞技场排名计算场景
                $cdnTaskTypes = [
                    TaskConstant::$task_arena_rank_max_grade,
                ];
                break;
            case TaskConstant::$scene_food_buy:
                //购买食物场景
                $cdnTaskTypes = [
                    TaskConstant::$task_food_buy_count,
                ];
                break;
            case TaskConstant::$scene_quest_vit_buy:
                //冒险模式购买体力
                $cdnTaskTypes = [
                    TaskConstant::$task_quest_buy_vit_count,
                ];
                break;
            case TaskConstant::$scene_quest_monster_kill:
                //冒险模式击杀怪物
                $cdnTaskTypes = [
                    TaskConstant::$task_quest_kill_evil_count,
                    TaskConstant::$task_quest_kill_reward_evil_count,
                    TaskConstant::$task_quest_user_grade,
                ];
                break;
            case TaskConstant::$scene_quest_die:
                //死亡次数
                $cdnTaskTypes = [
                    TaskConstant::$task_quest_die_count,
                ];
                break;
            case TaskConstant::$scene_quest_random_event:
                //冒险模式随机事件触发
                $cdnTaskTypes = [
                    TaskConstant::$task_quest_random_event_count,
                ];
                break;
            default:
                break;
        }
        foreach ($cdnTaskTypes as $cdnTaskType){
            $data['type'] = $cdnTaskType;
            $this->sendAchievementQueue($data);
            $this->sendTitleQueue($data);
        }
        $job->delete();
    }

    /**
     * 成就任务队列
     * @param $data 任务数据
     * @return bool
     */
    public function sendAchievementQueue($data)
    {
        return QueueService::sendQueue('AchievementTaskJob',$data,'achievementQueue');
    }

    /**
     * 称号任务队列
     * @param $data 任务数据
     * @return bool
     */
    public function sendTitleQueue($data)
    {
        return QueueService::sendQueue('TitleTaskJob',$data,'titleQueue');
    }

}