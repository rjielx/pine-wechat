<?php

namespace Pine\Wechat\Server;

trait WXCustomMenu
{
    /**
     * 菜单设置
     *
     * @param $data
     * @param string $data_type
     * @return mixed
     * @throws \Exception
     */
    public function setMenu($data, $data_type = 'array')
    {
        if (count($data) <= 0) {
            throw new \Exception('菜单不能为空');
        }
        $url = static::$uri . '/cgi-bin/menu/create?access_token=' . $this->getAccessToken();

        if ($data_type == 'array') {
            $data = $this->json_encode_unicode($data);
        }

        $respond = $this->client->request('post', $url, ['form_params' => $data]);
        if ($respond->getStatusCode() === 200) {
            $result = json_decode($respond->getBody()->getContents(), true);

            return $result;
        } else {
            throw new \Exception('请求失败');
        }
    }


    /**
     * 查询公众号菜单信息
     *
     * @return mixed
     * @throws \Exception
     */
    public function queryMenu()
    {
        $uri = static::$uri . '/cgi-bin/get_current_selfmenu_info?access_token=' . $this->getAccessToken();

        $respond = $this->client->request('get',$uri);
        if ($respond->getStatusCode() === 200) {
            $result = json_decode($respond->getBody()->getContents(), true);

            return $result;
        } else {
            throw new \Exception('请求失败');
        }
    }


    /**
     * 删除公众号菜单
     *
     * @Author RJie
     * @return mixed
     * @throws \Exception
     */
    public function deleteMenu()
    {
        $uri = static::$uri.'/cgi-bin/menu/delete?access_token='.$this->getAccessToken();

        $respond = $this->client->request('get',$uri);
        if ($respond->getStatusCode() === 200) {
            $result = json_decode($respond->getBody()->getContents(), true);

            return $result;
        } else {
            throw new \Exception('请求失败');
        }
    }
}
