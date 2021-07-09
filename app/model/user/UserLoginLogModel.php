<?php


namespace app\model\user;

use app\model\BaseModel;

/**
 * Class UserModel
 * @package app\model\user
 */
class UserLoginLogModel extends BaseModel
{
    protected $table = 'user_login_log';
    protected $pk    = 'id';

    /**
     * 获取登录天数
     * @param $userId
     */
    public function getLoginCount($userId)
    {
        return $this->where(['user_id' => $userId])->count('DISTINCT date');
    }
}