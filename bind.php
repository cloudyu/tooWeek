<?php
include_once 'config.php';
include_once 'class/wechat.class.php';
include_once 'class/fzujiaowutong.class.php';
include_once 'class/fzujiaowuchu.class.php';
include_once 'class/fzuhelper.class.php';
include_once 'class/sql.class.php';
if(isset($_GET['method'])){
    //var_dump($_SESSION['openId']);
    if(isset($_SESSION['openId']) && $_SESSION['openId']!=''){
        if($_GET['method'] == 'pw'){
            if(isset($_POST['xh']) && isset($_POST['password'])){
                $jwc = new jiaowuchu();
                if($jwc->Login($_POST['xh'], $_POST['password'])){
                    $sql = new sql();
                    $ssql = 'SELECT count(*) FROM `fzuhelper_user` WHERE openId != $0';
                    $r = $sql->Query($ssql, 'user', array($_SESSION['openId']));
                    if($row = $sql->FetchArray($r)){
                        if ($row == 0){
                            $ssql = 'INSERT INTO `__table__` (`openId`, `xh`, `password`, `token`) VALUES ($0, $1, $2, $3)';
                            $sql->Query($ssql, 'user', array($_SESSION['openId'], $_POST['xh'], $_POST['password'], ''));
                        }else{
                            $ssql = 'UPDATE `fzuhelper_user` SET 
`xh` = $0,`password` = $1, `token` = $2 WHERE `openId` = $3;';
                            $sql->Query($ssql, 'user', array($_POST['xh'], $_POST['password'], '', $_SESSION['openId']));
                        }
                    }
                    include_once 'template/bind.success.php';
                    exit;
                }else{
                    include_once 'template/bind.fail.php';
                    exit;
                }
            }
            include_once 'template/bind.pw.php';
            exit;
        }else if($_GET['method'] == 'token'){
            $jwt = new jiaowutong();
            if(isset($_POST['xh']) && $_POST['xh'] != '') {
                $token = $jwt->TraversalToken($_POST['xh'], '', 180);
                if($token){
                    $sql = new sql();
                    $ssql = 'SELECT count(*) FROM `fzuhelper_user` WHERE openId != $0';
                    $r = $sql->Query($ssql, 'user', array($_SESSION['openId']));
                    if($row = $sql->FetchArray($r)){
                        if ($row == 0){
                            $ssql = 'INSERT INTO `__table__` (`openId`, `xh`, `password`, `token`) VALUES ($0, $1, $2, $3)';
                            $sql->Query($ssql, 'user', array($_SESSION['openId'], '', '', $token));
                        }else{
                            $ssql = 'UPDATE `fzuhelper_user` SET `xh` = $0, `password` = $1, `token` = $2 WHERE `openId` = $3';
                            $sql->Query($ssql, 'user', array('', '', $token, $_SESSION['openId']));
                        }
                    }
                    include_once 'template/bind.success.php';
                    exit;
                }
            }
            include_once 'template/bind.token.php';
            exit;
        }
    }else{
        die('错误的请求');
    }


}
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
        include_once 'template/bind.main.php';
        $_SESSION['openId'] = $openId;

        //var_dump($openId);
    }
}





?>