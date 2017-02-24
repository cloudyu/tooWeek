<?php
/*
$jwc = new jiaowuchu('031603133', '260437');


/*
 * student/xkjg/wdxk/xkjg_list.aspx 我的选课
 * student/xyzk/cjyl/score_sheet.aspx 成绩
 *
 *
 *
 *
 */
class jiaowuchu{
    private $BaseURL = 'http://59.77.226.35/';
    private $LoginURL = 'http://59.77.226.32/';
    private $sessionId = null, $urlId = '';
    private $ch;
    private $xnxq = '', $week = '';
    public function GetExamList(){//获取考表
        if($this->sessionId == '' || $this->urlId == '') {
            //return false;
        }
        $url = $this->BaseURL . 'student/xkjg/examination/exam_list.aspx?id=' . $this->urlId;
        $data = $this->Get($url);
        var_dump($this->FormatExamListData($data));
        //echo $data;
    }

    public function Getxnxq(){
        if($this->xnxq != ''){
            return $this->xnxq;
        }
        $url = $this->LoginURL . 'tt.asp';
        $data = $this->Get($url);
        $data = mb_convert_encoding($data, 'utf-8', 'gbk');
        //var_dump($data);
        if(preg_match('{(\d+)学年(\d+)学期 第.*?(\d+)</font>周 </b>}', $data, $match)){
            $this->xnxq = $match[1].$match[2];
            $this->week = $match[3];
        }
        return $this->xnxq;
    }
    public function Getweek(){
        if($this->week != ''){
            return $this->week;
        }
        $url = $this->LoginURL . 'tt.asp';
        $data = $this->Get($url);
        $data = mb_convert_encoding($data, 'utf-8', 'gbk');
        //var_dump($data);
        if(preg_match('{(\d+)学年(\d+)学期 第.*?(\d+)</font>周 </b>}', $data, $match)){
            $this->xnxq = $match[1].$match[2];
            $this->week = $match[3];
        }
        return $this->week;
    }

    private function FormatExamListData($data){
        $data = preg_replace('/(<[^aA][a-zA-Z]*)[^>]*>/', '$1>', $data);//只剩下标签 a标签有数据 不能清空
        $data = str_replace( "\t", '', $data);
        $data = str_replace( " ", '', $data);
        $data = str_replace( "&nbsp;", ' ', $data);
        $data = str_replace( "\n", '', $data);
        $data = str_replace( "\r", '', $data);

        $pattern = '{<tr><td>([^<]*)</td><td>([^<]*)</td><td>([^<]*)</td><td>([^<]*)</td><td>([^<]*)</td></tr>}';
        preg_match_all($pattern, $data, $matchs);
        return $matchs;
    }

    public function GetCourseScore(){//获取成绩
        if($this->sessionId == '' || $this->urlId == '') {
            //return false;
        }
        $url = $this->BaseURL . 'student/xyzk/cjyl/score_sheet.aspx?id=' . $this->urlId;
        $data = $this->Get($url);
        var_dump($this->FormatCourseScoreData($data));
        echo $data;
    }
    private function FormatCourseScoreData($data){
        $data = preg_replace('/(<[^aA][a-zA-Z]*)[^>]*>/', '$1>', $data);//只剩下标签 a标签有数据 不能清空
        $data = str_replace( "\t", '', $data);
        $data = str_replace( " ", '', $data);
        $data = str_replace( "&nbsp;", ' ', $data);
        $data = str_replace( "\n", '', $data);
        $data = str_replace( "\r", '', $data);
        $pattern = '{<tr><td>([^<]*)</td><td>([^<]*)</td><td>([^<]*)</td><td>([^<]*)</td>'.
            '<td>[<font>]{0,6}([^<]*)[</font>]{0,7}</td><td>([^<]*)</td><td>([^<]*)</td>'.
            '<td>([^<]*)</td><td>([^<]*)</td><td>([^<]*)</td><td>([^/]*)</td>'.
            '<td>([^/]*)</td></tr>}';
        preg_match_all($pattern, $data, $matchs);
        return $matchs;
    }

