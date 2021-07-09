<?php


namespace app\model\user;

use app\model\BaseModel;

/**
 * Class UserEmailModel
 * @package app\model\user
 */
class UserEmailModel extends BaseModel
{
    protected $table = 'user_email';
    protected $pk    = 'id';

    /**
     * 发送邮件（单个玩家）
     * @param $data
     */
    public function sendEmail($data)
    {
        $emailData = [];
        $emailData['user_id'] = $data['user_id'];
        $emailData['title'] = $data['title'];
        $emailData['call'] = $data['call'];
        $emailData['content'] = $data['content'];
        $emailData['sign'] = $data['sign'];
        $emailData['annex'] = $data['annex'];
        if(empty($data['annex'])){
            $emailData['annex_status'] = 1;
        }
        $emailData['create_time'] = $data['create_time'];
        return $this->insertGetId($emailData);
    }

}