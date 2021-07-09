<?php
namespace app\logic\common;
use app\constant\Constant;
use app\constant\RedisKeyConstant;
use app\exception\LogicException;
use app\logic\BaseLogic;
use app\logic\channel\ChannelLogic;
use app\logic\combat\CombatLogic;
use app\logic\game\GameLogic;
use app\logic\game\InitLogic;
use app\logic\game\CharacterLogic;
use app\logic\task\EverydayTaskLogic;
use app\model\user\UserLoginLogModel;
use app\model\user\UserModel;
use app\model\user\UserCertifyModel;
use jwt\JwtAuth;
use Redis\Redis;
use think\Exception;
use think\facade\Db;

class UserCertifyLogic extends BaseLogic
{
    /**
     * 用户注册
     * @param $request
     */
    public function register($request)
    {
        $username = $request->param('username');
        $password = $request->param('password');
        $email = $request->param('email');
        $realname = $request->param('realname');
        $idcardNum = $request->param('idcard_num');

        //参数过滤
        if(empty($username) || empty($password) || empty($email) || empty($realname) || empty($idcardNum)){
            throw new LogicException('参数不能为空');
        }
        if(strlen( $username ) > 16 || strlen( $username ) < 6){
            throw new LogicException('用户名长度不符合');
        }
        if(strlen( $password ) > 20 || strlen( $password ) < 6){
            throw new LogicException('密码长度不符合');
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new LogicException('请输入有效的邮箱');
        }
        if(!validation_filter_id_card($idcardNum)){
            throw new LogicException('请输入正确的身份证号码');
        }
        $userRow = UserModel::getInstance()->getInfo(['nickname'=>$username])->toArray();
        if(!empty($userRow)){
            throw new LogicException('该用户名已存在');
        }

        Db::startTrans();
        try {
            $now = time();
            $insertUserCerInfo = [
                'username' => $username,
                'password' => md5($password),
                'email' => $email,
                'realname' => $realname,
                'idcard_num' => $idcardNum,
                'last_login_ip' => $request->ip(),
                'last_login_time'     => $now,
                'create_time' => $now
            ];
            $userId = UserCertifyModel::getInstance()->insertGetId($insertUserCerInfo);
            if (empty($userId)) {
                throw new Exception('user_certify insert fail');
            } else {
                //user表插入用户数据
                $insertUserInfo = [
                    'user_id'   => $userId,
                    'nickname'     => $username,
                    'openid' => $userId,
                    'last_login_ip' => $request->ip(),
                    'last_login_time'     => $now,
                    'login_times' => 1,
                    'create_time' => $now
                ];
                $uid = UserModel::getInstance()->insertGetId($insertUserInfo);
                if(empty($uid)){
                    throw new Exception('user insert fail'.json_encode($insertUserInfo));
                }
            }
            //初始化游戏
            InitLogic::getInstance()->initGame($userId);
            //初始计算战力
            CombatLogic::getInstance()->computeTotalPower($userId);
            // 登录日志
            $loginLog = [
                'user_id'   => $userId,
                'is_new'    => 1,
                'login_time'=> $now,
            ];
            UserLoginLogModel::getInstance()->insertGetId($loginLog);

            //设置渠道用户
            //获取用户信息
            $userData = UserModel::getInstance()->getInfo(['user_id' => $userId],'user_id,nickname,avatar,is_authorization,login_times,channel_id,scene_id');
            ChannelLogic::getInstance()->setChannelTodayActiveUserCount($userData->toArray());
            ChannelLogic::getInstance()->setChannelTodayNewUserCount();
            Db::commit();
        } catch (Exception $th) {
            Db::rollback();
            throw new LogicException('注册失败-' . $th->getMessage());
        }

        $userInfo = UserCertifyModel::getInstance()->getInfo(['user_id' => $userId]);
        $token = JwtAuth::encode(['user_id' => $userInfo->user_id]);

        // 获取游戏基本信息
        return [
            'token' => $token,
            'userInfo' => UserModel::getInstance()->getInfo(['user_id' => $userId],'user_id,nickname,avatar,login_times'),
            'character'=> GameLogic::getInstance()->getGameBaseInfo($userId),
            'equip'    => CharacterLogic::getInstance()->getUserEquipment($userId),
            'isAdult' => isAdult($idcardNum),
        ];

    }

