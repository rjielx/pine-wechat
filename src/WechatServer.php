<?php
namespace Pine\Wechat;

use Pine\Wechat\Server\WXCustomMenu;
use Pine\Wechat\Server\WXInitToken;
use Pine\Wechat\Server\WXJsapiJSSDK;
use Pine\Wechat\Server\WXWebpage;

class WechatServer
{
    // 基础信息类
    use WXApiUrl,WXInitToken;

    // 应用类
    use WXWebpage,WXJsapiJSSDK,WXCustomMenu;
}

