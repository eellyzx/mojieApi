<?php
namespace app\controller;

use app\constant\TaskConstant;
use app\controller\Base;
use app\exception\AuthException;
use app\logic\common\UserLogic;
use app\logic\common\UserCertifyLogic;
use app\service\QueueService;
use think\facade\Log;

class AuthController extends Base
{

    public function initialize(){

        //不需要登录
        $this->isNeedLogin = false;
        parent::initialize();

        //检测维护状态
        UserLogic::getInstance()->checkMaintain($this->request);
    }

    /**
     * 登录
     */
    public function login()
    {
        $res = UserLogic::getInstance()->wxLogin($this->request);
        return $this->success($res);
    }
}
