<?php


namespace app\model\user;

use app\model\BaseModel;

/**
 * Class UserModel
 * @package app\model\user
 */
class UserGameDataModel extends BaseModel
{
    protected $table = 'user_game_data';
    protected $pk    = 'user_id';

}