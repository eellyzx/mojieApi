<?php


namespace app\constant;

class RedisConstant
{
    ############################################公共缓存，可清除#################################################
    //角色配置缓存
    public static $commonCharacterLevel = 'common_character_level';
    //普通关卡配置
    public static $commonNormalLevel    = 'common_normal_level';
    //普通关卡奖励配置
    public static $commonNormalLevelReward    = 'common_normal_level_reward';
    //精灵配置
    public static $commonElveConfig     = 'common_elve_config';
    //单个精灵属性
    public static $commonElveInfoConfig = 'common_elve_info_config_';
    //精灵升级配置
    public static $commonElveUpgradeConfig = 'common_elve_upgrade_config';
    //翅膀配置
    public static $commonCbLevel        = 'common_wing_config';
    //获取神器配置
    public static $commonSqConfig = 'common_sq_config';
    //获取神器等级配置
    public static $commonSqLevelConfig = 'common_sq_level_config';
    //获取技能配置
    public static $commonSkillConfig = 'common_skill_config';
    //获取装备配置
    public static $commonZbConfig = 'common_zb_config';
    //获取道具配置
    public static $commonPropConfig = 'common_prop_config';
    //获取食材配置
    public static $commonFoodConfig = 'common_food_config';
    //深渊关卡配置
    public static $commonHardConfig = 'common_hard_config';
    //每日任务列表
    public static $taskDailyListConfig = 'task_daily_list_config';
    //每日任务进度奖励列表
    public static $taskDailyRateRewardConfig = 'task_daily_rate_reward_config';
    //广告配置
    public static $commonAdConfig = 'common_ad_config';
    //视频广告配置
    public static $commonVideoAdConfig = 'common_ad_video_config';
    //活动配置
    public static $commonActivityConfig = 'common_activity_config';
    //buff配置
    public static $commonBuffConfig = 'common_buff_config';
    //title配置
    public static $commonTitleConfig = 'common_title_config';
    //角色等级属性表
    public static $commonCharacterAttr = 'common_character_attr';
    //部分游戏配置
    public static $commonGameConfig    = 'common_game_config';
    //部分游戏模块配置
    public static $commonGameModuleConfig    = 'common_game_module_config';
    //游戏模块开启配置
    public static $commonGameModuleShowConfig = 'common_game_module_show_config';
    // 好友宝箱奖励
    public static $friendBoxReward = 'friend_box_reward';
    // 好友配置key
    public static $friendConfig = 'friend_config';
    // 冒险世界
    public static $questWorldConfig = 'quest_world_config';
    // 冒险剧情
    public static $questPlotList = 'quest_plot_list';
    // 冒险地图列表信息
    public static $questMapList = 'quest_map_list';
    // 冒险地图配置
    public static $questMapConfig = 'quest_map_config';
    // 飞行宝箱配置
    public static $flyBoxConfig = 'fly_box_config';
    // 格子宝箱配置
    public static $cellBoxConfig = 'common_cell_box_config';
    // 邀请新用户奖励
    public static $invitationRewardConfig = 'invitation_reward_config';
    // VIP配置
    public static $common_vip_config = 'common_vip_config';
    ############################################公共缓存，可清除#################################################

    ############################################用户缓存，不可清除###############################################
    //成就任务key
    public static $taskAchievement = 'task_achievement_user_';
    //称号任务key
    public static $taskTitle = 'task_title_user_';
    //用户冒险地图配置
    public static $userQuestMapConfig = 'user_quest_map_config_';
    //用户在精灵兑换商店购买精灵记录
    public static $userBuyElveShopLog = 'user_buy_elve_shop_log_';
    //缓存上一次socket请求时间
    public static $userSocketLastTime = 'user_socket_last_time_';
    //用户抽卡结果缓存
    public static $userDrawCardList = 'user_draw_card_record_';
    //玩家每日最多可挑战次数
    public static $dailyChallengeMaxNum = 5;
    //玩家每日已挑战次数
    public static $dailyChallengeNumKey = 'arena_daily_challenge_num';
    //玩家看广告增加的挑战次数
    public static $dailyChallengeNumFromAd = 'arena_daily_challenge_num_from_ad';
    //限制刷新对手锁
    public static $refreshOpponentsLock = 'arena_refresh_opponents_lock';
    //当日刷新对手次数
    public static $dailyRefreshOpponents = 'arena_daily_refresh_opponents';
    //匹配玩家缓存数组
    public static $matchOpponentsArr = 'arena_match_opponents_list';
    //深渊模式好友助战列表
    public static $hardModelHelpFriend = 'hard_model_help_friend_';
    // 用户每天邀请好友助战的次数
    public static $friendDateHelpLimit = 'friend_date_help_';
    // 好友列表
    public static $friendList = 'friend_list_';
    // 好友当天偷宝箱次数
    public static $friendBoxTime = 'friend_box_time';
    //解锁翅膀看视频次数缓存key
    public static $wingsUnlockAdTimes = 'wings_unlock_ad_times';
    //用户背包数据缓存key
    public static $userPackageData = 'user_package_data_';
    //用户背包锁key
    public static $userPackageDataLock = 'user_package_data_lock_';
    //食物购买操作并发锁
    public static $FoodBuyApiLock = 'food_buy_api_lock_';
    //食物购买获取空格子锁
    public static $FoodBuyEmptyPosLock = 'food_buy_api_lock_';
    //武器每日广告升级次数缓存key
    public static $equipAdUpgradeTimes = 'equip_ad_upgrade_times_';
    //冒险委托每日广告恢复体力次数缓存key
    public static $questAdAddEnergyTimes = 'quest_ad_add_energy_times_';
    //冒险委托每日广告恢复生命值次数缓存key
    public static $questAdAddHealthTimes = 'quest_ad_add_health_times_';
    // 裂变获得技能点上限
    public static $fissionSkillLimit = 'fission_skill_limit_';
    // 用户当天的领取的飞行宝箱
    public static $userReceiveFlyBox = 'user_receive_fly_box';
    // 设置用户当天刷新的宝箱次数
    public static $userCellBoxCount = 'user_cell_box_count';
    // 用户是否有新的宝箱生成
    public static $userCellBoxIsNew = 'user_cell_box_is_new';
    // 用户食物操作统计
    public static $userFoodStatistics = 'user_food_statistics_';
    // 用户解锁新食物奖励领取key
    public static $userUnlockNewFoodReward = 'user_unlock_new_food_reward_';
    // 用户冒险奖励
    public static $questUserReward = 'quest_user_reward_';
    // 用户偷宝箱的奖励
    public static $userFriendBoxReward = 'user_friend_box_reward_';
    // 用户基本信息
    public static $userBaseInfo = 'user_base_info_';
    // 用户成功邀请好友tips
    public static $friendInviteTips = 'friend_invite_tips_';
    // 用户VIP升级缓存
    public static $userVipUpgradeCache = 'user_vip_upgrade_cache_';
    // 用户VIP等级
    public static $userVipLevel = 'user_vip_level_';
    ############################################用户缓存，不可清除###############################################
}