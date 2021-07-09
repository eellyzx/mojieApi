<?php


namespace app\constant;


/**
 * 任务事件定义
 * Class Shenqi
 * @package app\constant
 */
class TaskConstant
{
    ########################场景配置############################
    //观看广告
    public static $scene_ad = 1;
    // 在线报告
    public static $scene_online = 2;
    // 登录
    public static $scene_login = 3;
    // 好友变化
    public static $scene_friend = 4;
    // 好友宝箱怪挑战
    public static $scene_friend_box = 5;
    // 金币变化
    public static $scene_coin = 6;
    // 解锁翅膀
    public static $scene_get_wing = 7;
    // 解锁神器
    public static $scene_get_sq = 8;
    // 神器升级
    public static $scene_sq_upgrade_level = 9;
    // 使用食物
    public static $scene_use_food = 10;
    // 合成食物
    public static $scene_merge_food = 11;
    // 角色升级
    public static $scene_character_upgrade = 12;
    // 武器升级
    public static $scene_arms_upgrade = 13;
    // 技能升级
    public static $scene_skill_upgrade = 14;
    // 抽卡
    public static $scene_draw_card = 15;
    // 获得精灵
    public static $scene_elve_get = 16;
    // 精灵升级
    public static $scene_elve_upgrade_level = 17;
    // 精灵升星
    public static $scene_elve_upgrade_star = 18;
    // 竞技场挑战
    public static $scene_arena_battle = 19;
    // 通关深渊模式
    public static $scene_hard_pass = 20;
    // 通关普通关卡
    public static $scene_normal_pass = 21;
    // 加速收益次数
    public static $scene_quicken_profit = 22;
    // 游戏分享
    public static $scene_share_game = 23;
    // 扫荡深渊模式
    public static $scene_hard_sweep = 24;
    // 竞技场排名结算
    public static $scene_arena_settlement = 25;
    // 购买食物场景
    public static $scene_food_buy = 26;
    // 冒险委托购买体力场景
    public static $scene_quest_vit_buy = 27;
    // 冒险委托击杀恶灵
    public static $scene_quest_monster_kill = 28;
    // 冒险委托死亡
    public static $scene_quest_die = 29;
    // 冒险委托随机事件触发
    public static $scene_quest_random_event = 30;
    ##############################场景配置#################################

    ######################################统一任务类型######################################
    //观看广告次数----getUserActionCount
    public static $task_ad_count = '1';
    //在线总时长（单位：分）----getUserActionCount
    public static $task_online_count = '2';
    //累积登录天数
    public static $task_login_count = '3';
    //每日登录
    public static $task_login_today = '4';
    //好友拥有数-----getUserActionCount
    public static $task_friend_count = '5';
    //分享次数------getUserActionCount
    public static $task_share_count = '6';
    //好友宝箱怪挑战协作数-----getUserActionCount
    public static $task_friend_box_count = '7';
    //加速收益次数-----getUserActionCount
    public static $task_quicken_count = '8';
    //金币持有总量
    public static $task_money_max_count = '9';
    //在线杀怪数量-------getUserActionCount
    public static $task_kill_monster_count = '10';
    //抽卡次数----------getUserActionCount
    public static $task_luckdraw_card_count = '11';
    //单次抽卡中获得的精灵品质
    public static $task_luckdraw_card_elve_quality = '12';
    //一次十连抽中获得SS级精灵（无数值
    public static $task_luckdraw_card_elve_ss = '13';
    //主线关卡章节通过总数
    public static $task_main_pass_level = '14';
    //角色最高级别数
    public static $task_character_max_grade = '15';
    //武器最高级别数
    public static $task_zb_max_grade = '16';
    //技能最高级别数
    public static $task_skill_max_grade = '17';
    //精灵最高级别数
    public static $task_elve_max_grade = '18';
    //精灵最高星级数
    public static $task_elve_max_star = '19';
    //神器最高级别数
    public static $task_sq_max_grade = '20';
    //三种武器都达到的最高级别数
    public static $task_zb_all_max_grade = '21';
    //三个角色都达到的最高级别数
    public static $task_character_all_max_grade = '22';
    //拥有精灵类型总数量
    public static $task_have_elve_type_count = '23';
    //精灵的某类型X的解锁数量（两个数值）
    public static $task_have_elve_quality_count = '24';
    //翅膀解锁数量-----getUserActionCount
    public static $task_cb_unlock_count = '25';
    //神器解锁数量-----getUserActionCount
    public static $task_sq_unlock_count = '26';
    //累积升级武器次数
    public static $task_zb_upgrade_count = '27';
    //累积升级精灵次数-----getUserActionCount
    public static $task_elve_upgrade_count = '28';
    //累计食用食物总次数------getUserActionCount
    public static $task_food_use_count = '29';
    //累计食用食物类型X的次数（两个数值）
    public static $task_food_id_use_count = '30';
    //食物类型解锁数量
    public static $task_food_unlock_count = '31';
    //合成食物次数 ----getUserActionCount
    public static $task_food_merge_count = '32';
    //购买食物次数-----getUserActionCount
    public static $task_food_buy_count = '33';
    //竞技场挑战胜利次数------getUserActionCount
    public static $task_arena_active_win = '34';
    //竞技场防守胜利次数------getUserActionCount
    public static $task_arena_unactive_win = '35';
    //竞技场挑战总次数------getUserActionCount
    public static $task_arena_active_max_count = '36';
    //竞技场排名到达级别
    public static $task_arena_rank_max_grade = '37';
    //深渊副本扫荡次数-----getUserActionCount
    public static $task_hard_sweep_count = '38';
    //深渊副本通过层数
    public static $task_hard_pass_level = '39';
    //冒险委托中购买体力次数----getUserActionCount
    public static $task_quest_buy_vit_count = '40';
    //冒险委托悬赏恶灵击杀数----getUserActionCount
    public static $task_quest_kill_reward_evil_count = '41';
    //冒险委托世界等级数
    public static $task_quest_user_grade = '42';
    //冒险委托中死亡次数-----getUserActionCount
    public static $task_quest_die_count = '43';
    //冒险委托中触发随机事件次数-----getUserActionCount
    public static $task_quest_random_event_count = '44';
    //冒险委托击杀恶灵数------getUserActionCount
    public static $task_quest_kill_evil_count = '45';
    //斩魂角色达到等级
    public static $task_character_1_max_grade = '46';
    //影舞角色达到等级
    public static $task_character_2_max_grade = '47';
    //狩魔角色达到等级
    public static $task_character_3_max_grade = '48';
    //武器解锁数量
    public static $task_zb_unlock_count = '49';
    //连续抽卡没获得SS级精灵次数
    public static $task_luckdraw_card_elve_not_ss = '50';
    ######################################统一任务类型######################################

    //无数值的任务类型
    public static function getNotValueTaskType()
    {
        return [
            self::$task_login_today,
            self::$task_luckdraw_card_elve_quality,
            self::$task_luckdraw_card_elve_ss,
            self::$task_zb_all_max_grade,
            self::$task_character_all_max_grade,
            self::$task_arena_rank_max_grade,
        ];
    }
}