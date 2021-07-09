<?php


namespace app\validate\friend;


use app\model\friend\FriendModel;
use app\validate\BaseValidate;
use app\model\user\UserModel;

class FriendValidate extends BaseValidate
{
    protected $rule = [
        'user_id' => 'require|different:friend_id|mustNotBeFriend|isUser',
        'friend_id' => 'require'
    ];

    protected $message = [
        'user_id.different' => '不能邀请自己'
    ];

    protected $scene = [
        'addFriend'=>['user_id','friend_id']
    ];


    /**
     * 必须是好友
     */
    protected function mustBeFriend($value, $rule, $data, $field)
    {
        if (empty($data['user_id']) || empty($data['friend_id'])) {
            return '用户id或好友id为空!';
        }

        $where = "(user_id = {$data['user_id']} and friend_id = {$data['friend_id']}) or 
                  (user_id = {$data['friend_id']} and friend_id = {$data['user_id']})";
        $id = FriendModel::getInstance()->getFriendValue($where);

        if ($id){
            return true;
        }
        return '对方不是你是好友';
    }

    /**
     * 必须不是好友
     */
    protected function mustNotBeFriend($value, $rule, $data, $field)
    {
        if (empty($data['user_id']) || empty($data['friend_id'])) {
            return '参数有误~';
        }

        $where = "(user_id = {$data['user_id']} and friend_id = {$data['friend_id']}) or 
                  (user_id = {$data['friend_id']} and friend_id = {$data['user_id']})";
        $id = FriendModel::getInstance()->getFriendValue($where);

        if ($id){
            return '对方已是您的好友';
        }
        return true;
    }

    /**
     * 是否存在邀请人 否则错误
     *
     * @param $value
     */
    protected function isUser($value)
    {
        $userId = UserModel::getInstance()->where('user_id',$value)->value('user_id');

        if (! $userId){
            return '邀请人不存在，记录邀请失败';
        }
        return true;
    }

    /**
     * 是好友或者为空
     */
    protected function mustBeFriendOrEmpty($value, $rule, $data, $field)
    {
        if (empty($data['friend_id'])){
            return true;
        }

        $isRobot = UserModel::getInstance()->getValueByField(['user_id'=>$data['friend_id']],'is_robot');
        if ($isRobot)
        {
            return true;
        }

        return $this->mustBeFriend($value, $rule, $data, $field);
    }
}
