$(function() {
    $("body").niceScroll({cursorcolor:"#ccc"});
    $.fn.transform = function(transform) {
        for (var i = 0; i < this.length; i++) {
            var elStyle = this[i].style;
            elStyle.webkitTransform = elStyle.MsTransform = elStyle.msTransform = elStyle.MozTransform = elStyle.OTransform = elStyle.transform = transform;
        }
        return this;
    };
});

function resize(){
    $('.course-table').width(windowWidth);
    $('.course-show-wrapper').width(windowWidth);
}
function slideCourse(length){
    var o = length / windowWidth * 100;
    if(o > 100) o = 100;
    if(o < -100) o = -100;
    $('.course-show-wrapper').transform("translate3d(" + (weekOffset * 100 + o) + "%, 0%, 0px)");
}
function UpdateCourse(length){
    var o = length / windowWidth * 100;
    if(Math.abs(o) <= 40){
        $('.course-show-wrapper').transform("translate3d(" + (weekOffset * 100) + "%, 0%, 0px)");
        return false;
    }
    var jsonArr = JSON.parse(rowCourse);
    var firstMonday = new Date();
    firstMonday = new Date(firstMonday.getFullYear(), firstMonday.getMonth(), firstMonday.getDate());
    firstMonday.setDate(firstMonday.getDate() - firstMonday.getDay() - jsonArr.week * 7 + 8);

    if(o < 0){
        ++weekOffset;
        $('.course-show-prev').remove();
        $('.course-show-current').attr('class', 'course-show-prev');
        $('.course-show-next').attr('class', 'course-show-current');
        var html = newCourseTable({
            'classType':'next',
            'firstMonday': firstMonday,
            'week' : parseInt(jsonArr.week) + weekOffset,
            'xnxq' : jsonArr.xnxq,
            'courses' : jsonArr.courses,
            'offset' : weekOffset
        });
        $('#WeekPicker').text('第' + (parseInt(jsonArr.week) + weekOffset) + '周');
        $(".course-show-wrapper").append(html);
    }else{
        --weekOffset;
        $('.course-show-next').remove();
        $('.course-show-current').attr('class', 'course-show-next');
        $('.course-show-prev').attr('class', 'course-show-current');
        var html = newCourseTable({
            'classType':'prev',
            'firstMonday': firstMonday,
            'week' : parseInt(jsonArr.week) + weekOffset,
            'xnxq' : jsonArr.xnxq,
            'courses' : jsonArr.courses,
            'offset' : weekOffset
        });
        $('#WeekPicker').text('第' + (parseInt(jsonArr.week) + weekOffset) + '周');
        $(".course-show-wrapper").prepend(html);

    }
    console.log(weekOffset);
    $('.course-show-wrapper').transform("translate3d(" + (-weekOffset * 100) + "%, 0%, 0px)");
}
function newCourseTables(jsonData) {
    var jsonArr = JSON.parse(jsonData);
    $('#WeekPicker').text('第' + (jsonArr.week) + '周');
    var firstMonday = new Date();
    firstMonday = new Date(firstMonday.getFullYear(), firstMonday.getMonth(), firstMonday.getDate());
    firstMonday.setDate(firstMonday.getDate() - firstMonday.getDay() - jsonArr.week * 7 + 8);
    console.log(firstMonday);
    var html = newCourseTable({
        'classType':'prev',
        'firstMonday': firstMonday,
        'week' : parseInt(jsonArr.week)-1,
        'xnxq' : jsonArr.xnxq,
        'courses' : jsonArr.courses,
        'offset' : 0
    });
    firstMonday = new Date();
    firstMonday = new Date(firstMonday.getFullYear(), firstMonday.getMonth(), firstMonday.getDate());
    firstMonday.setDate(firstMonday.getDate() - firstMonday.getDay() - jsonArr.week * 7 + 8);
    html += newCourseTable({
        'classType':'current',
        'firstMonday': firstMonday,
        'week' : parseInt(jsonArr.week),
        'xnxq' : jsonArr.xnxq,
        'courses' : jsonArr.courses,
        'offset' : 0
    });
    firstMonday = new Date();
    firstMonday = new Date(firstMonday.getFullYear(), firstMonday.getMonth(), firstMonday.getDate());
    firstMonday.setDate(firstMonday.getDate() - firstMonday.getDay() - jsonArr.week * 7 + 8);
    html += newCourseTable({
        'classType':'next',
        'firstMonday': firstMonday,
        'week' : parseInt(jsonArr.week)+1,
        'xnxq' : jsonArr.xnxq,
        'courses' : jsonArr.courses,
        'offset' : 0
    });
    $(".course-show-wrapper").append(html);
    //console.log(html)
}

