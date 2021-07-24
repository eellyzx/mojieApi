<?php
namespace app\logic\common;


use app\constant\RedisConstant;
use app\exception\LogicException;
use app\logic\BaseLogic;
use app\model\box\FlyBoxConfigModel;
use app\model\character\UserCharacterDataModel;
use app\model\common\MapModel;
use app\model\common\CommonCharacterAttrModel;
use app\model\common\CommonCharacterLevelModel;
use app\model\common\CommonElveAttrModel;
use app\model\common\CommonElveModel;
use app\model\common\CommonElveUpgradeModel;
use app\model\common\CommonHardModel;
use app\model\common\CommonHardRewardModel;
use app\model\common\CommonLevelModel;
use app\model\common\CommonLevelPassRewardExtraModel;
use app\model\common\CommonLevelPassRewardModel;
use app\model\common\CommonPropModel;
use app\model\common\CommonSkillModel;
use app\model\common\CommonSqLevelModel;
use app\model\common\CommonSqAttrModel;
use app\model\common\CommonSqModel;
use app\model\common\CommonWingAttrModel;
use app\model\common\CommonWingModel;
use app\model\common\CommonZbAttrModel;
use app\model\common\CommonZbModel;
use app\model\common\GameConfigModel;
use app\model\food\FoodModel;
use app\model\friend\FriendConfigModel;
use app\model\quest\QuestMapConfigModel;
use app\model\quest\QuestMapModel;
use app\model\quest\QuestMapPlotsModel;
use app\model\quest\QuestWorldConfigModel;
use app\model\task\TaskDailyCompleteRateModel;
use app\model\task\TaskDailyCompleteRateRewardModel;
use app\model\title\TitleModel;
use Redis\Redis;
use think\facade\Db;
use tools\ArrayHelper;

/**
 * 配置
 * Class ConfigLogic
 * @package app\logic\common
 */
class ConfigLogic extends BaseLogic
{
    /**
     * 获取角色等级配置
     */
    public function getCharacterLevel()
    {
        $levelConfig = Redis::get(RedisConstant::$commonCharacterLevel);
        if (empty($levelConfig)){
            $levelConfig = CommonCharacterLevelModel::getInstance()->order('level ASC')->field('alias,level,exp')->select()->toArray();
            $levelConfig = ArrayHelper::arrayExtractMap($levelConfig,'level');
            Redis::setex(RedisConstant::$commonCharacterLevel, 3600 * 24 * 7, $levelConfig);
        }
        return $levelConfig;
    }

    /**
     * 获取普通关卡配置
     */
    public function getGameNormalLevel()
    {
        $levelConfig = Redis::get(RedisConstant::$commonNormalLevel);
        if (empty($levelConfig)){
            $levelConfig = CommonLevelModel::getInstance()->order('level_id ASC')->field('level_id,alias,offline_reward,normal_kill_reward')->select()->toArray();
            $levelConfig = ArrayHelper::arrayExtractMap($levelConfig,'level_id');
            Redis::setex(RedisConstant::$commonNormalLevel, 3600 * 24 * 7, $levelConfig);
        }
        return $levelConfig;
    }

    /**
     * 获取关卡奖励配置
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGameNormalLevelRewardById($id)
    {
        $key = RedisConstant::$commonNormalLevelReward.$id;
        $levelConfig = Redis::get($key);
        if (empty($levelConfig)){
            $levelConfig = [
                'passReward' => CommonLevelPassRewardModel::getInstance()->where(['level_id' => $id])->field('level_id,prop_id,quantity')->select()->toArray(),
                'passExtraReward' => CommonLevelPassRewardExtraModel::getInstance()->where(['level_id' => $id])->field('level_id,prop_id,quantity')->select()->toArray(),
            ];
            Redis::setex($key, 3600 * 24 * 7, $levelConfig);
        }
        return $levelConfig;
    }

    /**
     * 获取翅膀配置
     */
    public function getCbConfig()
    {
        $config = Redis::get(RedisConstant::$commonCbLevel);
        if (empty($config)){
            $config = CommonWingModel::getInstance()->order('wing_id ASC')->select()->toArray();
            $config = ArrayHelper::arrayExtractMap($config,'wing_id');
            foreach ($config as &$item){
                $item['id'] = $item['alias'];
            }
            //获取翅膀属性
            $attrList = CommonWingAttrModel::getInstance()->field('wing_id,attr_id,attr_value,growth')->select()->toArray();
            foreach ($attrList as $attr){
                $config[$attr['wing_id']]['attr'][] = $attr;
            }
            Redis::setex(RedisConstant::$commonCbLevel, 3600 * 24 * 7, $config);
        }
        return $config;
    }

