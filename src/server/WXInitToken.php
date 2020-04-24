<?php

namespace Pine\Wechat\Server;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

trait WXInitToken
{
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
     * @param string $action 执行的检测动作，允许的值：dns（做域名解析）、ping（做ping检测）、all（dns和ping都做）
     * @param string $check_operator 指定平台从某个运营商进行检测，允许的值：CHINANET（电信出口）、UNICOM（联通出口）、CAP（腾讯自建出口）、DEFAULT（根据ip来选择运营商）
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
