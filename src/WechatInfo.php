<?php
namespace Pine\Wechat;

class WechatInfo
{
    /**
     * 发送用户的openID
     * @var
     */
    protected $FromUserName;

    /**
     * 接收用户的openID
     * @var
     */
    protected $ToUserName;

    /**
     * 发送的消息
     * @var
     */
    protected $Content;

    /**
     * 消息类型
     * @var
     */
    protected $MsgType;


    public function __construct()
    {
        $echoStr = $_GET["echostr"];
        if (isset($echoStr)) {
            if($this->checkSignature())
            {
                echo $echoStr;
                exit;
            }
        }
    }

    /**
     * 微信自动消息回复入口
     *
     * @throws \Exception
     */
    public function valid()
    {
        $this->responseMsg();
    }

    /**
     * 微信自动消息接口验证
     *
     * @return bool
     * @throws \Exception
     */
    private function checkSignature()
    {
        if (!defined("TOKEN")) {
            throw new \Exception('TOKEN is not defined!');
        }
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @return bool
     */
    public function responseMsg()
    {
        //获取触发事件
        $postStr = file_get_contents('php://input');

        if (!empty($postStr)) {
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this->FromUserName = $postObj->FromUserName; //发送用户的openID
            $this->ToUserName = $postObj->ToUserName;     //接收用户的openID
            $this->Content = trim($postObj->Content);     //发送的消息
            $this->MsgType = $postObj->MsgType; //消息类型
            $event = $postObj->Event;//事件类型，subscribe（订阅）、unsubscribe（取消订阅）

            switch ($this->MsgType){
                case 'event':
                    switch ($event){
                        case 'subscribe':   //关注事件

                            $content = '欢迎关注';

                            echo $this->transmitText($content);
                            break;
                        case 'unsubscribe':     //取消关注事件

                            break;
                        case 'CLICK':   //菜单点击事件
                            switch ($postObj->EventKey){
                                case 'V1001_GROUP':
                                    $content = '点击事件';
                                    echo $this->transmitText($content);
                                    break;
                            }
                            break;
                        default:
                            $content = $event . ' / '. $this->MsgType .'/' .$postObj->EventKey;
                            echo $this->transmitText($content);
                            break;
                    }
                    break;
                case 'text':
                    $content = '稍等片刻，即将问您解答！';
                    echo $this->transmitText($content);
                    break;
                case 'image':
                    $MediaId = $postObj->MediaId;
                    echo $this->transmitImage($MediaId);
                    break;
                default:
                    $content = $this->MsgType;
                    echo $this->transmitText($content);
                    break;
            }
        }else{
            echo '没有触发事件';
            return false;
        }
    }

    /**
     * 文本消息回复
     * @param $content
     * @param int $flag
     * @return string
     */
    private function transmitText($content, $flag = 0)
    {
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[text]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>%d</FuncFlag>
                    </xml>";
        $resultStr = sprintf($textTpl, $this->FromUserName, $this->ToUserName, time(), $content, $flag);
        return $resultStr;
    }

    /**
     * 图片消息回复
     * @param $MediaId
     * @return string
     */
    public function transmitImage($MediaId)
    {
        $imageTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[image]]></MsgType>
                        <Image>
                            <MediaId><![CDATA[%s]]></MediaId>
                        </Image>
                     </xml>";
        $resultStr = sprintf($imageTpl, $this->FromUserName, $this->ToUserName, time(), $MediaId);
        return $resultStr;
    }
}
