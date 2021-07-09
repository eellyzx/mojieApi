<?php
namespace app\constant;

/**
 * Class GameSet
 * @package app\game
 */
class GameConstant
{
    //角色初始等级
    const INIT_CHARACTER_LEVEL = 1;
    const INIT_CHARACTER_EXP = 0;

    /*****职业定义*****/
    const CHARACTER_TYPE_WARRIOR = 1;//斩魂
    const CHARACTER_TYPE_SLAYER  = 2;//影舞
    const CHARACTER_TYPE_SHOOTER = 3;//猎魔

    /*********等级最大值设定********/
    //角色等级最大值
    const CHARACTER_MAX_GRADE = 500;
    //精灵等级最大值
    const ELVE_MAX_GRADE = 100;
    //神器等级最大值
    const SQ_MAX_GRADE = 50;
    /*********等级最大值设定********/

    /*****装备插槽设定*****/
    const EQUIP_HAND = 1001;//手,武器

    //背包区域最大15格
    const PACKAGE_MAX_LENGTH = 15;

    //经济类型
    const ECONOMY_COIN    = 1;//金钱
    const ECONOMY_SOUL    = 2;//魂石
    const ECONOMY_DIAMOND = 3;//钻石
    const ECONOMY_FORG    = 4;//洗练石
    const ECONOMY_SKILL   = 5;//技能点
    const ECONOMY_CRYSTAL = 6;//精灵结晶
    const ECONOMY_JL_DEBRIS = 7;//精灵碎片
    const ECONOMY_JL      = 8;//精灵成品

    //金币消耗类型
    const MONEY_BUY_FOOD = -1; //金币购买食材
    const MONEY_UPGRADE_EQUIP = -2; //金币强化武器

    //控制可购买食材算法变量
    const CAN_BUY_FOOD_LEVEL_REDUCE = 3;

    //食物合成条件类型
    const FOOD_COMPOSE_CONDITION_LEVEL = 1; //角色等级
    const FOOD_COMPOSE_CONDITION_PASS = 2; //通关关卡

    //抽卡配置
    const CARD_POOL_LUCKDRAW_SINGLE = 1;//单次
    const CARD_POOL_LUCKDRAW_CONTINUE = 10;//多次
    const CARD_POOL_LUCKDRAW_FREE_REFRESH_TIME = '09:00';

    //翅膀解锁条件类型
    const WING_UNLOCK_CONDITION_LEVEL = 1; //角色等级
    const WING_UNLOCK_CONDITION_PASS = 2; //通关关卡
    const WING_UNLOCK_CONDITION_VIP = 3; //vip

    //翅膀解锁费用类型
    const WING_UNLOCK_COST_DIAMOND = 1; //钻石
    const WING_UNLOCK_COST_VIDIO = 2; //观看视频

    //任务系统
    const GAME_MODULE_TASK = 'task';
    //装备系统
    const GAME_MODULE_ZB = 'zb';
    //精灵系统
    const GAME_MODULE_ELVE = 'elve';
    //冒险系统
    const GAME_MODULE_QUEST = 'quest';
    //翅膀系统
    const GAME_MODULE_WING = 'wing';
    //神器系统
    const GAME_MODULE_SQ = 'sq';
    //深渊系统
    const GAME_MODULE_HARD = 'hard';
    //竞技场系统
    const GAME_MODULE_ARENA = 'arena';

    //精灵品质
    const ELVE_QUALITY_SS = 1;
    const ELVE_QUALITY_S = 2;
    const ELVE_QUALITY_A = 3;
    const ELVE_QUALITY_B = 4;
    const ELVE_QUALITY_C = 5;
}