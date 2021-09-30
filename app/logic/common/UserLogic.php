<?php
namespace app\logic\common;
use app\constant\Constant;
use app\exception\LogicException;
use app\logic\BaseLogic;
use app\logic\cache\tokenLogic;
use app\model\user\UserLoginLogModel;
use app\model\user\UserModel;
use jwt\JwtAuth;
use think\Exception;
use think\facade\Db;
use think\Request;
use think\Validate;
use tools\Curl;
use wxapp\aes\WXBizDataCrypt;

class UserLogic extends BaseLogic
{
    /**
     * 微信授权登录
     * @param $request
     */
    public function wxLogin($request)
    {
        $param = $request->param();

        $code          = $param['code'] ?? '';       // login可获取得到的code
        $serverId      = $param['server_id'] ?? 1;// 分服ID
        $ip            = $request->ip();
        $now           = time();
        $app = WechatLogic::getInstance()->getApp();
        $a = $app->auth->session($code);
        var_export($a);
        exit();
        //转换渠道ID
        if(empty($code)){
            throw new LogicException('code不能为空');
        }

        // 获取应用配置信息
        $wxappConfig = config('system')['wxapp'];
        $appId       = $wxappConfig['appid'];
        $secret      = $wxappConfig['secret'];

        // 调用微信接口获取用户标识和会话标识
        $wxappService = new WechatLogic($appId, $secret, $code);
        $wxRs = $wxappService->miniProgram();

        $wxUserData['openId']     = $openId = $wxRs['openid'] ?? '';
        $wxUserData['sessionKey'] = $wxRs['session_key'] ?? '';

        // 创建账号
        $userInfoField = 'user_id,last_login_time';
        $userInfo = UserModel::getInstance()->getInfo(['openid' => $openId,'server_id' => $serverId],$userInfoField)->toArray();
        //已存在用户
        if ($userInfo){
            $userId = $userInfo['user_id'];
        }else{
            //注册新用户
            $userId = $this->register($openId, $serverId);
        }
        //更新记录授权信息
        $updateRs = UserModel::getInstance()->updateInfo(['user_id' => $userId],['more' => json_encode($wxUserData)]);
        if($updateRs === false){
            throw new LogicException('更新登录信息失败');
        }

        //获取用户信息
        $userInfo = UserModel::getInstance()->getInfo(['openid' => $openId]);
        $token = JwtAuth::encode(['user_id' => $userInfo->user_id]);
        $token = tokenLogic::getInstance()->tokenToCache($token);
        // 获取游戏基本信息
        return [
            'userInfo' => $userInfo,
            'token'    => $token,
        ];
    }

    public function testLogin($openId,$serverId = 1)
    {
        if (empty($openId)){
            throw new LogicException('登录失败');
        }
        $userInfoField = 'user_id, status, last_login_time';
        $userInfo = UserModel::getInstance()->getInfo(['openid' => $openId,'server_id' => $serverId],$userInfoField)->toArray();
        if(!empty($userInfo) && Constant::STATUS_CLOSE == $userInfo['status']){
            throw new LogicException('账号已被封禁');
        }
        //已存在用户
        if ($userInfo){
            $userId = $userInfo['user_id'];
        }else{
            //注册新用户
            $userId = $this->register($openId, $serverId);
        }

        //获取用户信息
        $userInfo = UserModel::getInstance()->getInfo(['openid' => $openId]);
        $token = JwtAuth::encode(['user_id' => $userInfo->user_id]);
        $token = tokenLogic::getInstance()->tokenToCache($token);
        // 获取游戏基本信息
        return [
            'userInfo' => $userInfo,
            'token'    => $token,
        ];
    }

    /**
     * 注册账号
     * @param $openId 用户OPENID
     * @param int $serverId
     * @return int|string
     * @throws LogicException
     */
    public function register($openId, $serverId = 1)
    {
        Db::startTrans();
        try {
            $insertUserInfo = [
                'server_id'       => $serverId,
                'openid'		  => $openId,
                'create_time'     => time(),
            ];

            $userId = UserModel::getInstance()->insertGetId($insertUserInfo);
            if (empty($userId)){
                throw new Exception('自动创建用户失败');
            }
            Db::commit();
        }catch (Exception $e){
            Db::rollback();
            throw new LogicException($e->getMessage());
        }
        return $userId;
    }
}