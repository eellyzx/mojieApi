<?php


namespace app\constant;

/**
 * 配置定义
 * Class ConfigConstant
 * @package app\constant
 */
class ConfigConstant
{
    //每日任务总数
    public static $taskDaily = 'task_daily_count';
    //常驻任务应上架数
    public static $taskDailyResidentCount = 'task_daily_resident_count';
    //轮换任务应上架数
    public static $taskDailyRotateCount = 'task_daily_rotate_count';

    //观看广告获得技能点上限
    public static $watchAdGetSkillPointsMax = 'watch_ad_get_skill_points_max';
    //卡池保底值
    public static $cardPoolMax = 'card_pool_max_count';
    //单次购卡费用
    public static $cardOneCost = 'card_one_cost';
    //十次连抽费用
    public static $cardTenCost = 'card_ten_cost';
    //离线收益最大时长:分
    public static $offlineProfitMaxMinute = 'offline_profit_max_minute';
    //竞技场每日挑战次数
    public static $arenaBattleMaxCount = 'arena_battle_max_count';
    //竞技场积分重置周期
    public static $arenaIntegralResetDay = 'arena_integral_reset_day';
    //冒险委托体力上限
    public static $quest_vit_max = 'quest_vit_max';
    //冒险委托体力回复速度
    public static $quest_vit_recovery_rate = 'quest_vit_recovery_rate';
    //深渊每日扫荡次数
    public static $hard_sweep_max_count = 'hard_sweep_max_count';
    //兑换商店刷新费用
    public static $exchange_stop_reset_cost = 'exchange_stop_reset_cost';
    //转职广告提升等级
    public static $change_character_cut_grade = 'change_character_cut_grade';
    //加速收益时长
    public static $quicken_profit_minute = 'quicken_profit_minute';
    //每日加速收益次数
    public static $daily_quicken_count = 'quicken_profit_daily_count';
    //每日与好友协助击杀宝箱怪次数上限
    public static $friendsBox = 'friends_box';
    //洗炼锁定属性消耗钻石
    public static $refine_unlock_cost = 'refine_lock_cost';
    // 好友助战时长
    public static $friendsHelpTime = 'friends_help_time';
    //深渊模式挑战最大值
    public static $hard_battle_max_count = 'hard_battle_max_count';
    // 好友助战技能冷却时长

    //深渊模式好友协助通关，好友奖励钻石
    public static $hard_battle_help_pass = 'hard_battle_help_pass';
    //深渊模式好友协助没通关，好友奖励钻石
    public static $hard_battle_help_not_pass = 'hard_battle_help_not_pass';

    public static $friendsCoolDown = 'friends_cool_down';
    // 好友宝箱获得钻石奖励的概率
    public static $friendBoxDiamondRate = 'friend_box_diamond_rate';
    // 转职消费钻石
    public static $changeCharacterDiamond = 'change_character_diamond';
    // 裂变获得技能点上限
    public static $fissionSkillLimit = 'fission_skill_limit';
    // 格子宝箱刷新规则
    public static $cellBoxRefreshOnline = 'cell_box_refresh_online';
    // 格子宝箱刷新规则
    public static $cellBoxRefreshPassCount = 'cell_box_refresh_pass_count';
    // 格子宝箱刷新规则
    public static $cellBoxRefreshBuyFood = 'cell_box_refresh_buy_food';
    // 深渊扫荡-广告可添加次数
    public static $hardSweepAdMaxCount = 'hard_sweep_ad_max_count';
    // 深渊挑战-广告可添加次数
    public static $hardBattleAdMaxCount = 'hard_battle_ad_max_count';
    //竞技场每日看广告增加挑战次数
    public static $dailyArenaChallengeNumFromAd = 'daily_arena_challenge_num_from_ad';
    //冒险委托每日广告恢复体力次数
    public static $dailyQuestAdAddEnergyTimes = 'daily_quest_ad_add_energy_times';
    //冒险委托每日广告恢复生命值次数
    public static $dailyQuestAdAddHealthTimes = 'daily_quest_ad_add_health_times';


}