function newCourseTable(data){
    /*
     {
     xnxq : 第几学期
     classType: 第几类
     firstMonday: 第一天时间戳
     week: 本周第几周
     courses: 课表
     offset: 偏移
     }
     */
    console.log(data);

    var today = new Date();
    today = new Date(today.getFullYear(), today.getMonth(), today.getDate());
    //today = new Date('2016-9-28');

    var tempDay = data.firstMonday;
    var StartHtml = '<div class="course-show-'+data.classType+'" style="transform: translate3d(';
    if(data.classType == 'prev'){
        StartHtml += ((data.offset - 1) * 100) + '%';
    }else if(data.classType == 'current'){
        StartHtml += ((data.offset) * 100) + '%';
    }else{
        StartHtml += ((data.offset + 1) * 100) + '%';
    }
    //data.week += data.offset;
    tempDay.setDate(tempDay.getDate() + 7 * data.week - 7);
    StartHtml += ', 0%, 0px);">\n<table class="course-table">\n';

    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    var titleHtml = '<tr>';
    titleHtml += '<th' + specialDay(today, tempDay) + '>一<br /><span>' + tempDay.getDate() + '</span></th>\n';
    tempDay.setDate(tempDay.getDate() + 1);
    titleHtml += '<th' + specialDay(today, tempDay) + '>二<br /><span>' + tempDay.getDate() + '</span></th>\n';
    tempDay.setDate(tempDay.getDate() + 1);
    titleHtml += '<th' + specialDay(today, tempDay) + '>三<br /><span>' + tempDay.getDate() + '</span></th>\n';
    tempDay.setDate(tempDay.getDate() + 1);
    titleHtml += '<th' + specialDay(today, tempDay) + '>四<br /><span>' + tempDay.getDate() + '</span></th>\n';
    tempDay.setDate(tempDay.getDate() + 1);
    titleHtml += '<th' + specialDay(today, tempDay) + '>五<br /><span>' + tempDay.getDate() + '</span></th>\n';
    tempDay.setDate(tempDay.getDate() + 1);
    titleHtml += '<th' + specialDay(today, tempDay) + '>六<br /><span>' + tempDay.getDate() + '</span></th>\n';
    tempDay.setDate(tempDay.getDate() + 1);
    titleHtml += '<th' + specialDay(today, tempDay) + '>日<br /><span>' + tempDay.getDate() + '</span></th>\n';
    titleHtml += '</tr>';
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    var bodyHtml = '';
    var i_jie = 0;
    while(++i_jie <= 12){//每一行
        var i_xq = 0
        var rowHtml = '<tr>\n';
        while (++i_xq <= 7){//一星期每一天
            var tempCourse = new Array();
            var i_kc = 0;
            var courseLen = data.courses.length;
            for(var i_kc = 0; i_kc < courseLen; ++i_kc){
                var skxx = data.courses[i_kc].skxx;
                //console.log(skxx);
                for(var i_skxx = 0; i_skxx < skxx.length; ++i_skxx){
                    var course = skxx[i_skxx];
                    if(course.xq != i_xq) continue;
                    if(data.week < course.sWeek || course.eWeek < data.week) continue;
                    if(i_jie < course.sJie || course.eJie < i_jie) continue;
                    if(course.danShuang == '单' && data.week % 2 == 0) continue;
                    if(course.danShuang == '双' && data.week % 2 == 1) continue;
                    var temp = data.courses[i_kc];
                    temp.kcNum = i_kc;
                    temp.kcSel = i_skxx;
                    tempCourse.push(temp);
                }
            }
            if(tempCourse.length == 1){
                var td = tempCourse[0];
                //console.log(td);
                var course = td.skxx[td.kcSel];
                if(course.sJie == i_jie) {//第一节
                    rowHtml += '<td class="course-color-' + td.kcNum + '"';
                    rowHtml += ' rowspan=' + (course.eJie - course.sJie + 1);
                    rowHtml += ' data-week="' + data.week + '" data-xnxq="' + data.xnxq  +
                        '" data-jie="' + i_jie + '" data-kcmc="' + td.kcmc + '" data-kcdm="' + td.kcdm +
                        '" data-kkhm="' + td.kkhm + '" data-xf="' + td.xf + '" data-xxlx="' + td.xxlx +
                        '" data-kslb="' + td.kslb + '" data-rkjs="' + td.rkjs + '" data-bz="' + td.bz +
                        '" data-tkxx="' + td.tkxx + '" data-sWeek="' + course.sWeek + '" data-eWeek="' + course.eWeek +
                        '" data-sJie="' + course.sJie +'" data-eJie="' + course.eJie +'" data-js="' + course.js +
                        '" data-danShuang="' + course.danShuang +'">';
                    //一堆数据 我也很无奈
                    rowHtml += '<span>' + td.kcmc + '</span>';
                    rowHtml += '<span>' + course.js + '</span>';
                    rowHtml += '</td>';
                }else {
                    rowHtml += '<td class="hidden">&nbsp;</td>';
                }
            } else if(tempCourse.length > 1){
                rowHtml += '<td>同时出现2门课程. 暂时无法处理</td>';
                //这个情况是同时出现2门课
                //暂时不管 之后处理
            }else{
                rowHtml += '<td>&nbsp;</td>';
            }
            rowHtml += '\n';
        }
        rowHtml += '</tr>\n';
        bodyHtml += rowHtml;
    }
    var endHtml = '</table>\n</div>';
    return StartHtml + titleHtml + bodyHtml + endHtml;
}
function specialDay(today, tempDay) {
    if(today.getTime() == tempDay.getTime()){
        return ' class="today"'
    }
    return '';
}

