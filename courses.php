<?php
include_once 'config.php';
include_once 'class/wechat.class.php';
include_once 'class/fzujiaowutong.class.php';
include_once 'class/fzujiaowuchu.class.php';
include_once 'class/fzuhelper.class.php';
include_once 'class/sql.class.php';
if (isset($_GET['usercode'])){
    $fzuhelper = new fzuhelper();
    $openId = $fzuhelper->GetOpenId($_GET['usercode']);
    if($openId == -1){
        echo '验证已过期，请重新获取！';
        exit;
    }else if($openId == -2){
        echo '数据有误！';
        exit;
    }else{
        $_SESSION['openId'] = $openId;
        //var_dump($openId);
    }
}
if(isset($_SESSION['openId'])){
    $sql = new sql();
    $ssql = 'SELECT * FROM `fzuhelper_user` WHERE openId = $0';
    $r = $sql->Query($ssql, 'user', array($_SESSION['openId']));
    if($row = $sql->FetchArray($r)) {
        if ($row['token'] != ''){
            die('通过token获取课表方式还没写');
        }else{
            include_once 'template/courses.main.php';
        }
    }else{
        die('您还没有绑定, 微信输入 "绑定" 进行绑定账号');
    }
}






?>