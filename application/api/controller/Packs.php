<?php
namespace app\api\controller;
use app\api\controller\Islogin;

use app\common\model\Packs as pmodel;       // 课程包

/**
 * 课程包控制器
 * Class App
 * @package app\api\controller
 */
class Packs extends Islogin
{
    // 课程包列表
    public function list(){
        // 课程包列表
        $fields = 'id,sid,cid,name,price,tothour,validays,everyuan,saveyuan,intro';
        $para = [
            'ishow' => 1,
        ];

        $list = (new pmodel)->getpacks_list(0, $fields, $para);
        if ($list) {
            return ajax(200, '课程包列表', $list);
        }
        return ajax(400);
    }

    // 个人课程包 展示
    public function mylist()
    {
        $sid = input('sid/s','','trim');

        // 课程包列表
        $fields = 'id,uid,sid,cid,name,price,tothour,intro,tothour,hashour';
        if($sid == 'undefined'){
            $list = (new pmodel)->getuserpacks_list(0, $fields, $this->user['uid'], ['isuse'=>1]);
        }else{
            $cid = db('course')->where(['sid'=>$sid])->value('cid');
            $list = (new pmodel)->getuserpacks_list(0, $fields, $this->user['uid'], ['cid'=> '0,'.$cid,'isuse'=>1]);
        }
        if ($list) {
            return ajax(200, count($list), $list);
        }
        return ajax(400);
    }

    // 课程包详情
    public function detail(){
        $packsid = input('packsid/d',0,'intval');
        if($packsid == ''){
            return ajax(301,'ID为空');
        }

        $packcomm = (new pmodel)->getuserpacks_one($packsid, 'id,uid,sid,cid,name,tothour,hashour');
        if($packcomm){
            return ajax(200, $packcomm['name'], $packcomm);
        }
        return ajax(400);
    }
}
