<?php
namespace Pine\Wechat\Server;

trait WXWebpage
{
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
        $api_url = $this->getCodeUri($redirect_uri, $scope, $state);
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
            $api_url = $api_url . 'appid=' . $this->config['appID'];
            $api_url = $api_url . '&secret=' . $this->config['appsecret'];
            $api_url = $api_url . '&code=' . $code;
            $api_url = $api_url . '&grant_type=authorization_code';

            $respond = $this->client->request('get', $api_url);
            if ($respond->getStatusCode() === 200) {
                $result = json_decode($respond->getBody()->getContents(), true);

                return $result;
            } else {
                throw new \LogicException('请求失败');
            }
        } else {
            throw new \LogicException('code为空');
        }
    }

    /**
     * 网页授权,刷新CodeToken
     *
     * @return mixed
     */
    /**
     * 网页授权,刷新CodeToken
     *
     * @param $refresh_token
     * @return mixed
     */
    public function refreshCodeToken($refresh_token)
    {
        if ($refresh_token) {

            $api_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=' . $this->config['appID'] . '&refresh_token=' . $refresh_token . '&grant_type=refresh_token';

            $respond = $this->client->request('get', $api_url);
            if ($respond->getStatusCode() === 200) {
                return json_decode($respond->getBody()->getContents(), true);
            } else {
                throw new \LogicException('请求失败');
            }
        } else {
            throw new \LogicException('refresh_token不能为空');
        }
    }


    /**
     * 网页授权，获取用户微信信息
     *
     * @param $openId
     * @return mixed
     */
    public function getOpenInfo($openId)
    {
        $access_token = $this->getAccessToken();

        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $access_token . '&openid=' . $openId . '&lang=zh_CN';
        $json = file_get_contents($url);

        $result = json_decode($json, true);

        return $result;
    }
}
