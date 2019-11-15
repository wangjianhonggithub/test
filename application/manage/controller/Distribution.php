<?php

namespace app\manage\controller;

use app\common\model\Distributions;

/**
 * 分销管理
 * Class app
 * @package app\manage\controller
 */
class Distribution extends Controller
{

    //      分销商等级
    public function grade_list()
    {
        $page = input('page/d', 1, 'intval');
        $data = Distributions::get_all_data($page, url('grade_list'));
        $this->assign('data',$data);

        return $this->fetch('grade_list');
    }
    //    添加&修改
    public function grade_run()
    {
        $id = input('id/d', 0, 'intval');
        $distributionmodel = new Distributions;
        if (!$this->request->isAjax()) {

            if ($id) {
                $comm = $distributionmodel->get_one($id);
                $this->assign('comm', $comm);
            }

            return $this->fetch('grade_run');
        }


        if ($id ? $distributionmodel->mod_grade($id) : $distributionmodel->add_grade()) {
            return $this->renderSuccess('更新成功', url('grade_run'));
        }
        $error = $distributionmodel->getError() ? $distributionmodel->getError() : '更新失败';
        return $this->renderError($error);
    }
    //  删除
    public function grade_del()
    {
        $id = $_POST['id'];
        return $this->isdel_change($id, 'distributor_grade');
    }
    //    分销商列表
    public function distributor_list()
    {
        $nickname = input('nickname/s', '', 'trim');
        if ($nickname) {    $para['nickname'] = $nickname;  }

        $level = input('level/d', 0, 'intval');
        if ($level) {     $para['level'] = $level;    }

        $this->assign('para', $para);
        $page = input('page/d', 1, 'intval');
        $data = Distributions::get_all_user($page, url('grade_list',$para),$para);
        $level = Distributions::serach_level();
        $this->assign('level',$level);
        $this->assign('data',$data);

        return $this->fetch('user_list');

    }

    //   查看
    public function detail()
    {
        $id = input('id/d', 0, 'intval');
        if (!$this->request->isAjax()) {
            $comm = Distributions::getuserdetail($id);
            $level = Distributions::serach_level();
            $this->assign('level',$level);
            $this->assign('comm',$comm);
            return $this->fetch('detail');
        }
        $user_id = $_POST['user_id'];
        echo Distributions::updatedetail($user_id);
    }


    //    检测是否可以成为上级
    public function check_phone()
    {

        $phone = $_GET['parent_phone'];
        $id = $_GET['id'];
        echo Distributions::check_phone($id,$phone);
    }
    //  更改上级
    public function confirm_phone()
    {
        $phone = $_GET['parent_phone'];
        $id = $_GET['id'];
        echo Distributions::confirm_phone($id,$phone);
    }

    //    分成日志
    public function commission_list()
    {
        $uid = input('uid/d', '', 'intval');
        if ($uid) {    $para['uid'] = $uid;  }

        $oid = input('oid/d', 0, 'intval');
        if ($oid) {     $para['oid'] = $oid;    }

        $startstamp = input('startstamp/s', '', 'trim');
        if ($startstamp) {
            $para['startstamp'] = strtotime($startstamp);
        }else{
            $para['startstamp'] = time();
        }

        $endstamp = input('endstamp/s', '', 'trim');
        if ($endstamp) {
            $para['endstamp'] = strtotime($endstamp);
        }else{
            $para['endstamp'] = strtotime("+1 day");
        }



        $this->assign('para', $para);
        $page = input('page/d', 1, 'intval');
        $data = Distributions::commission_list($page, url('commission_list',$para),$para);
        $this->assign('data',$data);

        return $this->fetch('commissionlog_list');
    }


}
