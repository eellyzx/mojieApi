<?php


namespace app\model\user;


use app\model\BaseModel;

class UserApiTimesModel extends BaseModel
{
    protected $table = 'user_api_times';
    protected $pk    = 'id';

    /**
     * 用户访问的api次数自增
     */
    public function setApiInc($userId,$api = '')
    {
        ! $api && $api = request()->controller() .'/'.request()->action();

        // 查出信息
        $info = $this->getInfo(['user_id' => $userId, 'api' => $api],'id,times')->toArray();

        if ($info) {
            $data                = $info;
            $data['times']       = $data['times'] + 1;
            $data['update_time'] = time();
        } else {
            $data['user_id']     = $userId;
            $data['api']         = $api;
            $data['times']       = 1;
            $data['create_time'] = time();
        }

        $this->exists((bool)$info)->save($data);

        return $data['times'];

    }
}