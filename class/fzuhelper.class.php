<?php
/*
$fzuhelper = new fzuhelper();
$data = $fzuhelper->NewUsercode('1234fasdfasd5');
var_dump($data);
var_dump($fzuhelper->GetOpenId($data));
*/
class fzuhelper{
    private $DESKEY = 'F2U_h&1P';
    public function NewUsercode($openId){
        $code = rand(1, 99999999) . '|' . time(). '|' . $openId;
        $code = $code . '$' . substr(md5($code), 0, 8);
        return $this->EncryptDES($code);
    }
    public function GetOpenId($userCode){

        $temp = explode('$', $this->DecryptDES($userCode));
        if(@substr(md5($temp[0]), 0, 8) == @$temp[1]){
            $temp = explode('|', $temp[0]);
            if(time() - intval($temp[1]) < 600){
                return $temp[2];
            }else{
//                return -1;//过期
                return $temp[2];
            }
        }
        return -2;//已失败
    }
    private function EncryptDES($encryptString){
        //mcrypt_encrypt在 php 7 废除, 升级看起来要重写加密
        $encryptKey = $this->DESKEY;//密钥
        //PKCS5Padding填充开始
        $block = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_CBC);
        $padding = $block - (strlen($encryptString) % $block);
        $encryptString .= str_repeat(chr($padding),$padding);
        //PKCS5Padding填充结束
        return base64_encode(mcrypt_encrypt(MCRYPT_DES, $encryptKey, $encryptString, MCRYPT_MODE_CBC, $encryptKey));
    }
    private function DecryptDES($base64Data){
        //mcrypt_decrypt 在 php 7 废除, 升级看起来要重写加密
        $decryptKey = $this->DESKEY;//密钥
        $data = base64_decode($base64Data);
        $r = mcrypt_decrypt(MCRYPT_DES, $decryptKey, $data, MCRYPT_MODE_CBC, $decryptKey);
        return rtrim($r, substr($r ,-1));
    }

    private function Get($url, $header = false){//发送POST请求
        curl_setopt_array($this->ch, array(
            CURLOPT_URL => $url,
            CURLOPT_COOKIE => $this->sessionId != null ? 'ASP.NET_SessionId=' . $this->sessionId : '',
            CURLOPT_HEADER => $header,
            CURLOPT_POST => false));
        $result = curl_exec ($this->ch);
        $code = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        if ($code != "200"){
            return false;
        }
        return $result;
    }
    private function Post($url, $postArr, $referer = null,$header = false){//发送POST请求
        $postData = array();
        foreach($postArr as $key => $value){
            array_push($postData, $key . "=" . urlencode($value));
        }
        curl_setopt_array($this->ch, array(
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_COOKIE => $this->sessionId != null ? 'ASP.NET_SessionId=' . $this->sessionId : false,
            CURLOPT_HEADER => $header,
            CURLOPT_REFERER=> $referer,
            CURLOPT_POSTFIELDS => join($postData, "&")));
        //var_dump(join($postData, "&"));
        $result = curl_exec ($this->ch);
        $code = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        if ($code != "200"){
            return false;
        }
        return $result;
    }


    public function __construct($xh = '', $pwd = '')
    {
        date_default_timezone_set('PRC');
        $this->ch = curl_init();
        curl_setopt_array($this->ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT_MS => 1000,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; WOW64)',
            //CURLOPT_PROXY => 'http://127.0.0.1:8080',
        ));

    }
}
