<?php
namespace app\logic\common;

use app\constant\GameConstant;
use app\logic\BaseLogic;
use app\exception\LogicException;
use app\model\character\UserCharacterDataModel;
use app\model\user\UserRechargeModel;
use app\model\user\UserCertifyModel;
use think\facade\Db;
use app\Request;

/**
 * 充值逻辑
 * Class EquipLogic
 * @package app\logic\game
 */
class RechargeLogic extends BaseLogic
{
    /**
     * 增加金币
     * @param Request $request
     * @param $userId
     */
    public function addMoney(Request $request, $userId)
    {
        $money = $request->param('money');

        if(empty($money)){
            throw new LogicException('金额不能为空');
        }

        //判断身份证信息
        $userCertifyRow = UserCertifyModel::getInstance()->getInfo(['user_id'=>$userId],'idcard_num')->toArray();
        if(!empty($userCertifyRow))
        {
            $currMonth = date('Ym');
            $monthRecharge = UserRechargeModel::getInstance()->field('SUM(money) AS total')->where(['user_id'=>$userId,'create_month'=>$currMonth])->select()->toArray();
            $monthRecharge = $monthRecharge[0]['total']?$monthRecharge[0]['total']:0;
            $age = getAge($userCertifyRow['idcard_num']);
            if($age<8){
                throw new LogicException('未满8周岁的用户，无法充值');
            }else if($age>=8 && $age<16){
                if(intval($money)>50){
                    throw new LogicException('单次充值金额不能超过50元');
                }
                if($monthRecharge>200){
                    throw new LogicException('每月充值金额累计不超过200元');
                }
            }else if($age>=16 && $age<18){
                if(intval($money)>100){
                    throw new LogicException('单次充值金额不能超过100元');
                }
                if($monthRecharge>400){
                    throw new LogicException('每月充值金额累计不超过400元');
                }
            }
        }

        Db::startTrans();
        try {

            //加金币
            $characterData = UserCharacterDataModel::getInstance()->getInfo(['user_id' => $userId],'user_id,money');
            $characterData->money = bcadd($characterData->money,intval($money));
            $characterData->save();

            //记录log
            UserRechargeModel::getInstance()->addLog($userId,intval($money));

            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            throw new LogicException('增加金币失败-' . $e->getMessage());
        }

        $characterData = UserCharacterDataModel::getInstance()->getInfo(['user_id' => $userId],'user_id,money')->toArray();
        return ['characterData'=>$characterData];
    }
}