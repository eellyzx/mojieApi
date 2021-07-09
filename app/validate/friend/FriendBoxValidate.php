<?php


namespace app\validate\friend;


use app\constant\ConfigConstant;
use app\constant\RedisConstant;
use app\logic\common\ConfigLogic;
use app\model\friend\FriendBoxLogModel;
use Redis\Redis;

class FriendBoxValidate extends FriendValidate
{
    protected $rule = [
        'user_id'=>'require|different:friend_id|mustBeFriend|hadBox',
        'friend_id'=>'require'
    ];

    protected $message = [
        'user_id.different'=>'不能偷取自己的宝箱'
    ];

    protected $scene = [
        'stealBox'=>['user_id','friend_id'],
        'gotReward'=>['user_id'=>'require|ip','friend_id']
    ];

    /**
     * gotReward 验证场景定义
     *
     * @return \app\validate\friend\FriendBoxValidate
     */
    public function sceneGotReward()
    {
        return $this->only(['user_id','friend_id'])
            ->remove('user_id', ['mustBeFriend','hadBox','different:friend_id'])
            ->append('user_id','gotReward');
    }


    /**
     * 查询好友是否有宝箱 没有则错误
     * @param $value
     * @param $rule
     * @param $data
     * @param $field
     */
    protected function hadBox($value, $rule, $data, $field)
    {
        $where['user_id'] = $data['user_id'];
        $where['friend_id'] = $data['friend_id'];
        $where['date_time'] = strtotime(date('Y-m-d',time())); // 当天的宝箱

        $count = FriendBoxLogModel::getInstance()->where($where)->count();

        if ($count){
            return '您已偷过该好友的宝箱了~';
        }
        return true;
    }

    /**
     * 是否已领取奖励 是则错误
     *
     * @param $value
     * @param $rule
     * @param $data
     * @param $field
     */
    protected function gotReward($value, $rule, $data, $field){
        $data['date_time'] = strtotime(date('Y-m-d',time()));
        $count = FriendBoxLogModel::getInstance()->where($data)->count();
        if ($count){
            return '已经领取奖励';
        }

        $time = Redis::get(RedisConstant::$friendBoxTime.date('Ymd').'_'.$data['user_id']);
        $limit = ConfigLogic::getInstance()->getGameConfigByKey(ConfigConstant::$friendsBox);

        if ($time >= $limit)
        {
            return '每日偷宝箱次数已达上限';
        }

        return true;
    }

}
