<?php
namespace app\manage\controller;
use think\facade\Session;

use app\common\model\Menu as MenuModel;
use app\common\model\Auth as AuthModel;
use app\common\model\WebUser as WebModel;
use app\common\model\UserRole;
use app\common\model\LoginLog as LoginLogModel;
/**
 * 管理员个人控制器 不走权限
 */
class My extends Controller
{
    /**
     * 管理员更改个人信息
     */
    public function myself(){
        // dump($this->dzcadmin);die;
        $role_name = db('auth')->where(['role_id'=>$this->dzcadmin['role_id']])->value('role_name');
        return $this->fetch('',compact('role_name'));
    }
    
    // 更改个人信息
    public function editmyself(){
        
        // dump($this->dzcadmin);die;
        $save_data = array(
            'real_name' => input('real_name/s','','trim'),
            'phone' => input('phone/s','','trim'),
            'note' => input('note/s','','trim'),
        );
        
        $opass = input('password/s','','trim');
        $npass1 = input('npass1/s','','trim');
        $npass2 = input('npass2/s','','trim');
        if($npass1){
            // 改密码 核对原始密码
            if($opass == ''){
                return $this->renderError('原始密码为空');
            }
            if($npass1 != $npass2){
                return $this->renderError('两次密码不一致');
            }
            if(dzcpass_shift($opass) != $this->dzcadmin['password']){
                return $this->renderError('原始登录密码错误,请重试');
            }
            $save_data['password'] = dzcpass_shift($npass1);
        }
        $mod = db('web_user')->where(['user_id'=>$this->dzcadmin['user_id']])->update($save_data);
        if($mod){
            $user = db('web_user')->where(['user_id'=>$this->dzcadmin['user_id']])->find();
            Session::set('dzcadmin', $user);

            if($save_data['password']){
                return $this->renderSuccess('信息编辑成功,请重新登陆',url('login/logout'));
            }else{
                return $this->renderSuccess('信息编辑成功',url('my/myself'));
            }
        }
    }

}