<?php
namespace app\controller;

use app\BaseController;
use app\constant\RedisConstant;
use app\exception\AuthException;
use app\exception\LogicException;
use app\logic\cache\tokenLogic;
use app\model\user\UserModel;
use app\service\SignService;
use jwt\JwtAuth;
use think\exception\HttpResponseException;
use think\facade\Cache;
use think\Response;

class Base extends BaseController
{
    /**
     * 用户ID
     * @var
     */
    protected $userId;

    /**
     * 成功状态码
     * @var int
     */
    protected $successCode = 0;

    /**
     * 失败状态码
     * @var int
     */
    protected $errorCode   = 1;

    /**
     * 是否检查登录
     * @var bool
     */
    protected $isNeedLogin = true;

    /**
     * 用户信息
     * @var array
     */
    protected $userInfo = [];

    /**
     * 不需要登录的方法
     * @var array
     */
    protected $notLoginAction = [];

    // 初始化
    protected function initialize()
    {
        $token = $this->request->header('Authorization');
        $authorizationStr = tokenLogic::getInstance()->tokenToString($token);
        try {
            $authorization = JwtAuth::decode($authorizationStr);
            // dump($authorization);
            // exit();
            if (empty($authorization->user_id)){
                $this->userId = 0;
            }else {
                $this->userId = $authorization->user_id;
            }
        }catch (\Exception $e){
            $this->userId = 0;
        }
        if ($this->isNeedLogin && empty($this->userId) && !in_array($this->request->action(),$this->notLoginAction)){
            throw new AuthException('未登录');
        }
        //获取用户信息
        if (!empty($this->userId)){
            $info = UserModel::getInstance()->where(['u.user_id' => $this->userId])->alias('u')->join('user_game_data d','u.user_id = d.user_id')
                ->field('u.user_id,d.map_id')->findOrEmpty()->toArray();
            $this->userInfo = $info;
        }

        // 签名验证
        SignService::getInstance()->verify($this->request,$token);

        return true;
    }

    public function success($data = [])
    {
        $header = [];
        $header['Access-Control-Allow-Origin']  = '*';
        $header['Access-Control-Allow-Headers'] = 'X-Requested-With,Content-Type,XX-Device-Type,XX-Token,Authorization,apiSign';
        $header['Access-Control-Allow-Methods'] = 'GET,POST,PATCH,PUT,DELETE,OPTIONS';
        $result = [
            'code' => $this->successCode,
            'msg'  => 'success',
            'result' => $data,
        ];
        return json($result,200,$header);
    }

    public function error($msg,$code = '')
    {
        $header = [];
        $header['Access-Control-Allow-Origin']  = '*';
        $header['Access-Control-Allow-Headers'] = 'X-Requested-With,Content-Type,XX-Device-Type,XX-Token,Authorization,apiSign';
        $header['Access-Control-Allow-Methods'] = 'GET,POST,PATCH,PUT,DELETE,OPTIONS';
        $result = [
            'code' => empty($code) ? $this->errorCode : $code,
            'msg'  => $msg,
            'result' => [],
        ];
        return json($result,200,$header);
    }
}