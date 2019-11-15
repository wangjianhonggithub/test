<?php
namespace app\manage\controller;
use app\common\model\FlashSale;
use think\Db;

use app\common\model\Assemble as AssembleModel;
use app\common\model\Robbuy as RobbuyModel;
use app\common\model\Promorder as PromorderModel;
use app\common\model\UserCoupon as UserCouponModel;
use app\common\model\Turntable as TurntableModel;

use app\common\model\Ads as admodel;        // 广告
use app\common\model\Packs as pmodel;       // 课程包
use app\common\model\Course as cmodel;      // 课程
use app\common\model\Coupon as cpmodel;     // 优惠券
use app\common\model\User as usermodel;     // 会员
use app\common\model\FlashSale as FlashSalemodel;     // 抢购


/**
 * 优惠券管理
 * Class Coupon
 * @package app\manage\controller\market
 */
class Market extends Controller
{
    /* @var CouponModel $model */
    private $model;
    /**
     * 构造方法
     */
    public function initialize()
    {
        parent::initialize();
    }
    
    public function adlist(){
        $para = '';
        $gid = input('gid/d', 0, 'intval');
        if ($gid) {     $para['gid'] = $gid;    }

        $name = input('name/s', '', 'trim');
        if ($name) {    $para['name'] = $name;  }
        $this->assign('para', $para);

        // 广告分组
        $grouplist = (new admodel)->getgroup_list(0);
        $this->assign('grouplist', $grouplist);

        $page = input('page/d', 1, 'intval');
        $data = (new admodel)->getad_list($page, url('adlist', $para), $para);

        return $this->fetch('market/ad/list', compact('data'));

    }
    public function adrun(){
        $id = input('id/d', 0, 'intval');
        $admodel = new admodel;
        if (!$this->request->isAjax()) {
            if ($id) {
                $comm = $admodel->getad_one($id);
                $this->assign('comm', $comm);
            }
            // 广告分组
            $grouplist = (new admodel)->getgroup_list(0);
            $this->assign('grouplist', $grouplist);

            return $this->fetch('market/ad/run');
        }

        // dump(input());die;
        if ($id ? $admodel->modad($id) : $admodel->addad()) {
            return $this->renderSuccess('更新成功', url('adlist'));
        }
        $error = $admodel->getError() ?: '更新失败';
        return $this->renderError($error);
    }
    // 排序
    public function adsort()
    {
        $id = input('id/d', 0, 'intval');
        return $this->sort_change($id, 'ads');
    }
    // 展示隐藏
    public function adshow()
    {
        $id = input('id/d', 0, 'intval');
        return $this->ishow_change($id, 'ads');
    }
    // 删除
    public function addel()
    {
        $id = input('id/d', 0, 'intval');
        return $this->isdel_change($id, 'ads');
    }



    // 广告分组
    public function adgrouplist(){
        $para = '';
        $name = input('name/s', '', 'trim');
        if ($name) {
            $para['name'] = $name;
        }
        $this->assign('para', $para);

        $page = input('page/d', 1, 'intval');
        $data = (new admodel)->getgroup_list($page, url('adgrouplist', $para), $para);
        return $this->fetch('market/ad/grouplist', compact('data'));
    }
    public function adgrouprun(){
        $id = input('id/d', 0, 'intval');
        $admodel = new admodel;
        if (!$this->request->isAjax()) {
            if ($id) {
                $comm = $admodel->getgroup_one($id);
                $this->assign('comm', $comm);
            }
            return $this->fetch('market/ad/grouprun');
        }
        // dump(input());die;
        if ($id ? $admodel->modgroup($id) : $admodel->addgroup()) {
            return $this->renderSuccess('更新成功', url('adgrouplist'));
        }
        $error = $admodel->getError() ?: '更新失败';
        return $this->renderError($error);
    }
    // 删除
    public function adgroupdel()
    {
        $id = input('id/d', 0, 'intval');
        return $this->isdel_change($id, 'ad_group');
    }
    

