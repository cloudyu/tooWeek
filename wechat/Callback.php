<?php
include_once '../config.php';
include_once '../class/wechat.class.php';
//include_once '../class/fzujiaowutong.class.php';
include_once '../class/fzuhelper.class.php';
$wechat = new wechat();
$fzuhelper = new fzuhelper();
if($wechat->loadMsg()){
    if($wechat->GetMsgType() == 'text'){

        if(strstr($wechat->GetMsgObj()->Content,'绑定')){
            $wechat->SentTextMsg('点击<a href="https://fzuhelper.cloudyu.me/bind.php?usercode='.urlencode($fzuhelper->NewUsercode($wechat->GetFromUserName())).'">这里</a>，进行账号绑定！');
            exit;
        }else if(strstr($wechat->GetMsgObj()->Content, '课表')){
            $wechat->SentTextMsg('点击<a href="https://fzuhelper.cloudyu.me/courses.php?usercode='.urlencode($fzuhelper->NewUsercode($wechat->GetFromUserName())).'">这里</a>，查看课表！');
            exit;

        }
    }
}

$wechat->SentTextMsg(base64_encode(@$GLOBALS['HTTP_RAW_POST_DATA']));

?>=