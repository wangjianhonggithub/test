<?php
namespace app\api\controller;
use app\api\controller\Islogin;

use app\common\model\Course as cmodel;
use app\common\model\Coupon as cpmodel;

/**
 * 优惠券控制器
 */
class Coupon extends Islogin
{
    public function initialize()
    {
        parent::initialize();
    }

    // 通过sid 获取可使用的优惠券
    public function couponlist(){
        // 课程编号
        $sid = input('sid/s', 'undefined','trim');
        if($sid == 'undefined'){
            $list = (new cpmodel)->getuser_coupon_list($this->user['uid'], 'id,uid,cpsid,name,cid,manyuan,jianyuan,intro,validays,pastamp,ispast,isuse');
        }else{
            // 分类
            $comm = (new cmodel)->getcourse_detail($sid, 'cid,price');
            $list = (new cpmodel)->getuser_coupon_list($this->user['uid'], 'id,uid,cpsid,name,cid,manyuan,jianyuan,intro,validays,pastamp,ispast,isuse',['cid'=>'0,'. $comm['cid'].'', 'manyuan'=>$comm['price']]);
        }
        if ($list) {
            return ajax(200, count($list), $list);
        } else {
            return ajax(400, 0);
        }

    }

    // 获取会员的优惠券
    public function coupondetail(){
        $id = input('cpsid/d',0,'intval');
        if($id == 0){
            return ajax(400,'ID为空');
        }
        $comm = db('user_coupon')->where(['id'=> $id])->field('id,name,manyuan,jianyuan')->find();
        if($comm){
            return ajax(200,$comm['id'],$comm);
        }else{
            return ajax(400,'优惠券为空');
        }
    }
}