    // 课程包管理
    public function packslist(){
        $para = '';
        $cid = input('cid/d', 0, 'intval');
        if ($cid) {     $para['cid'] = $cid;    }

        $sid = input('sid/s', '', 'trim');
        if ($sid) {     $para['sid'] = $sid;    }

        $name = input('name/s', '', 'trim');
        if ($name) {    $para['name'] = $name;  }
        $this->assign('para', $para);
        // dump($para);die;
        // 获取课程分类
        $classlist = (new cmodel)->getclass_list(0);
        $this->assign('classlist', $classlist);
 
        $page = input('page/d', 1, 'intval');
        $data = (new pmodel)->getpacks_list($page, url('packslist', $para), $para);
        return $this->fetch('market/packs/list', compact('data'));
    }
    public function packsrun(){
        $id = input('id/d', 0, 'intval');
        $pmodel = new pmodel;
        if (!$this->request->isAjax()) {
            if ($id) {
                $comm = $pmodel->getpacks_one($id);
                $this->assign('comm', $comm);
            }
            // 获取课程分类
            $classlist = (new cmodel)->getclass_list(0);
            $this->assign('classlist', $classlist);
       
            return $this->fetch('market/packs/run');
        }

        // dump(input());die;
        if ($id ? $pmodel->modpacks($id) : $pmodel->addpacks()) {
            return $this->renderSuccess('更新成功', url('packslist'));
        }
        $error = $pmodel->getError() ?: '更新失败';
        return $this->renderError($error);
    }
    // 排序
    public function packsort()
    {
        $id = input('id/d', 0, 'intval');
        return $this->sort_change($id, 'packs');
    }
    // 展示隐藏
    public function packshow()
    {
        $id = input('id/d', 0, 'intval');
        return $this->ishow_change($id, 'packs');
    }
    // 删除
    public function packsdel()
    {
        $id = input('id/d', 0, 'intval');
        return $this->isdel_change($id, 'packs');
    }

    // 课程包 购买 使用
    public function packsloglist(){
        $para = '';
        $stype = input('stype/d', 0, 'intval');
        if ($stype) {
            $para['stype'] = $stype;
        }

        $name = input('name/s', '', 'trim');
        if ($name) {
            $para['name'] = $name;
        }

        $uid = input('uid/s', '', 'trim');
        if ($uid) {
            $para['uid'] = $uid;
        }

        $this->assign('para', $para);

        $page = input('page/d', 1, 'intval');
        $data = (new pmodel)->getpacks_loglist($page, url('packsloglist', $para), $para);

        // dump($data);die;

        return $this->fetch('market/log/packslist', compact('data'));
    }
    // 删除
    public function packslogdel(){
        $id = input('id/d', 0, 'intval');
        $del = db('log_coupon')->where(['id' => $id])->delete();
        if($del){
            return $this->renderSuccess('操作成功');
        }else{
            return $this->renderError('操作失败');
        }
    }



    // 优惠券列表
    public function couponlist()
    {
        $para = '';
        $cid = input('cid/d', 0, 'intval');
        if ($cid) {     $para['cid'] = $cid;    }

        $sid = input('sid/s', '', 'trim');
        if ($sid) {     $para['sid'] = $sid;    }

        $name = input('name/s', '', 'trim');
        if ($name) {    $para['name'] = $name;  }

        $isreget = input('isreget/d', 0, 'intval');
        if ($isreget) {     $para['isreget'] = $isreget;    }

        $this->assign('para', $para);
        // dump($para);die;
        // 获取课程分类
        $classlist = (new cmodel)->getclass_list(0);
        $this->assign('classlist', $classlist);

        $page = input('page/d', 1, 'intval');
        $data = (new cpmodel)->getcoupon_list($page, url('couponlist', $para), $para);

        // dump($data);die;
        return $this->fetch('market/coupon/list', compact('data'));
    }
    // 新增/编辑
    public function couponrun(){
        $id = input('id/d', 0, 'intval');
        $cpmodel = new cpmodel;
        if (!$this->request->isAjax()) {
            if ($id) {
                $comm = $cpmodel->getcoupon_one($id);
                $this->assign('comm', $comm);
            }
            // 获取课程分类
            $classlist = (new cmodel)->getclass_list(0);
            $this->assign('classlist', $classlist);

            return $this->fetch('market/coupon/run');
        }


        if ($id ? $cpmodel->modcoupon($id) : $cpmodel->addcoupon()) {
            return $this->renderSuccess('更新成功', url('couponlist'));
        }
        $error = $cpmodel->getError() ?: '更新失败';
        return $this->renderError($error);
    }
    // 删除
    public function coupondel(){
        $id = input('id/d', 0, 'intval');
        return $this->isdel_change($id, 'coupon');
    }
    // 更改状态
    public function couponisreget()
    {
        $id = input('id/d', 0, 'intval');
        if ($id == 0) {
            return $this->renderError('非法操作');
        }

        $now = db('coupon')->where(['id' => $id])->value('isreget');
        $mod = db('coupon')->where(['id' => $id])->update(['isreget' => $now == 1 ? 2 : 1]);
        if ($mod) {
            // 日志

            return $this->renderSuccess('操作成功');
        } else {
            return $this->renderError('操作失败');
        }

    }
    // 发放优惠券
    public function sendcoupon(){
        $sid = input('sid/s','','trim');
        $this->assign('sid',$sid);

        $para = [];
            $uid = input('uid/s', '', 'trim');
            if ($uid) {
                $para['uid'] = $uid;
            }
            $nickname = input('nickname/s', '', 'trim');
            if ($nickname) {
                $para['nickname'] = $nickname;
            }
            $gender = input('gender/d', 0, 'intval');
            if ($gender) {
                $para['gender'] = $gender;
            }
        $para['sid'] = $sid;
        $para['islock'] = 1;
        $this->assign('para', $para);

        if (!$this->request->isAjax()) {
            // 获取未冻结会员
            $page = input('page/d', 1, 'intval');
            $data = (new usermodel)->getuser_list($page, url('sendcoupon', $para), $para);
            return $this->fetch('market/coupon/send', compact('data'));
        }

        // 展示用户优惠券
        $couponshow = input('couponshow/d',0,'intval');
        if($couponshow == 1){
            $list = (new cpmodel)->getuser_coupon_list($uid, '');
            if(empty($list)){
                return 400;
            }
            $text = '';
            foreach ($list as $val) {
                $text .= '　　';
                $text .= '优惠券名称:'.$val['name'] . '　满' . $val['manyuan'].'减'.$val['jianyuan'];
                $text .= '　使用范围:'.$val['classname'];
                $text .= '　领取/发放时间:'.date("Y-m-d H:i", $val['addstamp']);
                $text .= '　有效期:'.$val['validays'].'天';
                $text .= '　过期日期:'. date("Y-m-d", $val['pastamp']);
  
                $text .= '<br>';
                $text .= '<br>';
            }
            return $text;
        }

        // 发送优惠券
        $para = input();
        unset($para['uids']);
        
        $userlist = $uids = input('uids');
        if($uids == 0){
            $userlist = (new usermodel)->getuser_list(0, 'uid', $para);
        }


        $cpmodel = new cpmodel;
        foreach($userlist as $val){
            if(is_array($val)){
                $cpmodel->sendcoupon($val['uid'],$sid);
            }else{
                $cpmodel->sendcoupon($val,$sid);
            }
        }
        return $this->renderSuccess('操作成功');
    }
   