    /**
     * 用户登录
     * @param $request
     */
    public function login($request)
    {
        $username = $request->param('username');
        $password = $request->param('password');

        //参数过滤
        if(empty($username) || empty($password)){
            throw new LogicException('参数不能为空');
        }

        $userInfo = UserCertifyModel::getInstance()->getInfo(['username' => $username,'password'=>md5($password)])->toArray();
        if(empty($userInfo)){
            throw new LogicException('账户名或密码错误');
        }

        Db::startTrans();
        try {
            $now = time();
            $userId = $userInfo['user_id'];
            $updateData = [
                'last_login_ip'   => $request->ip(),
                'last_login_time' => $now
            ];
            $updateRs = UserCertifyModel::getInstance()->updateInfo(['user_id' => $userId],$updateData);
            if($updateRs === false){
                throw new Exception("更新用户信息失败");
            }
            $updateData = [
                'last_login_ip' => $request->ip(),
                'last_login_time' => $now,
                'login_times' => Db::raw('login_times+1')
            ];
            $updateRs = UserModel::getInstance()->updateInfo(['user_id' => $userId],$updateData);
            if($updateRs === false){
                throw new Exception("更新用户信息失败");
            }
            // 登录日志
            $loginLog = [
                'user_id'   => $userId,
                'is_new'    => 0,
                'login_time'=> $now,
            ];
            UserLoginLogModel::getInstance()->insertGetId($loginLog);
            Db::commit();
        } catch (Exception $th) {
            Db::rollback();
            throw new LogicException('注册失败-' . $th->getMessage());
        }

        $token = JwtAuth::encode(['user_id' => $userInfo['user_id']]);
        // 获取游戏基本信息
        return [
            'token' => $token,
            'userInfo' => UserModel::getInstance()->getInfo(['user_id' => $userInfo['user_id']],'user_id,nickname,avatar,login_times'),
            'character'=> GameLogic::getInstance()->getGameBaseInfo($userInfo['user_id']),
            'equip'    => CharacterLogic::getInstance()->getUserEquipment($userId),
            'isAdult' => isAdult($userInfo['idcard_num']),
            'adConfig' => ConfigLogic::getInstance()->getAdConfig()
        ];



    }

    /**
     * 游客登录
     * @param $request
     */
    public function visitorLogin($request)
    {

        //创建一个新账号
        $username = 'vu_';
        $password = 'vp_';
        $username .= mt_rand(100000,999999);
        $password .= mt_rand(100000,999999);

        Db::startTrans();
        try {
            $now = time();
            $insertUserCerInfo = [
                'username' => $username,
                'password' => md5($password),
                'last_login_ip' => $request->ip(),
                'last_login_time'     => $now,
                'create_time' => $now
            ];
            $userId = UserCertifyModel::getInstance()->insertGetId($insertUserCerInfo);
            if (empty($userId)) {
                throw new Exception('user_certify insert fail');
            } else {
                //user表插入用户数据
                $insertUserInfo = [
                    'user_id'   => $userId,
                    'nickname'     => $username,
                    'openid' => $userId,
                    'last_login_ip' => $request->ip(),
                    'last_login_time'     => $now,
                    'login_times' => 1,
                    'create_time' => $now
                ];
                $uid = UserModel::getInstance()->insertGetId($insertUserInfo);
                if(empty($uid)){
                    throw new Exception('user insert fail'.json_encode($insertUserInfo));
                }
            }
            //初始化游戏
            InitLogic::getInstance()->initGame($userId);
            // 登录日志
            $loginLog = [
                'user_id'   => $userId,
                'is_new'    => 1,
                'login_time'=> $now,
            ];
            UserLoginLogModel::getInstance()->insertGetId($loginLog);
            Db::commit();
        } catch (Exception $th) {
            Db::rollback();
            throw new LogicException('注册失败-' . $th->getMessage());
        }

        $token = JwtAuth::encode(['user_id' => $userId]);
        // 获取游戏基本信息
        return [
            'token' => $token,
            'userAccount' => ['username'=> $username,'password' => $password],
            'userInfo' => UserModel::getInstance()->getInfo(['user_id' => $userId],'user_id,nickname,avatar,login_times'),
            'character'=> GameLogic::getInstance()->getGameBaseInfo($userId),
            'equip'    => CharacterLogic::getInstance()->getUserEquipment($userId),
            'isAdult' => false,
        ];

    }


}