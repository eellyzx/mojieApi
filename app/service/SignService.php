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
    public function verify(Request $request, $userId = 0)
    {
        $apiSign = $request->header('apiSign');
        if (empty($apiSign)){
            //没有传就不校验
            return true;
        }
        $this->postParam = $request->param();
        $this->apiSign   = $apiSign;
        $this->timestamp = $request->param('timestamp');
        // $this->apiKey    = $request->param('apiKey');
        if (!empty($userId)){
            $this->postParam['uid'] = $userId;
        }

        if(empty($this->apiSign)){
            throw new SignException("签名验证失败");
        }elseif(empty($this->timestamp)){
            throw new SignException("签名验证失败");
        }elseif (abs(time() - $this->timestamp) > 300){
            throw new SignException('请求超时');
        }

        $key    = config('system.api')['key'];
        $secret = config('system.api')['secret'];

        //获取所有请求的参数
        $AllParam = $this->postParam;
        $AllParam['apiKey'] = $key;
        unset($AllParam['module']);
        unset($AllParam['controller']);
        unset($AllParam['action']);

        ksort($AllParam);	//根据键对数组进行升序排序
        $hashData ='';
        foreach($AllParam as $k => $v){
            $hashData .= '&'.$k.'='. rawurlencode($v);
        }
        $hashData = ltrim($hashData,'&');

        $genSign = hash_hmac('md5', $hashData, $secret );
        if($genSign != $this->apiSign){
            throw new SignException('签名验证失败');
        }
        return true;
    }
}