    // 领取 发放记录
    public function couponloglist(){
        $para = '';
        $stype = input('stype/d', 0, 'intval');
        if ($stype) {
            $para['stype'] = $stype;
        }

        $name = input('name/s', '', 'trim');
        if ($name) {
            $para['name'] = $name;
        }

        $adminid = input('adminid/d', 0, 'intval');
        if ($adminid) {
            $para['adminid'] = $adminid;
        }

        $this->assign('para', $para);
        // 获取课程分类
        // $classlist = (new cmodel)->getclass_list(0);
        // $this->assign('classlist', $classlist);

        $page = input('page/d', 1, 'intval');
        $data = (new cpmodel)->getcoupon_loglist($page, url('couponloglist', $para), $para);

        // dump($data);die;
        $adminlist = db('web_user')->where(['is_delete'=>0])->order('create_time asc')->field('user_id,user_name')->select();
        $this->assign('adminlist', $adminlist);

        // dump($data);die;

        return $this->fetch('market/log/couponlist', compact('data'));
    }

    // 删除
    public function couponlogdel(){
        $id = input('id/d', 0, 'intval');
        $del = db('log_coupon')->where(['id' => $id])->delete();
        if($del){
            return $this->renderSuccess('操作成功');
        }else{
            return $this->renderError('操作失败');
        }
    }

    //    抢购活动列表
    public function qbgoods()
    {
        $para = '';

        $title = input('title/s', '', 'trim');
        if ($title) {    $para['title'] = $title;  }

        $goods_name = input('goods_name/s', '', 'trim');
        if ($goods_name) {    $para['goods_name'] = $goods_name;  }

        $status = input('status/d', 0, 'intval');
        if ($status) {     $para['status'] = $status;    }

        $this->assign('para', $para);
        // dump($para);die;


        $page = input('page/d', 1, 'intval');
        $flashsale = (new FlashSale)->flashsale_list($page, url('qbgoods', $para), $para);
        $this->assign('data', $flashsale);

        return $this->fetch('market/flash_sale/index');
    }

    //  添加修改活动
    public function addflashsale()
    {
        $id = input('id/d', 0, 'intval');
        $flashsalemodel = new FlashSale;
        if (!$this->request->isAjax()) {

            if ($id) {
                $comm = $flashsalemodel->get_one($id);
                $this->assign('comm', $comm);
            }
            $sale_goods = $flashsalemodel->getgoods();
            $this->assign('sale_goods', $sale_goods);
            return $this->fetch('market/flash_sale/add');
        }


        if ($id ? $flashsalemodel->modfalshsale($id) : $flashsalemodel->addfalshsale()) {
            return $this->renderSuccess('更新成功', url('qbgoods'));
        }
        $error = $flashsalemodel->getError() ? $flashsalemodel->getError() : '更新失败';
        return $this->renderError($error);

    }

    //      删除活动
    public function flashsaledel()
    {
        $id = $_POST['id'];
        return $this->isdel_change($id, 'flash_sale');

    }
    //    提前结束活动
    public function flashsaleend()
    {
        $id = $_POST['id'];
        $flashsalemodel = new FlashSale;
        $res = $flashsalemodel->end($id);
        if ($res) {
            return $this->renderSuccess('操作成功');
        }else{
            return $this->renderError('操作失败');
        }
    }
}