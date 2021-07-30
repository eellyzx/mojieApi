<?php
namespace app\controller;

use app\logic\monster\MonsterLogic;

class IndexController extends Base
{
    public $isNeedLogin = false;

    public function index()
    {
        MonsterLogic::getInstance()->initMapMonster();
    }
}
