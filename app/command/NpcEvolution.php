<?php
declare (strict_types = 1);

namespace app\command;

use app\constant\Constant;
use app\constant\GameConstant;
use app\controller\npc\Npc;
use app\logic\combat\CombatLogic;
use app\logic\common\ConfigLogic;
use app\logic\task\EverydayTaskLogic;
use app\model\character\UserCharacterDataModel;
use app\model\character\UserCharacterEquipModel;
use app\model\character\UserCharacterModel;
use app\model\character\UserSqAttrModel;
use app\model\character\UserSqModel;
use app\model\character\UserWingModel;
use app\model\title\TitleUnlockModel;
use app\model\user\UserElveAttrModel;
use app\model\user\UserElveModel;
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
use think\Exception;
use think\facade\Db;
use Redis\Redis;
use tools\ArrayHelper;
use tools\Strings;

/**
 * Npc提升
 * Class NpcEvolution
 * @package app\command
 */
class NpcEvolution extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('npcEvolution')
            ->setDescription('Npc提升');
    }

    /**
     * NPC定时每日提升
     * @param Input $input
     * @param Output $output
     * @return int|void|null
     */
    protected function execute(Input $input, Output $output)
    {
        //获取所有NPC用户
        $userList = UserModel::getInstance()->where(['is_robot' => 1])->where('nickname','<>','除灵师助手')->field('user_id,robot_type,create_time')->select()->toArray();

        $npcConfigList = Db::name('common_npc')->select()->toArray();
        $npcConfigList = ArrayHelper::arrayExtractMap($npcConfigList,'day');

        foreach ($userList as $user){
            $userId     = $user['user_id'];
            $robotType  = $user['robot_type'];
            $createTime = strtotime($user['create_time']);
            //获取NPC是创建了多少天
            $day        = $this->getDay($createTime) + 1;
            $npcConfig  = $npcConfigList[$day] ?? [];
            if (empty($npcConfig)){
                continue;
            }
            switch ($robotType){
                case 1://高成长
                    //角色等级
                    $roleGrade      = (int)$npcConfig['h_role_grade'];
                    //武器等级
                    $zbGrade        = $npcConfig['h_zb_grade'];
                    //高成长精灵品质
                    $elveQualtity   = (int)$npcConfig['h_elve_qualtity'];
                    //高成长精灵等级
                    $elveGrade      = (int)$npcConfig['h_elve_grade'];
                    //高成长精灵星级
                    $elveStar       = (int)$npcConfig['h_elve_star'];
                    //高成长神器等级
                    $sqGrade        = (int)$npcConfig['h_sq_grade'];
                    //高成长翅膀ID
                    $cbId           = (int)$npcConfig['h_cb_id'];
                    //高成长称号ID
                    $titleId        = (int)$npcConfig['h_title_id'];
                    break;
                case 2://中成长
                    //中成长角色等级
                    $roleGrade      = (int)$npcConfig['m_role_grade'];
                    //中成长武器等级
                    $zbGrade        = $npcConfig['m_zb_grade'];
                    //中成长精灵品质
                    $elveQualtity   = (int)$npcConfig['m_elve_qualtity'];
                    //中成长精灵等级
                    $elveGrade      = (int)$npcConfig['m_elve_grade'];
                    //中成长精灵星级
                    $elveStar       = (int)$npcConfig['m_elve_star'];
                    //中成长神器等级
                    $sqGrade        = (int)$npcConfig['m_sq_grade'];
                    //中成长翅膀ID
                    $cbId           = (int)$npcConfig['m_cb_id'];
                    //中成长称号ID
                    $titleId        = (int)$npcConfig['m_title_id'];
                    break;
                case 3://低成长
                    //低成长角色等级
                    $roleGrade      = (int)$npcConfig['l_role_grade'];
                    //低成长武器等级
                    $zbGrade        = $npcConfig['l_zb_grade'];
                    //低成长精灵品质
                    $elveQualtity   = (int)$npcConfig['l_elve_qualtity'];
                    //低成长精灵等级
                    $elveGrade      = (int)$npcConfig['l_elve_grade'];
                    //低成长精灵星级
                    $elveStar       = (int)$npcConfig['l_elve_star'];
                    //低成长神器等级
                    $sqGrade        = (int)$npcConfig['l_sq_grade'];
                    //低成长翅膀ID
                    $cbId           = (int)$npcConfig['l_cb_id'];
                    //低成长称号ID
                    $titleId        = (int)$npcConfig['l_title_id'];
                    break;
                default:
                    $roleGrade = 0;
                    break;
            }
            //角色浮动等级
            $floatRoleGrade = (int)$npcConfig['float_role_grade'];
            //精灵浮动等级
            $floatElveGrade = (int)$npcConfig['float_elve_grade'];
            //神器浮动等级
            $floatSqGrade = (int)$npcConfig['float_sq_grade'];

            if (empty($roleGrade)){
                continue;
            }
            //生成新等级
            $roleGrade = $roleGrade + Strings::randomNumber(-1 * $floatRoleGrade, $floatRoleGrade);
            $roleGrade = $roleGrade > GameConstant::CHARACTER_MAX_GRADE ? GameConstant::CHARACTER_MAX_GRADE : $roleGrade;
            //武器等级，段
            list($zbGrade, $zbStar) = explode(',', $zbGrade);
            //精灵等级
            $elveGrade = $elveGrade + Strings::randomNumber(-1 * $floatElveGrade, $floatElveGrade);
            $elveGrade = $elveGrade > GameConstant::ELVE_MAX_GRADE ? GameConstant::ELVE_MAX_GRADE : $elveGrade;
            //神器等级
            $sqGrade = $sqGrade + Strings::randomNumber(-1 * $floatSqGrade, $floatSqGrade);
            $sqGrade = $sqGrade > GameConstant::SQ_MAX_GRADE ? GameConstant::SQ_MAX_GRADE : $sqGrade;

            //更新角色等级start
            $userCharacterData = UserCharacterDataModel::getInstance()->where(['user_id' => $userId])->field('user_id,character_id,curr_character,max_level')->find();
            if ($userCharacterData->isEmpty()){
                continue;
            }
            $userCharacterData->max_level = $roleGrade > $userCharacterData->max_level ? $roleGrade : $userCharacterData->max_level;
            $userCharacterData->save();
            $userCharacterInfo = UserCharacterModel::getInstance()->getInfo(['character_id' => $userCharacterData->character_id],'character_id,level');
            $userCharacterInfo->level = $roleGrade > $userCharacterInfo->max_level ? $roleGrade : $userCharacterInfo->max_level;
            $userCharacterInfo->save();
            //更新角色等级end

            //更新武器等级start
            //获取装备配置
            $zbList = ConfigLogic::getInstance()->getZbConfig($userCharacterData->curr_character);
            $zbList = array_values($zbList);
            $zbList = ArrayHelper::arrayExtractMap($zbList,'level');

            $userZbInfo = UserCharacterEquipModel::getInstance()->getInfo(['user_id' => $userId]);
            $userZbInfo->level = $zbGrade;
            $userZbInfo->star  = $zbStar;
            $userZbInfo->foreign_key  = $zbList[$zbGrade]['zb_id'] ?? 0;
            $userZbInfo->save();
            //更新武器等级end

            //更新精灵start
            //关闭出战
            UserElveModel::getInstance()->updateInfo(['user_id' => $userId],['is_enable' => Constant::STATUS_CLOSE]);
            //选择出战
            $userElveIds = UserElveModel::getInstance()->where(['user_id' => $userId,'quality' => $elveQualtity,'is_destroy' => Constant::STATUS_CLOSE])->column('user_elve_id');
            if (empty($userElveIds)){
                $userElveId = 0;
            }else{
                $userElveId = $userElveIds[array_rand($userElveIds)] ?? 0;
            }

            if ($userElveId){
                $userElveInfo = UserElveModel::getInstance()->getInfo(['user_elve_id' => $userElveId],'user_elve_id,elve_id,level,star');
                $userElveInfo->level = $elveGrade > $userElveInfo->level ? $elveGrade : $userElveInfo->level;
                $userElveInfo->star = $elveStar;
                $userElveInfo->is_enable = Constant::STATUS_OPEN;
                $userElveInfo->save();
                //重组属性，不处理洗练属性
                $userElveAttrList = UserElveAttrModel::getInstance()->where(['user_elve_id' => $userElveId])->select();
                $elveInfo = ConfigLogic::getInstance()->getElveInfoConfig($userElveInfo->elve_id);
                $attrConfig = $elveInfo['attr'];
                foreach ($userElveAttrList as $userElveAttr){
                    $attrId = $userElveAttr->attr_id;
                    $baseAttrValue = $attrConfig[$attrId]['attr_value'] ?? 0;
                    $growth        = $attrConfig[$attrId]['growth'] ?? 0;
                    $userElveAttr->attr_value = Strings::mathAdd($baseAttrValue,Strings::mathMul($userElveInfo->getData('level')-1,$growth));
                    $userElveAttr->save();
                }
            }
            //更新精灵end

            //更新神器start
            $sqInfo = UserSqModel::getInstance()->getInfo(['user_id' => $userId,'is_enable' => Constant::STATUS_OPEN]);
            if (!$sqInfo->isEmpty()){
                $sqInfo->level = $sqGrade > $sqInfo->level ? $sqGrade : $sqInfo->level;
                $sqInfo->save();
                $sqConfig = ConfigLogic::getInstance()->getSqConfig();
                $configAttr = $sqConfig[$sqInfo->sq_id]['attr'] ?? [];
                $configAttr = ArrayHelper::arrayExtractMap($configAttr,'attr_id');
                //重组属性
                $sqAttrList = UserSqAttrModel::getInstance()->where(['user_id' => $userId,'sq_id' => $sqInfo->sq_id])->select();
                //获取属性
                $attrConfig = ConfigLogic::getInstance()->getCommonBuff();
                foreach ($sqAttrList as $sqAttr){
                    $attrId = $attrConfig[$sqAttr->attr_id]['alias'] ?? '';
                    $baseAttrValue = $configAttr[$attrId]['attr_value'] ?? 0;
                    $growth        = $configAttr[$attrId]['growth'] ?? 0;
                    $sqAttr->attr_value = Strings::mathAdd($baseAttrValue,Strings::mathMul($sqInfo->getData('level')-1,$growth));
                    $sqAttr->save();
                }
            }
            //更新神器end

            //更换翅膀
            UserWingModel::getInstance()->updateInfo(['user_id' => $userId],['is_enable' => Constant::STATUS_CLOSE]);
            UserWingModel::getInstance()->updateInfo(['user_id' => $userId,'foreign_key' => $cbId],['is_enable' => Constant::STATUS_OPEN]);
            //更换称号
            TitleUnlockModel::getInstance()->updateInfo(['user_id' => $userId],['is_use' => Constant::STATUS_CLOSE]);
            TitleUnlockModel::getInstance()->updateInfo(['user_id' => $userId,'title_id' => $titleId],['is_use' => Constant::STATUS_OPEN]);

            echo "角色等级{$roleGrade}--武器等级{$zbGrade}-{$zbStar}--精灵品质{$elveQualtity}--精灵等级{$elveGrade}-{$elveStar}--神器等级{$sqGrade}--翅膀{$cbId}--称号{$titleId}\r\n";
        }
        echo 'end';
        exit();
    }

    /**
     * 根据时间获取时间到今天是第几天
     * @param $time
     */
    private function getDay($time)
    {
        $date1 = date_create(date('Y-m-d',$time));
        $date2 = date_create(date('Y-m-d'));
        $interval = date_diff($date1, $date2);
        return $interval->format('%R%a');
    }

}
