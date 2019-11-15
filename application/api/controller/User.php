<?php
namespace app\api\controller;
use app\api\controller\Islogin;

use app\common\model\User as UserModel;

/**
 * 会员信息
 */
class User extends Islogin
{
    public function initialize()
    {
//        parent::initialize();
    }

    // 会员信息
    public function userinfo(){
        $userinfo = [
            'nickname' => $this->user['nickname'],
            'headimg' => $this->user['headimg'],
            'ordernum' => db('order')->where(['uid'=>$this->user['uid'],'stype'=>1,'isdel'=>1])->count(), // 预约中课程数量
        ];
        return ajax(200,'',$userinfo);
    }
    // 查看用户是否报名过该项目
    public function checksign(){
        $sid = input('sid/s');
        $is_sign = db('order')->where(['uid'=>$this->user['uid'],'sid'=>$sid,'isdel'=>1])->find();
        if($is_sign){
            return ajax(400,'您已报名该课程,请勿重复提交');
        }
        return ajax(200);
    }

}
