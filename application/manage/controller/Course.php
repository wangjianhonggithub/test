<?php
namespace app\manage\controller;

use app\common\model\Course as cmodel;
/**
 * 课程管理 
 */
class Course extends Controller{
    public function __construct(){
        parent::__construct();

        $this->para = '';
        $cid = input('cid/d', 0, 'intval');
        if ($cid) {     $this->para['cid'] = $cid;}

        $coid = input('coid/d', 0, 'intval');
        if ($coid) {    $this->para['coid'] = $coid;    }

        $state = input('state/d', 0, 'intval');
        if ($state) {   $this->para['state'] = $state;  }

        $sid = input('sid/s', '', 'trim');
        if ($sid) {     $this->para['sid'] = $sid;  }

        $name = input('name/s', '', 'trim');
        if ($name) {    $this->para['name'] = $name;    }

        $datestamp = input('datestamp/s', '', 'trim');
        if ($datestamp) {   $this->para['datestamp'] = $datestamp;  }

        $this->assign('para', $this->para);
    }

    // 课程列表
    public function index(){
        // 获取课程分类
        $classlist = (new cmodel)->getclass_list(0);
        $this->assign('classlist', $classlist);
        // 教练列表
        $coachlist = (new cmodel)->getcoach_list(0);
        $this->assign('coachlist', $coachlist);

        $page = input('page/d', 1, 'intval');
        $data = (new cmodel)->getcourse_list($page, url('index',$this->para), $this->para);

        // dump($data);die;

        return $this->fetch('courselist', compact('data'));
    }

    // 历史课程列表
    public function hislist(){
        // 获取课程分类
        $classlist = (new cmodel)->getclass_list(0);
        $this->assign('classlist', $classlist);
        // 教练列表
        $coachlist = (new cmodel)->getcoach_list(0);
        $this->assign('coachlist', $coachlist);

        $this->para['history'] = 1;
        $this->assign('history',1);
        
        $page = input('page/d', 1, 'intval');
        $data = (new cmodel)->getcourse_list($page, url('index', $this->para), $this->para);

        return $this->fetch('courselist', compact('data'));
    }

    // 新增/编辑
    public function courserun(){
        $id = input('id/d', 0, 'intval');
        $cmodel = new cmodel;
        if (!$this->request->isAjax()) {
            if ($id) {
                $comm = $cmodel->getcourse_one($id);
                $this->assign('comm', $comm);
            }
            // 获取课程分类
            $classlist = (new cmodel)->getclass_list(0);
            $this->assign('classlist', $classlist);
            // 教练列表
            $coachlist = (new cmodel)->getcoach_list(0);
            $this->assign('coachlist', $coachlist);

            return $this->fetch();
        }

        if ($id ? $cmodel->modcourse($id) : $cmodel->addcourse()) {
            return $this->renderSuccess('更新成功', url('index'));
        }
        $error = $cmodel->getError() ?: '更新失败';
        return $this->renderError($error);
    }

    // 排序
    public function coursesort(){
        $id = input('id/d', 0, 'intval');
        return $this->sort_change($id, 'course');
    }
    // 展示隐藏
    public function courseshow(){
        $id = input('id/d', 0, 'intval');
        return $this->ishow_change($id, 'course');
    }
    // 删除
    public function coursedel(){
        $id = input('id/d', 0, 'intval');
        return $this->isdel_change($id, 'course');
    }


    // 课程分类列表
    public function classlist(){
        $page = input('page/d',1,'intval');
        $data = (New cmodel)->getclass_list($page,url('classlist'));
        return $this->fetch('', compact('data'));
    }

    public function classrun(){
        $id = input('id/d', 0, 'intval');
        $cmodel = new cmodel;
        if (!$this->request->isAjax()) {
            if ($id) {
                $comm = $cmodel->getclass_one($id);
                $this->assign('comm',$comm);
            }
            return $this->fetch();
        }

        if ($id ? $cmodel->modclass($id) : $cmodel->addclass()) {
            return $this->renderSuccess('更新成功', url('classlist'));
        }
        $error = $cmodel->getError() ?: '更新失败';
        return $this->renderError($error);
    }

    // 分类展示隐藏
    public function classshow(){
        $id = input('id/d', 0, 'intval');
        return $this->ishow_change($id, 'courseclass');
    }

