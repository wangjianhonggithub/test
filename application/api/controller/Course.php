<?php
namespace app\api\controller;

use app\common\model\Course as cmodel;

/**
 * 课程控制器
 * Class App
 * @package app\api\controller
 */
class Course extends Controller
{
    public function classlist(){
        // 获取课程分类
        $list = (new cmodel)->getclass_list(0,'',['ishow'=>1]);
        if ($list) {
            return ajax(200, '课程分类', $list);
        }
        return ajax(400);
    }
    
    // 课程列表
    public function courselist(){
        // 分类
        $cid = input('cid/d',0,'intval');
        if($cid == 0){
            $classlist = (new cmodel)->getclass_list(0, '', ['ishow' => 1]);
            $cid = $classlist[0]['id'];
            if($cid == 0){  return ajax(301,'分类不存在');  }
        }

        // 开课日期
        $datestamp = input('datestamp/s','','trim');
        if($datestamp == ''){
            $datestamp = date("Y-m-d");
        }
        $para = [
            'cid' => $cid,
            'datestamp' => $datestamp,
            'ishow' => 1 
        ];

        $fields = 'id,sid,cid,name,coid,price,complexity,target,datestamp,stime,etime,listbgimg,totnum,nownum,ispast,isfull';
        $list = (new cmodel)->getcourse_list(0, $fields, $para);
        
        if ($list) {
            return ajax(200, '课程列表', $list);
        }
        return ajax(400);
    }

    // 课程详情
    public function coursedetail(){
        // 检查登录
        switch ($this->checklogin()) {
            case 'notoken':
                echo echojson(-1);die;break;
            case 'nopenid':
                echo echojson(-1);die;break;
            case 'nouser':
                echo echojson(-1);die;break;
            case 'islock':
                //锁定
                echo echojson(801);die;break;
            default:
                # code...
                break;
        }
        
        $sid = input('sid/s','','trim');
        if($sid == ''){ return ajax(300,'课程编号为空');}

        $fields = 'id,sid,cid,name,coid,price,complexity,target,datestamp,stime,etime,listbgimg,insbgimg,maxnum,totnum,nownum,content1,content2,ishow,ispast,isfull,isdel';
        $comm = (new cmodel)->getcourse_detail($sid, $fields);
        if(empty($comm)){   return ajax(400,'空');  }

        // 验证
        if($comm['isdel'] == 2){    return ajax(301,'课程已被删除');}
        unset($comm['isdel']);
        
        if($comm['ishow'] == 2){    return ajax(301,'课程已被下架');}
        unset($comm['ishow']);

        $history = input('history/d',1,'intval');
        if($history == 1){
            // 过期满员
            if($comm['ispast'] == 2){    return ajax(301,'课程已过期');}
            unset($comm['ispast']);

            if($comm['isfull'] == 2){    return ajax(301,'课程已满员');}
            unset($comm['isfull']);
        }
        // 分类
        $comm['classname'] = db('courseclass')->where(['id' => $comm['cid']])->value('name');

        // 转换日期展示方式
        $comm['datestamp'] = date("Y-m-d", $comm['datestamp']);
        $comm['stime'] = substr($comm['stime'], 0, 5);
        $comm['etime'] = substr($comm['etime'], 0, 5);

        $weekarray = array("日", "一", "二", "三", "四", "五", "六");
        $comm['week'] = '星期' . $weekarray[date("w", $comm['datestamp'])];

        // 教练
        $comm['cocomm'] = db('coach')->where(['id' => $comm['coid']])->field('id,name,headpath,title1,title2,content')->find();

        // dump($comm);die;
        return ajax(200,$comm['sid'],$comm);
    }

}