    /**
     * 获取精灵配置
     */
    public function getElveConfig()
    {
        $config = Redis::get(RedisConstant::$commonElveConfig);
        if (empty($config)){
            $config = CommonElveModel::getInstance()->select()->toArray();

            $config = ArrayHelper::arrayExtractMap($config,'elve_id');
            Redis::setex(RedisConstant::$commonElveConfig, 3600 * 24 * 7, $config);
        }
        return $config;
    }

    /**
     * 获取一个精灵的配置信息
     * @param $elveId
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getElveInfoConfig($elveId)
    {
        $key = RedisConstant::$commonElveInfoConfig.$elveId;
        $config = Redis::get($key);
        if (empty($config)){
            //获取精灵基础信息
            $baseInfo       = CommonElveModel::getInstance()->getInfo(['elve_id' => $elveId])->toArray();
            //获取对应精灵属性信息
            $commonAttrList = CommonElveAttrModel::getInstance()->where(['elve_id' => $elveId])->select()->toArray();
            $commonAttrList = ArrayHelper::arrayExtractMap($commonAttrList,'attr_id');
            $config = [
                'base' => $baseInfo,
                'attr' => $commonAttrList
            ];
            Redis::setex(RedisConstant::$commonElveInfoConfig.$elveId, 3600 * 24 * 7, $config);
        }
        return $config;
    }

    /**
     * 获取精灵升级配置
     */
    public function getElveUpgradeConfig()
    {
        $config = Redis::get(RedisConstant::$commonElveUpgradeConfig);
        if (empty($config)){
            $config = CommonElveUpgradeModel::getInstance()->select()->toArray();
            $config = ArrayHelper::arrayExtractMap($config,'level');
            Redis::setex(RedisConstant::$commonElveUpgradeConfig, 3600 * 24 * 7, $config);
        }
        return $config;
    }

    /**
     * 获取神器配置
     */
    public function getSqConfig()
    {
        $config = Redis::get(RedisConstant::$commonSqConfig);
        if (empty($config)){
            $sqList   = CommonSqModel::getInstance()->select()->toArray();
            $attrList = CommonSqAttrModel::getInstance()->select()->toArray();
            $attrMap = [];
            foreach ($attrList as $item){
                $attrMap[$item['sq_id']][] = $item;
            }
            foreach ($sqList as &$sq){
                $sq['attr'] = $attrMap[$sq['sq_id']] ?? [];
            }
            $sqList = ArrayHelper::arrayExtractMap($sqList,'sq_id');
            Redis::setex(RedisConstant::$commonSqConfig, 3600 * 24 * 7, $sqList);
            return $sqList;
        }
        return $config;
    }

    /**
     * 获取神器等级配置
     */
    public function getSqLevelConfig()
    {
        $config = Redis::get(RedisConstant::$commonSqLevelConfig);
        if (empty($config)){
            $config = CommonSqLevelModel::getInstance()->select()->toArray();
            $config = ArrayHelper::arrayExtractMap($config,'level');
            Redis::setex(RedisConstant::$commonSqLevelConfig, 3600 * 24 * 7, $config);
        }
        return $config;
    }

