<?php
namespace Pine\Wechat;

use Pine\Wechat\Server\WXInitToken;
use Pine\Wechat\Server\WXJsapiJSSDK;
use Pine\Wechat\Server\WXWebpage;

class WechatServer
{
    use WXInitToken,WXWebpage,WXJsapiJSSDK;
}

