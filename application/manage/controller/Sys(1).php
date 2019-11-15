<?php
namespace app\manage\controller;
use think\facade\Session;

use app\common\model\Menu as MenuModel;
use app\common\model\Sys as sysmodel;

/**
 * 系统管理 超级管理员之外踢出去
 */
class Sys extends Controller{
    public function __construct(){
        parent::__construct();
        // 验证超级管理员 或者定死id
        // dump($this->dzcadmin);die;
        if($this->dzcadmin['user_id'] == 10001 && $this->dzcadmin['is_super'] == 0){
        }else{
            $this->error('权限不足');
        }
        $this->menu['sys'] = [
            'name' => '系统',
            'model' => 'sys',
            'icon' => 'xe702',
            'list' => [
                1 => [
                    // 'name' => '　系统配置　　(非开发勿动)',
                    'name' => '系统配置',
                    'list' => [
                        1 => ['name' => '菜单权限','url' => 'manage/sys/menulist'],
                        2 => ['name' => '功能配置', 'url' => 'manage/sys/funcrun'],
                    ]
                ]
            ]
        ];
        $this->assign('menu',$this->menu);
    }
    public function _empty(){
        
    }

    // 权限列表
    public function menulist(){
        $lists = (new MenuModel)->getmenulist(0);
        return $this->fetch('',compact('lists'));
    }

    // 删除菜单
    public function delmenu(){
        $id = input('id/d',0,'intval');
        if($id == 0){
            return $this->renderError('非法操作');
        }

        $del = db('menu')->where(['id'=>$id])->update(['isdel'=>2]);
        if($del){
            return $this->renderSuccess('删除成功');
        }else{
            return $this->renderError('删除失败');
        }
    }
    // 展示/隐藏菜单
    public function showmenu(){
        $id = input('id/d',0,'intval');
        if($id == 0){
            return $this->renderError('非法操作');
        }
        $now = db('menu')->where(['id'=>$id])->value('ishow');
        $mod = db('menu')->where(['id'=>$id])->update(['ishow'=> $now==1?2:1 ]);
        if($mod){
            return $this->renderSuccess('操作成功');
        }else{
            return $this->renderError('操作失败');
        }
    }
    // 改排序
    public function changesort(){
        $id = input('id/d',0,'intval');
        if($id == 0){
            return $this->renderError('非法操作');
        }
        $num = input('num/d');
        $mod = db('menu')->where(['id'=>$id])->update(['sort'=>$num]);
        if($mod){
            return $this->renderSuccess('操作成功');
        }else{
            return $this->renderError('操作失败');
        }
    }

    public function menurun(){
        $id = input('id/d',0,'intval');

        if (!$this->request->isAjax()) {
            $lists = (new MenuModel)->getmenulist(0,'id,name,pid');
            // dump($lists);die;
            if($id != 0){
                $comm = db('menu')->where(['id'=>$id])->find();
                $this->assign('comm',$comm);
                // dump($comm);die;
            }
            return $this->fetch('', compact('lists'));
        } 

        if($id){
            // 编辑
            $pid = input('pid/d');
            if($id == $pid){    return $this->renderError('父级不能为菜单本身'); }
            if($pid == 0){
                $url = input('url');
                $model = explode('/',$url)[1];
                if($model == ''){   $this->renderError('路径填写有误'); }
            }else{
                $model = db('menu')->where(['id'=>$pid])->value('model');
            }

            $mod_data = [
                'name' => input('name/s'),
                'url' => input('url/s'),
                'pid' => $pid,
                'sort' => input('sort/s'),
                'update_time' => time(),
                'model' => $model,
                'icon' => input('icon/s'),
                'app_id' => $this->dzcadmin['app_id'],
                'ishow' => input('ishow/d'),
            ];
            $run = db('menu')->where(['id'=>$id])->update($mod_data);
        }else{
            $pid = input('pid/d');
            if($pid == 0){
                $url = input('url');
                $model = explode('/',$url)[1];
                if($model == ''){   $this->renderError('路径填写有误'); }
            }else{
                $model = db('menu')->where(['id'=>$pid])->value('model');
            }

            $add_data = [
                'name' => input('name/s'),
                'url' => input('url/s'),
                'pid' => $pid,
                'sort' => input('sort/s'),
                'create_time' => time(),
                'update_time' => time(),
                'model' => $model,
                'icon' => input('icon/s'),
                'app_id' => $this->dzcadmin['app_id'],
                'ishow' => input('ishow/d'),
            ];
            $run = db('menu')->insert($add_data);
        }

        if($run){
            return $this->renderSuccess('更新成功', url('menulist'));
        }else{
            return $this->renderSuccess('更新失败', url('menulist'));
        }
    }


    // 功能设置
    public function funcrun()
    {

        $sysmodel = new sysmodel();
        if (!$this->request->isAjax()) {
            // $lists = db('sysconfig')->select();
            $list = $sysmodel->getsys();
            return $this->fetch('', compact('list'));
        }

        $data = input();

        set_time_limit(0);  // 脚本执行时间0
        foreach($data as $k=>$val){
            // 先取值判断是否更改 根据key判断是否改具体位置
            $isopen = $sysmodel->getsys($k);
            if($isopen != $val){
                // 更改状态 更改后台模块
                $sysmodel->modsys($k,$val);
            }
        }
        return $this->renderSuccess('更新成功', url('funcrun'));
    }
}