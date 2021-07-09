<?php


namespace app\model\user;

use app\model\BaseModel;

/**
 * Class UserModel
 * @package app\model\user
 */
class UserModel extends BaseModel
{
    protected $table = 'user';
    protected $pk    = 'id';

}