<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>课表查询</title>
    <link href="//cdn.bootcss.com/weui/1.1.1/style/weui.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css"/>
</head>
<body style="overflow-x: hidden;">
<a href="javascript:void;" class="weui-btn weui-btn_default" id="WeekPicker"></a>
<div class="course-show-wrapper" style="transform: translate3d(0%, 0%, 0px);">

</div>
<div class="weui-footer">
    <br />
    <p class="weui-footer__links">
        <a href="https://cloudyu.me" class="weui-footer__link">CloudYu</a>
        <a href="javascript:void(0);" class="weui-footer__link">福大教务通<small>(仮)</small></a>
    </p>
    <p class="weui-footer__text">Copyright © 2016 CloudYu.me</p>
</div>
<script src="//cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
<script src="//cdn.bootcss.com/jquery.nicescroll/3.6.8/jquery.nicescroll.min.js"></script>
<script src="//res.wx.qq.com/open/libs/weuijs/1.0.0/weui.min.js"></script>
<script src="js/script.js"></script>
<script src="//cdn.bootcss.com/fastclick/1.0.6/fastclick.js"></script>
<script type="text/javascript">
    var clientX = 0;
    var clientY = 0;
    var direction = 0;
    var windowWidth = $(window).width();
    var weekOffset = 0;
    <?php
    $jsonData = '';
    $jwc = new jiaowuchu();

    $jwc->login($row['xh'], $row['password']);
    $jsonData = json_encode(array(
        "xnxq"=> $jwc->Getxnxq(),
        "week"=> $jwc->Getweek(),
        "courses" =>$jwc->GetCourseSelect()));
    ?>
    var rowCourse = '<?php echo $jsonData;?>';
    $(function() {
        resize();
        newCourseTables(rowCourse);
        $(".course-show-wrapper").bind("touchstart",function(e){
            $(".msg").html('touchstart');
            clientX = e.originalEvent.changedTouches[0].clientX;
            clientY = e.originalEvent.changedTouches[0].clientY;
            direction = 0;
            //$("body").css("overflow", "auto");
            console.log('touchstart',clientX, clientY);
        });
        $(".course-show-wrapper").bind("touchmove",function(e){
            $(".msg").html('touchmove' + direction);
            var x = e.originalEvent.changedTouches[0].clientX;
            var y = e.originalEvent.changedTouches[0].clientY;
            if(direction == 0){
                if(x - clientX >= 20){
                    direction = 1;
                }else if(x - clientX <= -20){
                    direction = 2;
                }else if(y - clientY >= 20){
                    direction = -1;
                }else if(y - clientY <= -20){
                    direction = -2
                }
            }
            if(direction > 0){
                slideCourse(x - clientX);
            }
            console.log('touchmove', x - clientX, y - clientY, direction);
            if(direction > 0){
                return false;
            }
        });
        $(".course-show-wrapper").bind("touchend",function(e){
            $(".msg").html('touchend');
            var x = e.originalEvent.changedTouches[0].clientX;
            var y = e.originalEvent.changedTouches[0].clientY;
            direction = 0;
            //$("body").css("overflow", "auto");
            UpdateCourse(x - clientX)
            console.log('touchend', x - clientX, y - clientY);
        });
    });
</script>


</body>
</html>