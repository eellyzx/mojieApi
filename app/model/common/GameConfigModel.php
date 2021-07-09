<?php


namespace app\model\common;


use app\model\BaseModel;

class GameConfigModel extends BaseModel
{
    protected $table = 'game_config';
    protected $pk    = 'config_id';
}