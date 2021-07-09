<?php


namespace app\validate\share;


use app\model\ad\ShareLogModel;
use app\model\share\ShareContentModel;
use app\validate\BaseValidate;

class ShareValidate extends BaseValidate
{
    protected $rule = [
        'share_id' => 'require|mustBeShare',
        'user_id' => 'require',
        'share_user_id' => 'require|mustShareLog|different:user_id',
        'share_content_id'=>'require'
    ];

    protected $message = [
    ];

    protected $scene = [
        'addRecord'=>['share_id'],
        'addVisit' => ['user_id','share_user_id','share_content_id']
    ];

    /**
     * 必须是分享
     *
     * @param $value
     */
    protected function mustBeShare($value)
    {
        $info = ShareContentModel::getInstance()->getInfo(['share_id'=>$value],'share_id')->toArray();

        return $info ? true : '没有该分享信息';
    }

    /**
     * 必须存在分享记录
     *
     * @param $value
     * @param $rule
     * @param $data
     * @param $field
     */
    protected function mustShareLog($value, $rule, $data, $field)
    {
        $where['user_id'] = $data['share_user_id'];
        $where['share_content_id'] = $data['share_content_id'];

        $res = ShareLogModel::getInstance()->getInfo($where)->toArray();
        if (empty($res)){
            return '没有分享记录';
        }

        return true;
    }
}