<?php
namespace app\manage\controller;
use app\common\model\User as usermodel;
// use app\common\model\Course as coumodel;

// use app\common\model\Level as LevelModel;
// use app\common\model\Withdraw as WithdrawModel;
// use app\common\model\Profit as ProfitModel;

use app\common\model\Packs as packsmodel;       // 课程包
use app\common\model\Coupon as couponmodel;     // 优惠券
use app\common\model\Distributions;
use app\common\model\Setting as SettingModel;

/**
 * 用户管理
 * Class User
 * @package app\manage\controller
 */
class User extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->para = '';

        $uid = input('uid/s', '', 'trim');
        if ($uid) {
            $this->para['uid'] = $uid;
        }

        $nickname = input('nickname/s', '', 'trim');
        if ($nickname) {
            $this->para['nickname'] = $nickname;
        }

        $gender = input('gender/d', 0, 'intval');
        if ($gender) {
            $this->para['gender'] = $gender;
        }

        $this->assign('para', $this->para);
    }

    /**
     * 用户列表
     */
    public function index(){
        $this->para['islock'] = 1;
        
        $page = input('page/d', 1, 'intval');
        $data = (new usermodel)->getuser_list($page, url('index', $this->para), $this->para);
        $res = SettingModel::getAll();
        return $this->fetch('', compact('data','res'));
    }
    // 冻结列表
    public function locklist(){
        $this->para['islock'] = 2;
        
        $page = input('page/d', 1, 'intval');
        $data = (new usermodel)->getuser_list($page, url('locklist', $this->para), $this->para);
        
        $this->assign('islock',2);

        return $this->fetch('index', compact('data'));
    }

    // 锁定用户
    public function userlock(){
        $id = input('id/d', 0, 'intval');
        if ($id == '') {
            return $this->renderError('非法操作');
        }
        $now = db('user')->where(['id' => $id])->value('islock');
        $mod = db('user')->where(['id' => $id])->update(['islock' => $now == 1 ? 2 : 1]);
        if ($mod) {
            // 日志

            return $this->renderSuccess('操作成功');
        } else {
            return $this->renderError('操作失败');
        }
    }

    // 删除
    public function userdel()
    {
        $id = input('id/d', 0, 'intval');
        return $this->isdel_change($id, 'user');
    }

    // 会员课程包列表
    public function packslist(){
        $uid = input('uid/s','','trim');
        if($uid == ''){
            $this->error('会员编号为空',url('index'));
        }

        $usercomm = (new usermodel)->get_detail($uid);
        $this->assign('usercomm', $usercomm);

        $page = input('page/d', 1, 'intval');
        $data = (new packsmodel)->getuserpacks_list($page,url('packslist', $this->para),$uid, $this->para);

        // dump($data);die;
        return $this->fetch('', compact('data'));
    }
    // 课程包记录
    public function packsloglist(){
        // dump(input());
        $id = input('id/d', 0, 'intval');
        if ($id == '') {
            return 400;
        }

        $list = (new packsmodel)->getpacks_loglist(0,'',$id);
        if(empty($list)){
            return 400;
        }

        $text = '';
        foreach($list as $val){
            $text .= '　　';
            $text .= date("Y-m-d H:i", $val['addstamp']);
            if($val['stype'] == 1){
                $text .= '　购买课程包:'.$val['name'].'　剩余课时:'.($val['tothour']-$val['hashour']);
            }else{
                $text .= '　使用课程包抵扣课程(订单编号:'.$val['oid'].'),使用课时'. $val['hashour']. '　剩余课时:'.($val['tothour']-$val['hashour']);
            }

            $text .= '<br>';
        }
        return $text;
    }

    // 会员优惠券列表
    public function couponlist(){
        $uid = input('uid/s','','trim');
        if($uid == ''){
            $this->error('会员编号为空',url('index'));
        }

        $usercomm = (new usermodel)->get_detail($uid);
        $this->assign('usercomm', $usercomm);

        $list = (new couponmodel)->getuser_coupon_list($uid,'');
        // dump($list);die;
        return $this->fetch('', compact('list'));
    }
    // 优惠券记录
    public function couponloglist(){
        // dump(input());
        $uid = input('uid/s', '', 'trim');
        if ($uid == '') {   return 400; }

        $list = (new couponmodel)->getcoupon_loglist(0,'',['uid'=> $uid]);
        if(empty($list)){   return 400; }

        // dump($list);die;

        $text = '';
        foreach($list as $val){
            $text .= '　　';
            $text .= date("Y-m-d H:i", $val['addstamp']);
            if($val['stype'] == 1){
                $text .= '　'.$val['content'].'　优惠券:'.$val['name'];
            }elseif($val['stype'] == 2){
                $text .= '　'.$val['content'].'　'.$val['name'].'(订单编号:'.$val['oid'].')';
            } elseif ($val['stype'] == 3) {
                // 发放
                $text .= '　'.$val['content'].'　'.$val['name'];
            }
            $text .= '<br>';
        }
        return $text;
    }











//////////////////

    /**
     * 用户收益列表
     * @param $uid
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function profit($uid=null)
    {
        $model = new ProfitModel;
        $list=$model::getlist($uid);
        return $this->fetch('profit', compact('list','page'));
    }
    /**
     * 会员领取列表
     * @param string $nickName
     * @param null $gender
     * @param $pid
     * @param $is_delete
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function card($nickName = '', $gender = null,$pid=null,$is_delete=0)
    {
        $nickName=trim($nickName);
        $model = new LevelModel;
        $list = $model->getList($nickName, $gender,$pid,$is_delete);
        $page=$list->render();
        return $this->fetch('card', compact('list','page'));
    }
    /**
     * 冻结用户列表
     * @param string $nickName
     * @param null $gender
     * @param null $pid
     * @param null $is_delete
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function frozen($nickName = '', $gender = null,$pid=null,$is_delete=1)
    {
        $nickName=trim($nickName);
        $model = new UserModel;
        $list = $model->getList($nickName,$gender,$pid,$is_delete);
        $page=$list->render();
        return $this->fetch('lock', compact('list','page'));
    }
    
    /**
     *删除用户
     *@param $user_id
     *@return array
     *@throws \think\exception\DbException
     */
    public function remove($user_id)
    {
        // 商品详情
        $model = UserModel::detail($user_id);
        if (!$model->removeDelete()) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('删除成功');
    }
    /**
     *解冻用户
     *@param $user_id
     *@return array
     *@throws \think\exception\DbException
     */
    public function uefreezing($user_id){
        // 商品详情
        $model = UserModel::detail($user_id);
        if (!$model->uefreezing()) {
            return $this->renderError('解冻失败');
        }
        return $this->renderSuccess('已解冻');
    }
    /**
     * 驳回
     * @param $id
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function reject($id='')
    {
        $model = WithdrawModel::detail($id);
        $data=$this->postData('withdraw');
        if(!$model->submits($data)){
            return $this->fetch();
        }
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

    //    签到记录
    public function signlog()
    {
        return $this->fetch('signlog');
    }

}