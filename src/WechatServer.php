<?php
namespace Pine\Wechat;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Config;
use Pine\Wechat\WechatJSSDKServer as wechatJssdk;

/**
 * @method static wechatJssdk wechatJssdk(array $config) 微信js-sdk
 */
class WechatServer
{
    protected $config =[] , $configs = [];

    protected $client;

    /**
     * WechatServer constructor.
     * @param $appid 微信公众号APPID
     * @param $appsecret 微信公众号密钥
     */
    public function __construct($appid,$appsecret)
    {
        $config = [
            'appID' => $appid,
            'appsecret' => $appsecret
        ];

        $this->config = $config;
        $this->configs = new Config($config);

        $this->client = new Client();
    }

    /**
     * 获取微信公众号access_token
     *
     * @return mixed
     */
    public function getAccessToken()
    {
        $config = $this->config;
        $time = now()->addSeconds(7200);
        return Cache::remember('access_token', $time, function () use ($config) {
            $api_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $config['appID'] . "&secret=" . $config['appsecret'];

            $json = file_get_contents($api_url);

            $result = json_decode($json, true);

            return $result['access_token'];
        });
    }
}

