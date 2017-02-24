
<?php
class wechat
{
    private $msgObj;
    private $ch = null, $access_token = null;
    public function __construct()
    {
        if(CY_DEBUG){
            return true;
        }

        if(!$this->checkSignature()){//不是微信请求就退出
            //var_dump($_GET);
            //die('非法请求 - 0');
        }
        if(isset($_GET['valid'])){//判断是否是微信认证
            $this->Valid();
        }
        if(!$this->loadMsg()){//加载xml信息 如果加载失败就退出
            die('非法请求 - 1');//加载失败
        }
        if($this->msgObj->ToUserName != WECHAT_ID){
            die('非法请求 - 2');//不是发送给本人的
        }
        if(!$this->GetAccess_token()){
            die('程序错误 - 0');//Get A ccess_token 失败
        }
    }

    public function DownloadTempSource($media_id){
        //垃圾微信 权限超低, 无法调用, 不写了
        return false;
    }
    public function AddTempSource($type, $data){
        if(!($type == 'image' || $type =='voice' ||
            $type =='video' || $type =='thumb')){
            return false;
        }
        $url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token=' . $this->GetAccess_token() . '&type=' . $type;
        //垃圾微信 权限超低, 无法调用, 不写了
        return false;
    }
    public function GetMsgType(){
        //text, image, voice, video, shortvideo, location, link
        return $this->msgObj->MsgType;
    }
    public function GetAccess_token ($force = false){
        Global $memcache;
        if(!$force && $this->access_token != null){
            return $this->access_token;
        }
        if(!$force && $access_token = $memcache->get('WECHAT_access_token')){
            $this->access_token = $access_token;
            return $access_token;
        }
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . WECHAT_AppID .
            '&secret=' . WECHAT_AppSecret;
        $r = $this->Get($url);
        if($jsonArr = json_decode($r, true)){
            if(isset($jsonArr['access_token']) &&  isset($jsonArr['expires_in'])){
                //$memcache->delete('WECHAT_access_token');
                $memcache->set('WECHAT_access_token', strval($jsonArr['access_token']), MEMCACHE_COMPRESSED,
                    intval($jsonArr['expires_in']) - 1800);
                $this->access_token = $jsonArr['access_token'];
                return $jsonArr['access_token'];
            }
        }
        return false;
    }

    public function SentNewsMsg($itemArr){
        $xmlTpl = '<xml> 
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>%s</ArticleCount>
<Articles>
';
        $itemTpl = '<item>
<Title><![CDATA[%s]]></Title> 
<Description><![CDATA[%s]]></Description>
<PicUrl><![CDATA[%s]]></PicUrl>
<Url><![CDATA[%s]]></Url>
</item>
';
        $resultStr = sprintf($xmlTpl, $this->msgObj->FromUserName, $this->msgObj->ToUserName, time(), count($itemArr));
        foreach ($itemArr as  $value){
            $item = sprintf($itemTpl, $value['title'], $value['description'], $value['picUrl'], $value['url']);
            $resultStr .= $item;
        }
        $resultStr .= '</Articles>
</xml>';
        echo $resultStr;
    }
    public function SentMusicMsg($media_id, $title = '', $description = '', $music_url='', $hq_music_url=''){
        $xmlTpl = '<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[music]]></MsgType>
<Music>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<MusicUrl><![CDATA[%s]]></MusicUrl>
<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
</Music>
</xml>';
        $resultStr = sprintf($xmlTpl, $this->msgObj->FromUserName, $this->msgObj->ToUserName, time(),
            $title, $description, $music_url, $hq_music_url, $media_id);
        echo $resultStr;
    }
    public function SentVideoMsg($media_id, $title = '', $description = ''){
        $xmlTpl = '<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[video]]></MsgType>
<Video>
<MediaId><![CDATA[%s]]></MediaId>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
</Video> 
</xml>';
        $resultStr = sprintf($xmlTpl, $this->msgObj->FromUserName, $this->msgObj->ToUserName, time(), $media_id,
            $title, $description);
        echo $resultStr;
    }
    public function SentVoiceMsg($media_id){
        $xmlTpl = '<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[voice]]></MsgType>
<Voice>
<MediaId><![CDATA[%s]]></MediaId>
</Voice>
</xml>';
        $resultStr = sprintf($xmlTpl, $this->msgObj->FromUserName, $this->msgObj->ToUserName, time(), $media_id);
        echo $resultStr;
    }
    public function SentImageMsg($media_id){
        $xmlTpl = '<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[image]]></MsgType>
<Image>
<MediaId><![CDATA[%s]]></MediaId>
</Image>
</xml>';
        $resultStr = sprintf($xmlTpl, $this->msgObj->FromUserName, $this->msgObj->ToUserName, time(), $media_id);
        echo $resultStr;
    }
    public function SentTextMsg($text){
        $xmlTpl = '<xml> 
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>';
        $resultStr = sprintf($xmlTpl, $this->msgObj->FromUserName, $this->msgObj->ToUserName, time(), $text);
        echo $resultStr;
    }
    public function GetMsgObj(){
        return $this->msgObj;
    }
    public function GetFromUserName(){
        return $this->msgObj->FromUserName;
    }

    public function LoadMsg(){
        $msgStr = @$GLOBALS['HTTP_RAW_POST_DATA'];
/*
        $msgStr ='<xml><ToUserName><![CDATA[gh_9634df8f5354]]></ToUserName>
<FromUserName><![CDATA[ombRvv6h8s7nXtjPXxF4gVjwD6r4]]></FromUserName>
<CreateTime>1487162752</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[绑定]]></Content>
<MsgId>6387315384109857595</MsgId>
</xml>';
*/
        if (!empty($msgStr)) {
            $this->msgObj = simplexml_load_string($msgStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            //$toUsername = $postObj->ToUserName;
            if ($this->msgObj) {
                return true;
            }
        }
        return false;
    }
    private function Valid()
    {
        $echoStr = @$_GET['echostr'];
        if($this->CheckSignature()){
            header('content-type:text');
            echo $echoStr;
            exit;
        }
    }
    private function CheckSignature()
    {
        $signature = @$_GET['signature'];
        $timestamp = @$_GET['timestamp'];
        $nonce = @$_GET['nonce'];
        $tmpArr = array(WECHAT_Token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    private function Get($url){//发送POST请求
        if($this->ch == null){
            $this->NewCurl();
        }
        curl_setopt_array($this->ch, array(
            CURLOPT_URL => $url,
            CURLOPT_POST => false
        ));
        $result = curl_exec ($this->ch);
        $code = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        if ($code != "200"){
            return false;
        }
        return $result;
    }

    private function Post($url, $postArr){//发送POST请求
        if($this->ch == null){
            $this->NewCurl();
        }
        $postData = array();
        foreach($postArr as $key => $value){
            array_push($postData, $key . "=" . urlencode($value));
        }
        curl_setopt_array($this->ch, array(
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => join($postData, "&")));

        $result = curl_exec ($this->ch);
        $code = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        if ($code != "200"){
            return false;
        }
        return $result;
    }

    private function NewCurl(){
        $this->ch = curl_init();
        curl_setopt_array($this->ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_CONNECTTIMEOUT_MS => 500,
            CURLOPT_USERAGENT => ''
        ));

    }

}