    /**
     * 获取技能配置
     * @param $character 角色1:斩魂；2影舞；3猎魔
     * @param $skillNum 技能数(技能1，技能2，技能3)
     */
    public function getSkillConfig($character,$skillNum)
    {
        if (empty($character) || empty($skillNum)){
            throw new LogicException('参数错误');
        }
        $key = RedisConstant::$commonSkillConfig.'_'.$character .'_'.$skillNum;
        $config = Redis::get($key);
        if (empty($config)){
            $config = CommonSkillModel::getInstance()->where(['character_type' => $character,'skill_num'=>$skillNum])->order('level ASC')->select()->toArray();
            $config = ArrayHelper::arrayExtractMap($config,'level');
            Redis::setex($key, 3600 * 24 * 7, $config);
        }
        return $config;
    }

    /**
     * 获取装备配置
     * @param $character 角色1:斩魂；2影舞；3猎魔
     */
    public function getZbConfig($character)
    {
        if (empty($character)){
            throw new LogicException('参数错误');
        }
        $key = RedisConstant::$commonZbConfig.'_'.$character;
        $config = Redis::get($key);
        if (empty($config)){
            $field = 'zb_id,character_type,alias,name,level,break,increase,increase_basic,condition';
            $config = CommonZbModel::getInstance()->where(['character_type' => $character])->field($field)->order('level ASC')->select()->toArray();
            $config = ArrayHelper::arrayExtractMap($config,'zb_id');

            $zbIds    = array_column($config,'zb_id');
            $attrList = CommonZbAttrModel::getInstance()->where(['zb_id' => $zbIds])->field('zb_id,attr_id,attr_value,growth')->select()->toArray();
            foreach ($attrList as $attr){
                $config[$attr['zb_id']]['attr'][] = $attr;
            }
            $config = array_values($config);
            $config = ArrayHelper::arrayExtractMap($config,'level');
            //获取属性
            Redis::setex($key, 3600 * 24 * 7, $config);
        }
        return $config;
    }

    /**
     * 获取道具配置
     */
    public function getCommonPropConfig()
    {
        $config = Redis::get(RedisConstant::$commonPropConfig);
        if (empty($config)){
            $field = 'prop_id,alias as id,alias,short_name as name,type,sub_type,quality,elve_id,merge_need_quality';
            $list = CommonPropModel::getInstance()->field($field)->select()->toArray();
            $list = ArrayHelper::arrayExtractMap($list,'prop_id');
            Redis::setex(RedisConstant::$commonPropConfig, 3600 * 24 * 7, $list);
            return $list;
        }
        return $config;
    }

    /**
     * 获取食材配置
     */
    public function getFoodConfig()
    {
        $config = Redis::get(RedisConstant::$commonFoodConfig);
        if (empty($config)){
            $list = FoodModel::getInstance()->select()->toArray();
            $list = ArrayHelper::arrayExtractMap($list,'id');
            Redis::setex(RedisConstant::$commonFoodConfig, 3600 * 24 * 7, $list);
            return $list;
        }
        return $config;
    }

    /**
     * 获取深渊模式配置
     */
    public function getCommonHardConfig()
    {
        $config = Redis::get(RedisConstant::$commonHardConfig);
        if (empty($config)){
            //获取所有关卡
            $list = CommonHardModel::getInstance()->field('hard_id,alias,boss,advice_power,advice_character')->order('hard_id ASC')->select()->toArray();
            $list = ArrayHelper::arrayExtractMap($list,'hard_id');
            //获取所有奖励
            $rewardList = CommonHardRewardModel::getInstance()->field('hard_id,pass_type,prop_id,quantity')->select()->toArray();
            $propList = $this->getCommonPropConfig();
            foreach ($rewardList as $reward){
                if (isset($list[$reward['hard_id']])){
                    $reward['id'] = $propList[$reward['prop_id']]['alias'] ?? '';
                    $list[$reward['hard_id']]['reward'][$reward['pass_type']][] = $reward;
                }
            }
            unset($rewardList);
            Redis::setex(RedisConstant::$commonHardConfig, 3600 * 24 * 7, $list);
            return $list;
        }
        return $config;
    }