    // 分类展示隐藏
    public function classdel(){
        $id = input('id/d', 0, 'intval');
        return $this->isdel_change($id, 'courseclass');
    }

    // 改排序
    public function classsort(){
        $id = input('id/d', 0, 'intval');
        return $this->sort_change($id, 'courseclass');
    }

    // 教练列表
    public function coachlist(){
        $page = input('page/d',1,'intval');
        $data = (New cmodel)->getcoach_list($page,url('coachlist'));
        return $this->fetch('', compact('data'));
    }

    public function coachrun(){
        $id = input('id/d', 0, 'intval');
        $cmodel = new cmodel;
        if (!$this->request->isAjax()) {
            if ($id) {
                $comm = $cmodel->getcoach_one($id);
                $this->assign('comm',$comm);
            }
            return $this->fetch();
        }

        if ($id ? $cmodel->modcoach($id) : $cmodel->addcoach()) {
            return $this->renderSuccess('更新成功', url('coachlist'));
        }
        $error = $cmodel->getError() ?: '更新失败';
        return $this->renderError($error);
    }

    // 分类展示隐藏
    public function coachdel(){
        $id = input('id/d', 0, 'intval');
        return $this->isdel_change($id, 'coach');
    }

    // 课程报名列表
    public function coursereglist(){
        $sid = input('sid/s','','trim');
        if($sid == ''){
            $this->error('缺少课程编号', 'index');
        }
        $this->assign('sid',$sid);

        // 课程信息
        $coucomm = db('course')->alias('cou')
            ->join('courseclass class', 'cou.cid = class.id')
            ->where(['cou.sid' => $sid])
            ->field('cou.sid,cou.cid,cou.name,cou.datestamp,cou.stime,cou.etime,cou.maxnum,cou.totnum,cou.nownum,cou.isover,class.name classname')
            ->find();
        $this->assign('coucomm', $coucomm);

        $para = ['sid'=>$sid];
        $phone = input('phone/s','','trim');
        if($phone){     $para['phone'] = $phone;}
        $content = input('content/s','','trim');
        if($content){     $para['content'] = $content;}
        $stype = input('stype/d',0,'intval');
        if($stype){     $para['stype'] = $stype;}
        
        $this->assign('para', $para);

        $page = input('page/d', 1, 'intval');
        $data = (new cmodel)->getcourse_reglist($page, url('coursereglist',$para),$para);
        // dump($data);die;
        return $this->fetch('reglist', compact('data'));
    }

    // 添加报名信息
    public function courseregrun(){
        $sid = input('sid/s', '', 'trim');
        $this->assign('sid', $sid);
        // if ($sid == '') {
        //     $this->error('缺少课程编号', 'index');
        // }

        // 课程信息
        $coucomm = db('course')->alias('cou')
            ->join('courseclass class', 'cou.cid = class.id')
            ->where(['cou.sid' => $sid])
            ->field('cou.sid,cou.cid,cou.name,cou.datestamp,cou.stime,cou.etime,cou.maxnum,cou.totnum,cou.nownum,class.name classname')
            ->find();
        $this->assign('coucomm', $coucomm);

        if (!$this->request->isAjax()) {
            return $this->fetch('regrun');
        }
        
        // if ($id ? $cmodel->modcourse($id) : $cmodel->addcourse()) {
        $run = (new cmodel)->addcoursereg();
        if (!is_array($run)) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($run['mess']);
    }

    // 编辑备注信息
    public function courseregcontent(){
        $id = input('id/d', 0, 'intval');
        $comm = input('comm/s', '', 'trim');
        return $this->content_change($id, 'course_list', 'content', $comm);
    }
    // 删除报名信息
    public function courseregdel(){
        $id = input('id/d', 0, 'intval');
        return $this->isdel_change($id, 'course_list');
    }

    // 下课 更改isover状态
    public function courseover(){
        $stype = input('stype/d',1,'intval');
        if($stype == 1){
            return ajax(200,'asdasdxiakeasdasd');
        }else{
            $sid = input('sid/s', '', 'trim');
            if($sid == ''){
                return ajax(400);
            }

            // 更改课程下课状态
            db('course')->where(['sid'=>$sid])->update(['isover'=>2]);
            return ajax(200);
        }
    }
}