    public function GetCourseSelect(){//获取课表
        if($this->sessionId == '' || $this->urlId == '') {
            //return false;
        }
        $url = $this->BaseURL . 'student/xkjg/wdxk/xkjg_list.aspx?id=' . $this->urlId;
        $data = $this->Get($url);


        //$data = file_get_contents('./data');



        $dataArr = $this->FormatCourseData($data);
        //var_dump($dataArr);
        $dataReturn = array();
        foreach($dataArr[2] as $key=>$value){
            $skxx = array();
            $tempArr = explode('<br>', $dataArr[10][$key]);
            foreach($tempArr as $temp){
                //var_dump($temp);
                if(preg_match('{(\d{1,2})-(\d{1,2}) 星期(\d):(\d{1,2})-(\d{1,2})节([^ ]*) ([^ ]*)}',
                    $temp, $tempMatch)){
                    array_push($skxx,  array(
                        'sWeek' => intval($tempMatch[1]),
                        'eWeek' => intval($tempMatch[2]),
                        'xq' => intval($tempMatch[3]),//星期
                        'sJie' => intval($tempMatch[4]),
                        'eJie' => intval($tempMatch[5]),
                        'danShuang' => str_replace(')', '', str_replace('(', '', $tempMatch[6])),
                        'js' => $tempMatch[7]
                    ));
                }
            }
            if(preg_match('{(\d+)年(\d+)月(\d+)日<br>(\d+):(\d+)-(\d+):(\d+)<br>(.*)}',
                $dataArr[11][$key], $tempMatch)){
                $ksxx= array(
                    'y' => intval($tempMatch[1]),
                    'm' => intval($tempMatch[2]),
                    'd' => intval($tempMatch[3]),
                    'sH' => intval($tempMatch[4]),
                    'sM' => intval($tempMatch[5]),
                    'eH' => intval($tempMatch[6]),
                    'eM' => intval($tempMatch[7]),
                    'js' => $tempMatch[8]
                );
            }else{
                $ksxx = '';
            }
            if(preg_match('{[^\d]*([\d]*)}', $dataArr[3][$key], $tempMatch)){
                $kcdm = $tempMatch[1];
            }else{
                $kcdm = '';
            }
            if(preg_match('{[^\d]*([\d]*)}', $dataArr[4][$key], $tempMatch)){
                $kkhm = $tempMatch[1];
            }else{
                $kkhm = '';
            }
            array_push($dataReturn, array(
                    'kcmc' => $value,//课程名称
                    'kcdm' => $kcdm,//教学大纲
                    'kkhm' => $kkhm,//授课计划
                    'xf' => $dataArr[6][$key],//学分
                    'xxlx' => $dataArr[7][$key],//选修类型
                    'kslb' => $dataArr[8][$key],//考试类别
                    'rkjs' => $dataArr[9][$key],//任课教师
                    'skxx' => $skxx,//上课信息
                    'ksxx' => $ksxx,//考试信息
                    'bz' => $dataArr[12][$key],///备注
                    'tkxx' => $dataArr[13][$key]//调课信息
                ));
        }
        return $dataReturn;
    }
    private function FormatCourseData($data){
        $data = preg_replace('/(<[^aA][a-zA-Z]*)[^>]*>/', '$1>', $data);//只剩下标签 a标签有数据 不能清空
        $data = str_replace( "\t", '', $data);
        $data = str_replace( " ", '', $data);
        $data = str_replace( "&nbsp;", ' ', $data);
        $data = str_replace( "\n", '', $data);
        $data = str_replace( "\r", '', $data);
        $pattern = '{<tr><td>([^<]*)</td><td>([^<]*)</td><td><ahref=javascript:pop1\(\'([^<]*)&id=\d+\'\);>教学大纲</a><br>'.
            '<ahref=javascript:pop1\(\'([^<]*)&id=\d+\'\);>授课计划</a></td><td>([^<]*)</td><td><span>([^<]*)</span></td>'.
            '<td><span>([^<]*)</span></td><td><span>([^<]*)</span></td><td>([^<]*)</td><td>([^/]*)<br></td><td>([^/]*)</td>'.
            '<td>([^<]*)</td><td>([^<]*)</td></tr>}';
        preg_match_all($pattern, $data, $matchs);
        return $matchs;
    }
    public function Login($xh, $pwd){
        $url = $this->LoginURL . 'logincheck.asp';
        $r = $this->Post($url, array('muser'=> $xh,
            'passwd' => $pwd,
            'x' => rand(30, 40),
            'y' => rand(20, 30)), 'http://jwch.fzu.edu.cn/', true);
        //var_dump($r);
        if(strstr($r, '密码错误')){
            return false;
        }
        if(preg_match('/ASP.NET_SessionId=([^;]*)/', $r, $match)){
            $this->sessionId = $match[1];
        }else{
            return false;
        }
        if(preg_match('/top\.aspx\?id=(\d+)/', $r, $match)){
            $this->urlId = $match[1];
        }else{
            return false;
        }
        return true;
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




    public function __construct()
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
