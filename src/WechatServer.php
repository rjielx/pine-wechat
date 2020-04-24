<?php
namespace Pine\Wechat;

use GuzzleHttp\Client;
use Pine\Wechat\Config\ApiUrl;
use Pine\Wechat\Server\WXCustomMenu;
use Pine\Wechat\Server\WXInitToken;
use Pine\Wechat\Server\WXJsapiJSSDK;
use Pine\Wechat\Server\WXWebpage;

class WechatServer
{
    // 基础信息类
    use ApiUrl,WXInitToken;

    // 应用类
    use WXWebpage,WXJsapiJSSDK,WXCustomMenu;

    protected $client;
    protected $config = [];  // 微信配置

    /**
     * WechatServer constructor.
     * @param $appid 微信公众号APPID
     * @param $appsecret 微信公众号密钥
     */
    public function __construct($appid, $appsecret)
    {
        $this->client = new Client();

        $this->config = [
            'appID' => $appid,
            'appsecret' => $appsecret
        ];
    }

    public function test()
    {
        $this->client->request('','','');
    }
}

