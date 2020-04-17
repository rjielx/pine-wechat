<?php

namespace Pine\Wechat\Server;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

trait WXInitToken
{
    protected $config = [];

    protected $client;

    /**
     * WechatServer constructor.
     * @param $appid 微信公众号APPID
     * @param $appsecret 微信公众号密钥
     */
    public function __construct($appid, $appsecret)
    {
        $this->config = [
            'appID' => $appid,
            'appsecret' => $appsecret
        ];
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
            $api_url = static::$uri . "/cgi-bin/token?grant_type=client_credential&appid=" . $config['appID'] . "&secret=" . $config['appsecret'];

            $json = file_get_contents($api_url);

            $result = json_decode($json, true);

            return $result['access_token'];
        });
    }


    /**
     * 获取微信callback IP地址
     *
     * @return mixed
     * @throws \Exception
     */
    public function callbackIP()
    {
        $uri = static::$uri.'/cgi-bin/getcallbackip?access_token='.$this->getAccessToken();

        $respond = $this->client->request('get', $uri);
        if ($respond->getStatusCode() === 200) {
            $result = json_decode($respond->getBody()->getContents(), true);

            return $result;
        } else {
            throw new \Exception('请求失败');
        }
    }


    /**
     * 网络监测
     *
     * @param string $action
     * @param string $check_operator
     * @return mixed
     * @throws \Exception
     */
    public function networkCheck($action = 'all',$check_operator = 'DEFAULT')
    {
        $uri = static::$uri.'/cgi-bin/callback/check?access_token=ACCESS_TOKEN';

        $params = [
            'action' => $action,
            'check_operator' => $check_operator
        ];

        $respond = $this->client->request('post', $uri,['form_params' => $params]);
        if ($respond->getStatusCode() === 200) {
            $result = json_decode($respond->getBody()->getContents(), true);

            return $result;
        } else {
            throw new \Exception('请求失败');
        }
    }
}
