<?php


namespace app\validate\task;


use app\model\title\TitleModel;
use app\model\title\TitleUnlockModel;
use app\validate\BaseValidate;

class TitleValidate extends BaseValidate
{
    protected $rule = [
        'user_id'  => 'require|haveGotTitle',
        'title_id' => 'require|isTitle'
    ];

    protected $message = [

    ];

    protected $scene = [
        'getTitle' => ['user_id', 'title_id']
    ];


    /**
     * 必须未领取该称号
     * @param $value
     * @param $rule
     * @param $data
     * @param $field
     */
    public function haveGotTitle($value, $rule, $data, $field){
        $id = TitleUnlockModel::getInstance()->getUnlockTitleRecord($data,'id');

        if ($id->isExists()){
            return '已领取该称号';
        }
        return true;
    }

    /**
     * 必须已领取该称号
     * @param $value
     * @param $rule
     * @param $data
     * @param $field
     */
    public function noHaveGotTitle($value, $rule, $data, $field){
        $id = TitleUnlockModel::getInstance()->getUnlockTitleRecord($data,'id');

        if (! $id){
            return '未领取该称号,请先领取';
        }
        return true;
    }

}
