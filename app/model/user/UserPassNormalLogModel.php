<?php


namespace app\model\user;

use app\model\BaseModel;

/**
 * Class UserModel
 * @package app\model\user
 */
class UserPassNormalLogModel extends BaseModel
{
    protected $table = 'user_pass_normal_log';
    protected $pk    = 'log_id';

}