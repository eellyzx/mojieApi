<?php


namespace app\model\user;

use app\model\BaseModel;

/**
 * Class UserModel
 * @package app\model\user
 */
class UserPassFailLogModel extends BaseModel
{
    protected $table = 'user_pass_fail_log';
    protected $pk    = 'log_id';
}