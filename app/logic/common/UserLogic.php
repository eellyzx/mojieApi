<?php
namespace app\logic\common;
use app\constant\Constant;
use app\exception\LogicException;
use app\logic\BaseLogic;
use app\logic\channel\ChannelLogic;
use app\logic\combat\CombatLogic;
use app\logic\game\CharacterLogic;
use app\logic\game\GameLogic;
use app\logic\game\InitLogic;
use app\logic\share\ShareLogic;
use app\model\channel\ChannelModel;
use app\model\user\UserLoginLogModel;
use app\model\user\UserModel;
use app\model\maintain\MaintainModel;
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
     * 检测维护状态
     */
    public function checkMaintain($request)
    {
        $configData = MaintainModel::getInstance()->getInfo(['online_status'=>1])->toArray();
        if(!empty($configData)){
            if ($configData['start_time'] <= $_SERVER['REQUEST_TIME'] && $configData['end_time'] >= $_SERVER['REQUEST_TIME']) {
                $client_ip = $request->ip();
                $ip_white_list = explode(',',$configData['ip_white_list']);
                if(!in_array($client_ip,$ip_white_list)){
                    throw new LogicException('服务器维护中！',10410);
                }
            }
        }
    }

    /**
     * QQ授权登录
     * @param Request $request
     */
    public function qqLogin(Request $request)
    {
        $param = $request->param();
        $code          = $param['code'] ?? '';       // login可获取得到的code
        $shareUserId   = $param['share_user_id'] ?? 0;// 邀请用户
        $shareId       = $param['share_id'] ?? 0; // 分享内容id
        $fromApp       = $param['appid'] ?? '';	     // 来源appid
        $channelId     = $param['channel'] ?? 0; // 来源渠道号
        $serverId      = $param['server_id'] ?? 1;// 分服ID
        $sceneId       = $param['scene_id'] ?? 0;// 场景值
        $ip            = $request->ip();
        $now           = time();

        if(empty($code)){
            throw new LogicException('code不能为空');
        }
        //转换渠道ID
        $channelId = (int) ChannelModel::getInstance()->where(['channel_sn' => $channelId])->value('channel_id');

        // 获取应用配置信息
        $wxappConfig = config('system')['qqapp'];
        $appId       = $wxappConfig['appid'];
        $secret      = $wxappConfig['secret'];

        $url        = "https://api.q.qq.com/sns/jscode2session?appid={$appId}&secret={$secret}&js_code={$code}&grant_type=authorization_code";
        $responses  = Curl::get($url);
        $response   = json_decode($responses, true);
        if($response===null || $response===false){
            throw new LogicException('解密数据错误:'.$url.',reposnse:'.serialize($responses));
        }elseif ($response['errcode'] != 0){
            throw new LogicException($response['errcode'].':'.$response['errmsg']);
        }elseif (empty($response['openid'])){
            //缺少openid openid是qq的唯一标识
            throw new LogicException('解密数据错误');
        }

        $openId     = $response['openid'];
        $sessionKey = empty($response['session_key']) ? '' : $response['session_key'];
        $unionId    = empty($response['unionid']) ? '' : $response['unionid'];

        // 创建账号
        $userInfoField = 'user_id, status, last_login_time';
        $userInfo = UserModel::getInstance()->getInfo(['openid' => $openId,'server_id' => $serverId],$userInfoField);
        if(!$userInfo->isEmpty() && Constant::STATUS_CLOSE == $userInfo->status){
            throw new LogicException('账号已被禁用');
        }

        //已存在用户
        if (!$userInfo->isEmpty()){
            $userId = $userInfo->user_id;
            $isNewAccount = 0;
        }else{
            //注册新用户
            $isNewAccount = 1;
            $userId = $this->register($openId, $ip, $channelId, $fromApp, 0, $serverId, $sceneId);
        }
        //更新记录
        $updateData = [
            'last_login_ip'   => $ip,
            'last_login_time' => $now,
            'login_times'     => Db::raw('login_times+1'),
            'more'      => json_encode([
                'sessionKey' => $sessionKey
            ])
        ];
        $updateRs = UserModel::getInstance()->updateInfo(['user_id' => $userId],$updateData);
        if($updateRs === false){
            throw new LogicException('更新登录信息失败');
        }

        // 登录日志
        $loginLog = [
            'user_id'   => $userId,
            'is_new'    => $isNewAccount,
            'login_time'=> $now,
            'date'      => date('Ymd'),
        ];
        UserLoginLogModel::getInstance()->insertGetId($loginLog);

        //获取用户信息
        $userInfo = UserModel::getInstance()->getInfo(['openid' => $openId],'user_id,nickname,avatar,is_authorization,login_times,channel_id,scene_id');
        $token = JwtAuth::encode(['user_id' => $userInfo->user_id]);
        //新人邀请
        // FriendLogic::getInstance()->addFriend($shareUserId, $userId, $isNewAccount);

        // 用户点击分享链接记录
        ShareLogic::getInstance()->addUserVisitRecord($userId, $shareId, $shareUserId, $isNewAccount);

        //设置渠道用户
        ChannelLogic::getInstance()->setChannelTodayActiveUserCount($userInfo->toArray());
        if ($isNewAccount){
            ChannelLogic::getInstance()->setChannelTodayNewUserCount($channelId);
        }
        // 获取游戏基本信息
        return [
            'userInfo' => $userInfo,
            'character'=> GameLogic::getInstance()->getGameBaseInfo($userId),
            'token'    => $token,
            'equip'    => CharacterLogic::getInstance()->getUserEquipment($userId),
            'adConfig' => ConfigLogic::getInstance()->getAdConfig()
        ];
    }

    /**
     * 微信授权登录
     * @param $request
     */
    public function wxLogin($request)
    {
        $param = $request->param();

        // scene_id?: number;
        // appid?: string;
        // channel?: string;
        // share_id?: number;
        // share_user_id?: number;
        // share_uid?: number;
        // wxgamecid?: string;
        // code?:string;

        $code          = $param['code'] ?? '';       // login可获取得到的code
        $shareUserId   = $param['share_user_id'] ?? 0;// 邀请用户
        $shareId       = $param['share_id'] ?? 0; // 分享内容id
        $fromApp       = $param['appid'] ?? '';	     // 来源appid
        $channelId     = $param['channel'] ?? 0; // 来源渠道号
        $serverId      = $param['server_id'] ?? 1;// 分服ID
        $sceneId       = $param['scene_id'] ?? 0;// 场景值
        $ip            = $request->ip();
        $now           = time();

        //转换渠道ID
        $channelId = (int) ChannelModel::getInstance()->where(['channel_sn' => $channelId])->value('channel_id');
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
        if(isset($wxRs['unionid'])){
            $wxUserData['unionId'] = $wxRs['unionid'];
        }
        // 创建账号
        $userInfoField = 'user_id, status, last_login_time';
        $userInfo = UserModel::getInstance()->getInfo(['openid' => $openId,'server_id' => $serverId],$userInfoField)->toArray();
        if(!empty($userInfo) && Constant::STATUS_CLOSE == $userInfo['status']){
            throw new LogicException('账号已被禁用');
        }

        //已存在用户
        if ($userInfo){
            $userId = $userInfo['user_id'];
            $isNewAccount = 0;
        }else{
            //注册新用户
            $isNewAccount = 1;
            $userId = $this->register($openId, $ip, $channelId, $fromApp, 0, $serverId, $sceneId);
        }
        //更新记录
        $updateData = [
            'last_login_ip'   => $ip,
            'last_login_time' => $now,
            'login_times'     => Db::raw('login_times+1'),
            'more'            => json_encode($wxUserData)
        ];
        $updateRs = UserModel::getInstance()->updateInfo(['user_id' => $userId],$updateData);
        if($updateRs === false){
            throw new LogicException('更新登录信息失败');
        }

        // 登录日志
        $loginLog = [
            'user_id'   => $userId,
            'is_new'    => $isNewAccount,
            'login_time'=> $now,
            'date'      => date('Ymd')
        ];
        UserLoginLogModel::getInstance()->insertGetId($loginLog);

        //获取用户信息
        $userInfo = UserModel::getInstance()->getInfo(['openid' => $openId],'user_id,nickname,avatar,is_authorization,login_times,channel_id,scene_id');
        $token = JwtAuth::encode(['user_id' => $userInfo->user_id]);
        //新人邀请
        // FriendLogic::getInstance()->addFriend($shareUserId, $userId, $isNewAccount);

        // 用户点击分享链接记录
        ShareLogic::getInstance()->addUserVisitRecord($userId, $shareId, $shareUserId, $isNewAccount);

        //设置渠道用户
        ChannelLogic::getInstance()->setChannelTodayActiveUserCount($userInfo->toArray());
        if ($isNewAccount){
            ChannelLogic::getInstance()->setChannelTodayNewUserCount($channelId);
        }
        // 获取游戏基本信息
        return [
            'userInfo' => $userInfo,
            'character'=> GameLogic::getInstance()->getGameBaseInfo($userId),
            'token'    => $token,
            'equip'    => CharacterLogic::getInstance()->getUserEquipment($userId),
            'adConfig' => ConfigLogic::getInstance()->getAdConfig()
        ];
    }

    /**
     * 注册账号
     * @param $openId openId
     * @param $ip IP地址
     * @param $channelId 渠道ID
     * @param $fromApp 来自
     * @param int $isRobot 是否是机器人 1是；0否
     * @param int $serverId 服务器ID
     * @return int|string
     * @throws LogicException
     */
    public function register($openId, $ip, $channelId, $fromApp, $isRobot = 0, $serverId = 1,$sceneId = 0)
    {
        Db::startTrans();
        try {
            $insertUserInfo = [
                'server_id'       => $serverId,
                'openid'		  => $openId,
                'channel_id'      => $channelId,
                'last_login_ip'   => $ip,
                'login_times'     => 0,
                'last_login_time' => time(),
                'appid'	  		  => $fromApp,
                'is_robot'        => $isRobot,
                'create_time'     => time(),
                'scene_id'        => $sceneId
            ];

            $userId = UserModel::getInstance()->insertGetId($insertUserInfo);
            if (empty($userId)){
                throw new Exception('注册失败');
            }else{
                //设置用户默认头像和昵称
                UserModel::getInstance()->updateInfo(
                    ['user_id'=>$userId],
                    ['nickname'=>getUserDefaultNickname($userId),'avatar'=>getUserDefaultAvatar()]
                );
            }
            //初始化游戏
            InitLogic::getInstance()->initGame($userId,$serverId);
            Db::commit();
        }catch (Exception $e){
            Db::rollback();
            throw new LogicException($e->getMessage());
        }
        //初始计算战力
        CombatLogic::getInstance()->computeTotalPower($userId);
        return $userId;
    }

    /**
     * 微信用户信息处理
     * @param $userId
     * @param $param
     * @return bool
     * @throws LogicException
     */
    public function saveWxUserInfo($userId,$param)
    {
        $validate = new Validate([
            'encryptedData' => 'require',
            'iv'             => 'require',
        ]);

        $validate->message([
            'encryptedData.require' => '缺少参数encryptedData!',
            'iv.require'             => '缺少参数iv!',
        ]);

        if (!$validate->check($param)) {
            throw new LogicException($validate->getError());
        }

        $userInfo = UserModel::getInstance()->getInfo(['user_id' => $userId],'user_id,nickname,avatar,more');

        $wxappSettings = config('system.wxapp');
        //微信配置
        $appId     = $wxappSettings['appid'];
        $appSecret = $wxappSettings['secret'];

        $userInfoMore = json_decode($userInfo->more, true);
        $sessionKey = $userInfoMore['sessionKey'];

        $pc      = new WXBizDataCrypt($appId, $sessionKey);
        $errCode = $pc->decryptData($param['encryptedData'], $param['iv'], $wxUserData);
        if ($errCode != 0) {
            throw new LogicException('用户信息解密失败:'.$errCode);
        }

        $userInfo->nickname = $wxUserData['nickName'];
        $userInfo->avatar = $wxUserData['avatarUrl'];
        $userInfo->is_authorization = Constant::STATUS_OPEN;
        $res = $userInfo->save();
        if ($res){
            return  true;
        }else{
            return false;
        }
    }

    /**
     * QQ用户信息处理
     * @param $userId
     * @param $param
     * @return bool
     * @throws LogicException
     */
    public function saveQQUserInfo($userId,Request $request)
    {
        $encryptedData  = $request->param('encryptedData/s', null);
        $share_user_id  = $request->param('share_user_id/d', 0);
        $scene_id       = $request->param('scene_id/d', null);
        $iv             = $request->param('iv/s', null);

        // 获取应用配置信息
        $wxappConfig = config('system')['qqapp'];
        $appId       = $wxappConfig['appid'];
        $secret      = $wxappConfig['secret'];
        $currentTime = time();
        if (empty($encryptedData) || empty($iv)){
            throw new LogicException('缺少参数');
        }elseif (empty($userId)){
            //用户token登录
            throw new LogicException('用户未登录');
        }
        $userData = UserModel::getInstance()->getInfo(['user_id' => $userId]);
        $more = '';
        if ($userData->more) {
            $more = json_decode($userData->more, true);
        }
        $session_key = empty($more['sessionKey']) ? '' : $more['sessionKey'];
        $pc = new WXBizDataCrypt($appId, $session_key);
        $errCode = $pc->decryptData($encryptedData, $iv, $wxUserData);
        if ($errCode != 0) {
            throw new LogicException('用户信息解密失败');
        }
        if (!empty($wxUserData['openId'])) {
            $userData->openid = $wxUserData['openId'];
        }
        //用户昵称
        if (!empty($wxUserData['nickName'])) {
            $userData->nickname = $wxUserData['nickName'];
        }
        //性别
        if (!empty($wxUserData['gender'])) {
            $userData->sex = $wxUserData['gender'];
        }
        //城市
        if (!empty($wxUserData['city'])) {
            $userData->city = $wxUserData['city'];
        }
        //省份
        if (!empty($wxUserData['province'])) {
            $userData->province = $wxUserData['province'];
        }
        //国家
        if (!empty($wxUserData['country'])) {
            $userData->country = $wxUserData['country'];
        }
        //头像
        if (!empty($wxUserData['avatarUrl'])) {
            $userData->avatar = $wxUserData['avatarUrl'];
        }
        //appid
        if (!empty($wxUserData['watermark']['appid'])) {
            $userData->appid = $wxUserData['watermark']['appid'];
        }
        $ip = $request->ip(0, true);
        $userData->last_login_ip = $ip;
        $userData->last_login_time = time();
        $userData->login_times = Db::raw('login_times+1');
        $userData->save();

        return  true;
    }
}