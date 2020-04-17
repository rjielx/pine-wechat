### 使用说明

**安装**
```$xslt
composer require rjielx/pine-wechat
```

```
# 网页授权初始化
$appid 微信公众号ID
$appsecret 微信公众号密钥
$wechat = new WechatServer($appid,$appsecret);
```

```
# 微信消息配置(未完善)
define("TOKEN", "weixin");  令牌(Token)
$info = new WechatInfo();
$info->valid();
```