    /**
     * 获取角色等级属性表
     * @param $characterType
     */
    public function getCommonCharacterAttr($characterType)
    {
        $key = RedisConstant::$commonCharacterAttr.$characterType;
        $list = Redis::get($key);
        if (empty($list)){
            $list = CommonCharacterAttrModel::getInstance()->where(['character_type' => $characterType])->select()->toArray();
            $list = ArrayHelper::arrayExtractMap($list,'level');
            Redis::setex($key, 3600 * 24 * 7, $list);
        }
        return $list;
    }

    /**
     * 获取游戏一些设定配置，做缓存
     */
    public function getGameConfig()
    {
        $key = RedisConstant::$commonGameConfig;
        $list = Redis::get($key);
        if (empty($list)){
            $list = GameConfigModel::getInstance()->field('config_id,key,value,desc')->select()->toArray();
            $list = ArrayHelper::arrayExtractMap($list,'key');
            Redis::setex($key, 300 , $list);
        }
        return $list;
    }

    /**
     * 根据Key获取配置
     * @param $key
     * @return mixed
     * @throws LogicException
     */
    public function getGameConfigByKey($key)
    {
        $config = $this->getGameConfig();
        if (!isset($config[$key])){
            throw new LogicException('配置不存在');
        }
        return $config[$key]['value'];
    }

    /**
     * 获取游戏系统解锁条件配置,弃用
     */
    public function getGameModuleConfig()
    {
        $key = RedisConstant::$commonGameModuleConfig;
        $list = Redis::get($key);
        if (empty($list)){
            $list = Db::name('game_module')->select();
            $list = ArrayHelper::arrayExtractMap($list,'key');
            Redis::setex($key, 3600 , $list);
        }
        return $list;
    }

    /**
     * 获取任务进度奖励
     */
    public function getTaskDailyCompleteRate()
    {
        $key = RedisConstant::$taskDailyRateRewardConfig;

        $rateList = Redis::get($key);
        if (empty($rateList)){
            $rateList = TaskDailyCompleteRateModel::getInstance()->field('rate_id,complete_count')->order('complete_count asc')->select()->toArray();
            $rateList = ArrayHelper::arrayExtractMap($rateList,'rate_id');
            $rewardList = TaskDailyCompleteRateRewardModel::getInstance()->field('rate_id,prop_id,quantity')->select()->toArray();
            $propList = $this->getCommonPropConfig();
            foreach ($rewardList as $item){
                //转换ID
                $item['id'] = $propList[$item['prop_id']]['alias'] ?? '';
                if (isset($rateList[$item['rate_id']])){
                    $rateList[$item['rate_id']]['reward'][] = $item;
                }
            }
            Redis::setex($key, 300 , $rateList);
        }
        return array_values($rateList);
    }

    /**
     * 获取游戏功能开启状态
     */
    public function getGameFeatureModule()
    {
        $key = RedisConstant::$commonGameModuleShowConfig;
        $list = Redis::get($key);
        if (empty($list)){
            $list = Db::name('game_module_feature')->field('id,key,name,is_show,condition_type,condition_value,step')->select()->toArray();
            Redis::setex($key, 300 , $list);
        }
        return $list;
    }

        /**
     * 获取buff配置
     */
    public function getCommonBuff($index = 'id')
    {
        $key = RedisConstant::$commonBuffConfig;
        $list = Redis::get($key);
        if (empty($list)){
            $list = MapModel::getInstance()->field('id,type,alias,desc,decimal_place')->select()->toArray();
            Redis::setex($key, 30 * 24 * 3600 , $list);
        }
        $list = ArrayHelper::arrayExtractMap($list,$index);
        return $list;
    }

    /**
     * 获得好友相关配置
     */
    public function getFriendConfig()
    {
        $key = RedisConstant::$friendConfig;
        $list = [];
        if (empty($list)){
            $list = FriendConfigModel::getInstance()->getColumnByField([],'value','key');
            foreach ($list as &$value){
                $value = json_decode($value,true);
            }
            Redis::setex($key, 300 , $list);
        }
        return $list;
    }

    /**
     * 通过key获得好友相关配置
     */
    public function getFriendConfigByKey($key)
    {
        $config = $this->getFriendConfig();
        if (!isset($config[$key])){
            throw new LogicException('配置不存在');
        }
        return $config[$key];
    }

