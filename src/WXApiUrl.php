<?php
namespace Pine\Wechat;

trait WXApiUrl
{
    /**
     * 通用域名
     * 使用该域名将访问官方指定就近的接入点
     * @var string
     */
    public static $uri = 'https://api.weixin.qq.com';

    /**
     * 通用异地容灾域名
     * 当上述域名不可访问时可改访问此域名
     * @var string
     */
    public static $uri2 = 'https://api2.weixin.qq.com';

    /**
     * 上海域名
     * 使用该域名将访问上海的接入点
     * @var string
     */
    public static $sh_uri = 'https://sh.api.weixin.qq.com';

    /**
     * 深圳域名
     * 使用该域名将访问深圳的接入点
     * @var string
     */
    public static $sz_uri = 'https://sz.api.weixin.qq.com';

    /**
     * 香港域名
     * 使用该域名将访问香港的接入点
     * @var string
     */
    public static $hk_uri = 'https://hk.api.weixin.qq.com';


}
