<?php
/*
 * @Author: zane
 * @Date: 2020-06-10 11:01:05
 * @LastEditTime: 2020-11-25 15:24:58
 * @Description: 
 */
namespace app\logic\common;


use app\exception\LogicException;
use app\logic\BaseLogic;
use tools\Curl;

/**
 *  微信小游戏
 * 
 *  @author zane 
 */
class WechatLogic extends BaseLogic {

    private $appId = '';
    private $secret = '';
    private $code = '';
    private $apiUrl = 'https://api.weixin.qq.com/sns/jscode2session?';

    public function __construct($appId, $secret, $code)
    {
        $this->appId  = $appId;
        $this->secret = $secret;
        $this->code   = $code;
    }

    /**
     * 小游戏授权登录
     *
     * @return array
     */
    public function miniProgram()
    {
        if(empty($this->appId)){
            throw new LogicException('缺少appid配置');
        }
        if(empty($this->secret)){
            throw new LogicException('缺少secret配置');
        }
        if(empty($this->code)){
            throw new LogicException('缺少code');
        }
        $data['appid']      = $this->appId;
        $data['secret']     = $this->secret;
        $data['js_code']    = $this->code;
        $data['grant_type'] = 'authorization_code';
        $httpStr = http_build_query($data);
        $url     = $this->apiUrl.$httpStr;

        $header  = array("Content-type: application/json;charset='utf-8'", "Accept: application/json");
        $curlGet = Curl::get($url, $header);

        if($curlGet == false){
            throw new LogicException('访问微信授权登录API失败');
        }
        $result = json_decode($curlGet, true);

        if (!empty($result['errcode'])) {
            throw new LogicException("访问微信授权登录API失败,错误码：".$result['errcode']);
        }
        return $result;
    }




}