    /**
     * 获取称号配置
     */
    public function getTitleConfig()
    {
        $key = RedisConstant::$commonTitleConfig;
        $list = Redis::get($key);
        if (empty($list)){
            $list = TitleModel::getInstance()->select()->toArray();
            $list = ArrayHelper::arrayExtractMap($list,'id');
            Redis::setex($key, 30 * 24 * 3600 , $list);
        }
        return $list;
    }

    /**
     * 获得冒险世界配置
     */
    public function getQuestWorldConfig()
    {
        $redisKey = RedisConstant::$questWorldConfig;
        $list = Redis::get($redisKey);

        if (empty($list)){
            $list = QuestWorldConfigModel::getInstance()->getColumnByField([],'value','key');
            Redis::setex($redisKey, 30 * 24 * 3600 , $list);
        }

        return $list;
    }

    /**
     * 通过key获得冒险世界配置
     */
    public function getQuestWorldConfigByKey($key)
    {
        $config = $this->getQuestWorldConfig();
        if (!isset($config[$key])){
            throw new LogicException('配置不存在');
        }
        return $config[$key];
    }

    /**
     * 获得冒险的剧情
     */
    public function getQuestPlotList()
    {
        $redisKey = RedisConstant::$questPlotList;
        $list = Redis::get($redisKey);

        if (empty($list)){
            $list = QuestMapPlotsModel::getInstance()->getCommonQuestPlotList([]);
            $list = ArrayHelper::arrayExtractMap($list,'alias');
            Redis::setex($redisKey, 30 * 24 * 3600 , $list);
        }

        return $list;
    }

    /**
     * 通过剧情别名获得剧情的信息
     *
     * @param $alias
     */
    public function getQuestPlotListByAlias($alias)
    {
        $list = $this->getQuestPlotList();
        if (!isset($list[$alias])){
            throw new LogicException('配置不存在');
        }
        return $list[$alias];
    }

    /**
     * 获取冒险地图列表
     */
    public function getQuestMapList()
    {
        $redisKey = RedisConstant::$questMapList;
        $list = Redis::get($redisKey);

        if (empty($list)){
            $list = QuestMapModel::getInstance()->select()->toArray();
            $list = ArrayHelper::arrayExtractMap($list,'point_id');
            Redis::setex($redisKey, 7 * 24 * 3600 , $list);
        }

        return $list;
    }

    /**
     * 获得冒险地图全部配置
     */
    public function getQuestMapConfig()
    {
        $redisKey = RedisConstant::$questMapConfig;
        $list = Redis::get($redisKey);
        if (empty($list)){
            $list = QuestMapConfigModel::getInstance()->select()->toArray();
            $list = ArrayHelper::arrayExtractMap($list,'quest_grade');
            Redis::setex($redisKey, 7 * 24 * 3600 , $list);
        }
        return $list;
    }

    /**
     * 通过世界等级获得冒险地图的配置
     *
     * @param $grade // 世界等级
     */
    public function getQuestMapConfigByGrade($grade)
    {
        $list = $this->getQuestMapConfig();

        if (!isset($list[$grade])){
            throw new LogicException('冒险配置不存在');
        }
        return $list[$grade];
    }

    /**
     *  获取广告开关配置
     */
    public function getAdConfig()
    {
        $key = RedisConstant::$commonAdConfig;

        $list = Redis::get($key);
        if (empty($list)){
            $list = Db::name('ad_config')->field('type,sub_type,scenes,scenes_name,is_show')->select()->toArray();
            Redis::setex($key, 3600 , $list);
        }
        return $list;
    }

    /**
     * 获取视频广告场景Id
     */
    public function getVideoAdScenesId()
    {
        $key = RedisConstant::$commonVideoAdConfig;

        $list = Redis::get($key);
        if (empty($list)){
            $list = Db::name('ad_config')->where(['type' => [5,6]])->column('id');
            Redis::setex($key, 3600 , $list);
        }
        return $list;
    }

