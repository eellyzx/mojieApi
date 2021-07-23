<?php
namespace app\service;

use app\exception\SignException;
use app\Request;

class SignService
{
    /**
     * 客户端传参签名
     * @var string
     */
    public $apiSign   = '';

    /**
     * 客户端传参时间戳
     * @var string
     */
    protected $timestamp = '';

    /**
     * 客户端传参apiKey
     * @var string
     */
    protected $apiKey    = '';

    /**
     * 授权信息
     * @var string
     */
    protected $authorization = '';

    /**
     * 客户端参数
     * @var array
     */
    protected $postParam = [];

    /**
     * @return static
     */
    public static function getInstance()
    {
        return new static();
    }

    /**
     * 检查签名
     * @param Request $request
     * @return bool
     * @throws SignException
     */
    public function verify(Request $request, $token)
    {
        $apiSign = $request->header('sign');
        if (empty($apiSign)){
            //没有传就不校验
            return true;
        }
        $this->postParam = $request->param();
        $this->apiSign   = $apiSign;
        $this->timestamp = $request->header('timestamp');
        $this->authorization = $request->header('Authorization','');
        // $this->apiKey    = $request->param('apiKey');

        if(empty($this->apiSign)){
            throw new SignException("签名验证失败");
        }elseif(empty($this->timestamp)){
            throw new SignException("签名验证失败");
        }elseif (abs(time() - $this->timestamp) > 10){
            throw new SignException('请求超时');
        }
        //获取所有请求的参数
        $AllParam = $this->postParam;
        unset($AllParam['module']);
        unset($AllParam['controller']);
        unset($AllParam['action']);

        ksort($AllParam);	//根据键对数组进行升序排序
        $hashData ='';
        foreach($AllParam as $k => $v){
            $hashData .= $k.'='. urldecode($v).'&';
        }
        $hashData .= 'timestamp='.$this->timestamp.'&token='.$this->authorization;
        $hashData = trim($hashData,"&");
        $genSign = md5(urlencode($hashData));
        if($genSign != $this->apiSign){
            dump(urlencode($hashData));
            exit();
            throw new SignException('签名验证失败');
        }
        return true;
    }
}