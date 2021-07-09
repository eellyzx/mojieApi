<?php
namespace app\logic\common;
use app\constant\Constant;
use app\exception\LogicException;
use app\logic\BaseLogic;
use app\logic\game\CharacterLogic;
use app\logic\game\FriendLogic;
use app\logic\game\GameLogic;
use app\logic\game\InitLogic;
use app\logic\task\EverydayTaskLogic;
use app\model\user\UserLoginLogModel;
use app\model\user\UserModel;
use jwt\JwtAuth;
use Redis\Redis;
use think\Exception;
use think\facade\Db;
use think\Validate;
use tools\Strings;
use wxapp\aes\WXBizDataCrypt;

/**
 * 用户动作操作缓存，对用户游戏过程中的操作进行缓存
 * Class SceneLogic
 * @package app\logic\common
 */
class UserActionLogic extends BaseLogic
{
    /**
     * 组装redis Key
     * @param $key   key
     * @param $userId 用户ID
     * @param int $day 是否加上每日日期 1是，0否
     * @return string
     */
    public function getHandleRedisKey($key, $userId, $day = 0)
    {
        if ($day) {
            return 'action_user_' . $userId . '_' . $key . '_' . date('Ymd');
        } else {
            return 'action_user_' . $userId . '_' . $key;
        }
    }

    /**
     * 设置用户动作次数累加
     * @param $userId 用户ID
     * @param $keyName  key值
     * @param int $incr 增量
     * @return bool
     */
    public function setUserActionCount($userId,$keyName,$incr = 1)
    {
        if (empty($userId) || empty($keyName) || empty($incr)){
            return true;
        }
        //每日Key
        $key = $this->getHandleRedisKey($keyName,$userId,true);
        $dayOnline = (int)Redis::get($key);
        if (empty($dayOnline)){
            Redis::setex($key, 24 * 60 * 60, $incr);
        }else{
            Redis::incrby($key, $incr);
        }
        //总和key
        $key = $this->getHandleRedisKey($keyName,$userId);
        Redis::incrby($key, $incr);
        return true;
    }

    /**
     * 获取用户操作数据
     * @param $userId 用户ID
     * @param $keyName key值
     * @param bool $isDay true获取今天，false获取所有
     * @return mixed
     */
    public function getUserActionCount($userId,$keyName,$isDay = false)
    {
        $key = $this->getHandleRedisKey($keyName,$userId,$isDay);
        return Redis::get($key);
    }

    /**
     * 设置用户金币缓存
     * @param $userId
     * @param $keyName
     * @param $money
     */
    public function setUserMoney($userId,$keyName,$money = 0)
    {
        $data = $this->getUserActionCount($userId,$keyName);
        if (Strings::mathComp($money,$data) === 1){
            $key = $this->getHandleRedisKey($keyName,$userId);
            Redis::setex($key, 30 * 24 * 60 * 60, $money);
        }
        return true;
    }

    /**
     * 清空用户当日数据
     * @param $userId
     * @param $keyName
     */
    public function chearUserActionCount($userId,$keyName)
    {
        $key = $this->getHandleRedisKey($keyName, $userId, 1);
        return Redis::set($key,0);
    }
}