    /**
     * 获得飞行宝箱配置
     */
    public function getFlyBoxConfig()
    {
        $key = RedisConstant::$flyBoxConfig;
        $list = Redis::get($key);

        if (empty($list)) {
            $list = FlyBoxConfigModel::getInstance()->getConfigList([],'*','sequence');
            foreach ($list as &$value){
                $value['reward'] = json_decode($value['reward'],true);
            }

            Redis::setex($key, 7 * 24 * 3600, $list);
        }

        return $list;
    }

    /**
     * 获取格子宝箱配置
     */
    public function getCellBoxConfig()
    {
        $key = RedisConstant::$cellBoxConfig;
        $list = Redis::get($key);
        if (empty($list)) {
            $list = Db::name('cell_box')->field('box_id,box_type,quantity,count')->select()->toArray();
            $list = ArrayHelper::arrayExtractMap($list,'box_id');
            Redis::setex($key, 7 * 24 * 3600, $list);
        }
        return $list;
    }

    /**
     * 获得邀请好友奖励
     */
    public function getInvitationRewardConfig()
    {
        $key = RedisConstant::$invitationRewardConfig;
        $list = Redis::get($key);
        if (empty($list)) {
            $list = Db::name('friend_invite_reward')->field('invite_min,invite_max,reward')->select()->toArray();
            Redis::setex($key, 7 * 24 * 3600, $list);
        }
        return $list;
    }

    /**
     * 检查用户功能模块开启状态
     * @param $userId
     */
    public function checkGameFeatureModule($userId)
    {
        $list = ConfigLogic::getInstance()->getGameFeatureModule();

        // $gameModule = ConfigLogic::getInstance()->getGameModuleConfig();

        $userDataInfo =  UserCharacterDataModel::getInstance()->getInfo(['user_id' => $userId],'user_id,max_level,max_pass_normal,un_module');
        //解锁模块
        $unModuleMap = explode(',', $userDataInfo->un_module);
        //对用户过滤属性
        foreach ($list as &$item){
            $item['condition'] = [
                'type' => $item['condition_type'],
                'value'=> $item['condition_value'],
                'step' => $item['step'],
            ];
            $item['un_module'] = in_array($item['id'],$unModuleMap) ? 1 : 0;
            if ($item['is_show'] == 1){
                if (($item['condition_type'] == 1 && $userDataInfo->max_level >= $item['condition_value']) ||
                    ($item['condition_type'] == 2 && $userDataInfo->max_pass_normal >= $item['condition_value'])){
                    $item['logic_show'] = 1;
                }else{
                    $item['logic_show'] = 0;
                }
            }else{
                $item['logic_show'] = 0;
            }
            $item['tips_text'] = '';
            if($item['condition_type']==1){
                $item['tips_text'] = '角色'.$item['condition_value'].'级开启';
            }else if($item['condition_type']==2){
                $item['tips_text'] = '通关'.$item['condition_value'].'关开启';
            }
            unset($item['condition_type']);
            unset($item['condition_value']);
        }
        return $list;
    }

    /**
     * 获取VIP配置
     * @param $vipId
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getVipConfig()
    {
        $key = RedisConstant::$common_vip_config;
        $list = Redis::get($key);
        if (empty($list)) {
            $list = Db::name('vip')->alias('v')->leftJoin('vip_patent vp','v.id = vp.vip_id')->leftJoin('vip_reward vr','v.id = vr.vip_id')
                ->order('v.id ASC')->select()->toArray();
            $list = ArrayHelper::arrayExtractMap($list,'vip_id');
            Redis::setex($key, 7 * 24 * 3600, $list);
        }
        return $list;
    }

    /**
     * 获取VIP配置
     * @param $vipId
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getVipConfigById($vipId)
    {
        $list = $this->getVipConfig();
        $info = $list[$vipId] ?? [];
        return $info;
    }


    /**
     * 获取VIP下一级
     * @param $vipId
     */
    public function getNextVipConfigById($vipId)
    {
        $list = $this->getVipConfig();
        $vipId++;
        $info = $list[$vipId] ?? [];
        return $info;
    }

}