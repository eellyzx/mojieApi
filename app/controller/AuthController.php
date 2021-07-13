<?php
namespace app\controller;

use app\logic\common\UserLogic;

class AuthController extends Base
{
    public $isNeedLogin = false;

    /**
     * 登录
     */
    public function vxLogin()
    {
        $res = UserLogic::getInstance()->wxLogin($this->request);
        return $this->success($res);
    }

    public function testLogin()
    {
        $openId = $this->request->param('openId');
        $res = UserLogic::getInstance()->testLogin($openId);
        return $this->success($res);
    }
}
