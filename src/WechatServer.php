<?php
namespace Pine\Wechat;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class WechatServer
{
    protected $config = [];

    protected $client;

    /**
     * WechatServer constructor.
     * @param $appid 微信公众号APPID
     * @param $appsecret 微信公众号密钥
     */
    public function __construct($appid,$appsecret)
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
            $api_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $config['appID'] . "&secret=" . $config['appsecret'];

            $json = file_get_contents($api_url);

            $result = json_decode($json, true);

            return $result['access_token'];
        });
    }

    /**
     * 网页授权，用户同意获取code的路径
     *
     * @param $redirect_uri
     * @param string $scope
     * @param string $state
     * @return string
     */
    public function getCodeUri($redirect_uri, $scope = 'snsapi_base', $state = 'STATE')
    {
        $api_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->config['appID'] . '&redirect_uri=' . urlencode($redirect_uri) . '&response_type=code&scope=' . $scope . '&state=' . $state . '&connect_redirect=1#wechat_redirect';

        return $api_url;
    }

    /**
     * 网页授权，用户同意获取code
     *
     * @param $redirect_uri
     * @param string $scope
     * @param string $state
     */
    public function getCode($redirect_uri, $scope = 'snsapi_base', $state = 'STATE')
    {
        $api_url = $this->getCodeUri($redirect_uri, $scope = 'snsapi_base', $state = 'STATE');
        header('Location:' . $api_url);
    }

    /**
     * 网页授权，通过code换取网页授权access_token
     *
     * @param $code
     * @return mixed
     */
    public function getCodeToken($code)
    {
        if ($code) {
            $api_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?';
            $api_url = $api_url . 'appid=' . $this->appid;
            $api_url = $api_url . '&secret=' . $this->secret;
            $api_url = $api_url . '&code=' . $code;
            $api_url = $api_url . '&grant_type=authorization_code';

            $respond = $this->client->request('get',$api_url);
            if ($respond->getStatusCode() === 200) {
                return json_decode($respond->getBody()->getContents(), true);
            }else{
                throw new \LogicException('请求失败');
            }
        } else {
            throw new \LogicException('code为空');
        }
    }
}
