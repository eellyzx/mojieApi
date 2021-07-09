<?php
/*
 * @Author: zane
 * @Date: 2020-02-19 16:50:29
 * @LastEditTime: 2020-07-15 11:12:50
 * @Description: 
 */

namespace app\service;

use app\exception\DingDingException;
use think\Exception;

/**
 * 钉钉消息服务
 */
class DingTalkService 
{
    protected $webhook;

    protected $projectName = '';

    protected $message = '';

    public function __construct($message)
    {
        $this->projectName = config('system.app_name');

        $this->message = is_array($message) ? json_encode($message, JSON_UNESCAPED_UNICODE) : $message;
        if(!config('system.webhook')){
            throw new DingDingException("项目-".$this->projectName.",缺少钉钉webhook配置参数");
        }
        $this->webhook = config('system.webhook');
    }

    /**
     * 发送消息
     * @return bool
     * @throws DingDingException
     */
    public function sendMsg()
    {  
        try {
            $msg = "## 项目-$this->projectName (异常提醒)\n\n"
                    ."> 异常信息: \n\n $this->message ";

            $content = $this->markdown('业务报警', $msg);
            $this->request_by_curl($this->webhook , $content);
        } catch (Exception $th) {
            throw new DingDingException("发送钉钉信息失败" . json_encode($content, JSON_UNESCAPED_UNICODE), $this->webhook);
        }
        return true;
    }

    /**
     * markdown格式
     * @param $title
     * @param $message
     * @return array
     */
    protected function markdown($title, $message)
    {

        $data = array (
            'msgtype' => 'markdown',
            'markdown' => array (
                'title' => $title,
                'text' => $message
            )
        );
        return $data;
    }

    /**
     * curl
     *
     * @param string $remoteServer
     * @param string $postString
     * @return array
     */
    protected function request_by_curl($remoteServer, $postString) {
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, $remoteServer);
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/json;charset=utf-8'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postString));  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
        // 线下环境不用开启curl证书验证, 未调通情况可尝试添加该代码
        $SSL = substr($remoteServer, 0, 5) == "https" ? true : false;
        if ($SSL) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名
        }
        $data = curl_exec($ch);
        $data = json_decode($data, 1);
        curl_close($ch);                
        return $data;
    }
}