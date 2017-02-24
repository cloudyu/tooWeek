<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>绑定账号——学号密码</title>
    <link href="//cdn.bootcss.com/weui/1.1.1/style/weui.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css"/>
    <style>
        body{
            padding:10px;
        }
    </style>
</head>
<body>
<h1>
    绑定账号
</h1>
<br />
<form class="weui-cells weui-cells_radio" method="post" action="bind.php?method=token">
    <div class="weui-cell">
        <div class="weui-cell__hd"><label class="weui-label">学号</label></div>
        <div class="weui-cell__bd">
            <input class="weui-input" type="number" name="xh" placeholder="请输入学号">
        </div>
    </div>

    <div class="weui-cell">
        <div class="weui-cell__hd"><label class="weui-label">绑定方法</label></div>
        <div class="weui-cell__bd">
            1、填写上方学号<br />
            2、打开福大教务通App<br />
            3、注销账号，并重新登录<br />
            4、点击下方绑定<br />
            注：登录成功后请尽快点击绑定。
        </div>
    </div>
    <div class="weui-btn-area">
        <input class="weui-btn weui-btn_primary" type="submit" value="绑定" />
    </div>

</form>

<div class="weui-footer">
    <br />
    <p class="weui-footer__links">
        <a href="https://cloudyu.me" class="weui-footer__link">CloudYu</a>
        <a href="javascript:void(0);" class="weui-footer__link">福大教务通<small>(仮)</small></a>
    </p>    <p class="weui-footer__text">Copyright © 2016 CloudYu.me</p>
</div>
<script src="//cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
<script src="//cdn.bootcss.com/jquery.nicescroll/3.6.8/jquery.nicescroll.min.js"></script>
<script src="//res.wx.qq.com/open/libs/weuijs/1.0.0/weui.min.js"></script>
<script src="js/script.js"></script>
<script type="application/javascript">

</script>
</body>
</html>