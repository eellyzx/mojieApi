<?php
namespace app\constant;

/**
 * 冒险模式
 * Class QuestConstant
 * @package app\constant
 */
class QuestConstant
{
    //冒险模式基础属性初始值
    const quest_start_grade  = 1; //世界等级
    //const quest_energy_value = 100; //体力值
    const quest_health_value = 1; //生命值百分比
    const quest_unlock_area = 1; //解锁区域

    //体力值恢复时间(时间戳)
    const quest_energy_renew_time = 60*5;

    //事件类型
    const quest_event_evil = 4; //恶灵
    const quest_event_box = 8; //宝箱
    const quest_event_box_monster = 9; //宝箱怪
    const quest_event_boss = 2; //Boss
    const quest_event_plot = 10; //剧情
    const quest_event_random = 5; //随机
    const quest_event_random_lucky = 51; //随机-幸运
    const quest_event_random_unlucky = 52; //随机-厄运
    const quest_event_random_fight = 53; //随机-战斗
    const quest_event_random_treasure = 54; //随机-宝藏

    //宝箱品质
    const quest_box_quality_primary = 1; //初级
    const quest_box_quality_middle = 2; //中级
    const quest_box_quality_high = 3; //高级

    //用户地图事件Redis相关KEY
    const quest_user_event_cache = 'quest_event_data';
    const quest_user_event_cache_day = 'quest_event_data_date';

    const quest_user_buff_1 = 'SJSJ0001'; // 体力恢复10
    const quest_user_buff_2 = 'SJSJ0002'; // 体力恢复20
    const quest_user_buff_3 = 'SJSJ0003'; // 角色生命恢复50%
    const quest_user_buff_4 = 'SJSJ0004'; // 角色生命回满
    const quest_user_buff_5 = 'SJSJ0005'; // 下次战斗，角色攻击力增加25%。
    const quest_user_buff_6 = 'SJSJ0006'; // 下次战斗，角色防御力增加25%。
    const quest_user_buff_7 = 'SJSJ0007'; // 下次战斗，角色暴击增加25%。
    const quest_user_buff_8 = 'SJSJ0008'; // 下次战斗，角色韧性增加25%。
    const quest_user_buff_9 = 'SJSJ0009'; // 获得50洗练石
    const quest_user_buff_10 = 'SJSJ0010'; // 获得100洗练石
    const quest_user_buff_11 = 'SJSJ0011'; // 获得30钻石
    const quest_user_buff_12 = 'SJSJ0012'; // 下次击杀恶灵奖励增加50%
    const quest_user_buff_13 = 'SJSJ0013'; // 下次击杀恶灵奖励翻倍
    const quest_user_buff_14 = 'SJSJ0014'; // 体力损失5
    const quest_user_buff_15 = 'SJSJ0015'; // 角色生命损失20%（最低到1点，不会死亡）
    const quest_user_buff_16 = 'SJSJ0016'; // 下次战斗，角色攻击力减低15%。
    const quest_user_buff_17 = 'SJSJ0017'; // 下次战斗，角色防御力降低15%。
    const quest_user_buff_18 = 'SJSJ0018'; // 下次战斗，角色暴击降低15%。
    const quest_user_buff_19 = 'SJSJ0019'; // 下次战斗，角色韧性降低15%。
    const quest_user_buff_20 = 'SJSJ0020'; // 随机传送

    const boxQualityRate            = 'box_quality_rate'; // 宝箱品质概率
    const eventRandomRate           = 'event_random_rate'; // 随机事件概率
    const monsterLevelRate          = 'monster_level_rate'; // 恶灵等级区间概率
    const treasureEventLimitTime    = 'treasure_event_limit_time'; // 宝藏事件过期时间
    const areaEventNumRange         = 'area_event_num_range';
    const areaUnlockCondition       = 'area_unlock_condition'; // 区域解锁条件
    const areaRefreshHour           = 'area_refresh_hour'; // 区域刷新时间(每小时)
    const areaOpenLevel             = 'area_open_level'; // 冒险地图开放的最低角色等级
    const fightFailureReduceEnergy  = 'fight_failure_reduce_energy'; // 战斗失败扣除的体力
    const bjdEventReduceEnergy  = 'bjd_event_reduce_energy'; // 触发补给点扣除的体力
    const bossEventReduceEnergy  = 'boss_event_reduce_energy'; // 触发BOSS事件扣除的体力
    const adIncreaseEnergy  = 'ad_increase_energy'; // 看广告恢复体力点数
    const adIncreaseHealth  = 'ad_increase_health'; // 看广告恢复生命值

    /*****************************世界配置--start***********************************/

    //宝箱品质概率
    public static $quest_box_quality_rate = [
        ['id' => self::quest_box_quality_high,'value' => 10], //生成高级宝箱事件概率
        ['id' => self::quest_box_quality_middle,'value' => 35], //生成中级宝箱事件概率
        ['id' => self::quest_box_quality_primary,'value' => 55] //生成低级宝箱事件概率
    ];

    //随机事件概率
    public static $quest_event_random_rate = [
        ['id' => self::quest_event_random_lucky,'value' => 35], //随机事件中幸运事件概率
        ['id' => self::quest_event_random_unlucky,'value' => 15], //随机事件中厄运事件概率
        ['id' => self::quest_event_random_fight,'value' => 25], //随机事件中战斗事件概率
        ['id' => self::quest_event_random_treasure,'value' => 25] //随机事件中宝箱事件概率
    ];

    //区域事件数量区间
    public static $quest_area_event_num_range = [
        1 => [4, 6],
        2 => [2, 4],
        3 => [2, 4],
        4 => [1, 3]
    ];

    // 恶灵等级区间
    public static $quest_monster_level_rate = [
        ['level' => [1,1],'value' => 15],
        ['level' => [-3,0],'value' => 50],
        ['level' => [-10,-4],'value' => 35],
    ];

    // 随机事件-宝藏事件的过期时间
    const quest_box_limit_time = 480; // 分钟;

    // 区域解锁条件
    public static $quest_area_unlock_condition = [
      1 => 1,
      2 => 3,
      3 => 5,
      4 => 7
    ];

    /*****************************世界配置--end***********************************/

}