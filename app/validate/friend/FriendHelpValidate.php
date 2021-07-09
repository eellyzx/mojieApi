<?php


namespace app\validate\friend;


use app\constant\RedisConstant;
use app\model\common\GameConfigModel;
use app\validate\BaseValidate;
use Redis\Redis;

class FriendHelpValidate extends FriendValidate
{
    protected $rule = [
        'user_id'=>'require|different:friend_id|mustBeFriendOrEmpty',
        'friend_id'=>'require'
    ];

    protected $message = [
        'user_id.different'=>'不能设置自已助战'
    ];

    protected $scene = [
        'setHelp'=>['user_id','friend_id'],
        'getHelp'=>['user_id','friend_id'],
    ];

    /**
     * getHelp 验证场景定义
     *
     * @return \app\validate\friend\FriendHelpValidate
     */
    protected function sceneGetHelp()
    {
        return $this->only(['user_id','friend_id'])
            ->remove('user_id', ['mustBeFriendOrEmpty'])
            ->append('user_id', ['mustBeFriend','dateHelpLimit']);
    }

    /**
     * 用户每天邀请好友助战上限
     *
     * @param $value
     * @param $rule
     * @param $data
     * @param $field
     */
    protected function dateHelpLimit($value, $rule, $data, $field)
    {
        // 查找用户每日助战上限配置
        $limit = GameConfigModel::getInstance()->getValueByField(['key'=>'friends_box'],'value');

        // 获得该用户当天邀请助战次数
        $num = (int)Redis::get(RedisConstant::$friendDateHelpLimit.date('Ymd').'_'.$value);
        if ($limit <= $num){
            return '已达到助战上限！';
        }
        return true;
    }


}