<?php
namespace app\manage\controller;

use app\common\model\Course as cmodel;
use app\common\model\Order as ordermodel;
/**
 * 订单控制器 
 */
class Ordercourse extends Controller{
    public function __construct(){
        parent::__construct();

        $this->para = '';
        
        $oid = input('oid/s', '', 'trim');
        if ($oid) {     $this->para['oid'] = $oid;  }

        $phone = input('phone/s', '', 'trim');
        if ($phone) {     $this->para['phone'] = $phone;  }

        $paytype = input('paytype/d', 0, 'intval');
        if ($paytype) {     $this->para['paytype'] = $paytype;}

        $course_name = input('course_name/s', '', 'trim');
        if ($course_name) {     $this->para['course_name'] = $course_name;  }

        $number = input('number/d', 0, 'intval');
        if ($number) {     $this->para['number'] = $number;}

        $stype = input('stype/d', 0, 'intval');
        if ($stype) {     $this->para['stype'] = $stype;}

        $this->assign('para', $this->para);
    }

    // 课程列表
    public function index(){

        $uid = input('uid/s','','');
        if($uid){
            $this->para['uid'] = $uid;
            $this->assign('uid', $uid);
        }
        $page = input('page/d', 1, 'intval');

        $data = (new ordermodel)->getorder_list($page, url('index',$this->para), $uid, $this->para);

        // dump($data);die;

        return $this->fetch('', compact('data'));
    }

    public function detail(){
        $oid = input('oid/s', '', 'trim');
        $this->assign('oid', $oid);

        $comm = (new ordermodel)->getorder_one($oid,'');


        switch ($comm['paytype']) {
            case 1:
                $comm['logpay'] = db('log_pay')->where(['oid'=>$oid])->find();
                break;
            case 2:
                $comm['logpay'] = db('log_pay')->where(['oid'=>$oid])->find();
                $comm['logcoupon'] = db('log_coupon')->where(['oid'=>$oid])->find();
                break;
            case 3:
                // $comm['logpay'] = db('log_pay')->where(['oid'=>$comm['oid']])->find();
                $comm['logpacks'] = db('log_packs')->where(['oid'=>$comm['oid'], 'stype'=>2])->find();
                break;
            
            default:
                # code...
                break;
        }
        // dump($comm);die;
        $this->assign('comm', $comm);
        
        $usercomm = db('user')->where(['uid'=>$comm['uid']])->find();
        $this->assign('usercomm', $usercomm);
        $coucomm = db('course')->where(['sid'=>$comm['sid']])->find();
        $coucomm['classname'] = db('courseclass')->where(['id'=> $coucomm['cid']])->value('name');
        $coucomm['coname'] = db('coach')->where(['id'=> $coucomm['coid']])->value('name');

        $this->assign('coucomm', $coucomm);

        return $this->fetch();
    }
    // 删除订单
    public function orderdel(){   
        $id = input('id/d', 0, 'intval');
        return $this->isdel_change($id, 'order');
    }

}