<?php
namespace app\api\controller;
use app\api\controller\Islogin;

use app\common\model\Order as ordermodel;

/**
 * 订单控制器
 */
class Order extends Islogin
{
    public function initialize()
    {
        parent::initialize();
    }

    public function orderlist(){
        $ordertype = input('ordertype/d',0,'intval');

        $list = (new ordermodel)->getorder_list(0,'',$this->user['uid'],['stype'=>$ordertype]);
        // dump(input());
        // dump($list);die;
        if($list){
            return ajax(200,$ordertype,$list);
        }
        return ajax(400,'','');
    }

    public function orderdetail(){
        $oid = input('oid/s','','trim');
        if($oid == ''){
            return ajax(500,'订单编号为空');
        }

        $order = (new ordermodel)->getorder_one($oid,'oid,uid,sid,phone,number,course_name,course_price,course_date,course_stime,course_etime,cpsid,cprice,packsid,price,addstamp,paytype,stype');
        
        if($order){
            $weekarray = array("日", "一", "二", "三", "四", "五", "六");
            $order['course_date'] = date("Y-m-d", $order['course_date']);
            $order['course_week'] = '星期' . $weekarray[date("w", $order['course_date'])];
            $order['course_stime'] = substr($order['course_stime'], 0, 5);
            $order['course_etime'] = substr($order['course_etime'], 0, 5);
            $order['addstamp'] = date("Y-m-d H:i", $order['addstamp']);

            return ajax(200,$oid,$order);
        }
        return ajax(400);
    }
}
