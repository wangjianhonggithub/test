<?php
/**
 * 鼎智诚科技
 */
namespace app\manage\controller;
use think\facade\Request;
use think\facade\Session;
use app\common\model\Menu as MenuModel;
use app\common\model\Index;
use app\common\model\Auth as AuthModel;

error_reporting(E_ERROR | E_PARSE);    // 设置报错级别 不加这个太严格了

/**
 * 后台控制器基类
 * Class BaseController
 * @package app\manage\controller
 */
class Controller extends \think\Controller
{
    /* @var array $dzcmanage 管理员登录信息 */
    protected $dzcmanage;
	/* @var string $app_id 小程序信息 */
	protected $app_id;
	/* @var string $is_super 用户关联的主账号 */
	protected $is_super;
    /* @var string $route 当前控制器名称 */
    protected $controller = '';
    /* @var string $route 当前方法名称 */
    protected $action = '';
    /* @var string $route 当前路由uri */
    protected $routeUri = '';
    /* @var string $route 当前路由：分组名称 */
    protected $group = '';

    /* @var array $allowAllAction 全局白名单 */
    protected $allowAllAction= [
        'login/login'
    ];

    /* @var array $notLayoutAction 无需全局layout */
    protected $notLayoutAction = [
        'login/login',   // 登录页面
    ];

    // 不走权限的控制器  小写
    protected $noAuthController = [
        'my',    // 个人信息
        'sys',   // 后台系统
    ];

    /**
     * 后台初始化
     */
    public function initialize(){
        // 当前路由信息
        $this->getRouteinfo();
		// 商家登录信息
        $this->dzcadmin = Session::get('dzcadmin');
		//用户关联的主账号
		$this->is_super = !empty($this->dzcadmin['is_super'])? $this->dzcadmin['is_super']:$this->dzcadmin['user_id'];
		//小程序信息
		$this->app_id = $this->dzcadmin['app_id'];
		 // 验证登录
        $this->checkLogin();
        // 全局layout
        $this->layout();
    }
     /*
     *取当前控制器并进行验证
     */
    public function auth(){
        $url=Request::module().'/'.$this->routeUri;
        $auth = new AuthModel;
        
        if(!in_array($this->controller,$this->noAuthController)){
            if($auth->check($url,$this->dzcadmin)===false){
                if (Request::isGet() && Request::isPost()){
                    return $this->renderError('权限不足');
                }
                $this->error('权限不足');
            }
        }
    }
    /**
     * 全局layout模板输出
     */
    private function layout(){
        // 验证当前请求是否在白名单
        if(in_array($this->routeUri, $this->notLayoutAction)){
            $this->view->engine->layout(false);
        }else{
            $request= Request::instance();
            $this->menu = $this->menu();
            $this->action = input('action/s','','trim');
            // 输出到view
            $this->assign([
                'base_url'      => base_url(),          // 当前域名
                'dzcmanageurl'  => url('/manage/'),     // 后台模块url
                'group'         => $this->group,        // 当前页面分组
                'url'           => $request->path(),    // 当前路径
                'menu'          => $this->menu,         // 后台菜单
                'dzcadmin'      => $this->dzcadmin,     // 商家登录信息
                'request'       => Request::instance(), // Request对象
                'action'        => $this->action,       // Request对象
            ]);
        }
    }
    /**
     * 解析当前路由参数 （分组名称、控制器名称、方法名）
     */
    protected function getRouteinfo(){
        // 控制器名称
        $this->controller = toUnderScore($this->request->controller());
        // 方法名称
        $this->action = $this->request->action();
        // 控制器分组 (用于定义所属模块)
        $groupstr = strstr($this->controller, '.', true);
        $this->group = $groupstr !== false ? $groupstr : $this->controller;
        // 当前uri
        $this->routeUri = $this->controller . '/' . $this->action;
    }
    /**
     * 后台菜单配置
     * @return array
     */
    private  function menu(){
        $list = (new MenuModel)->getJsTree();
        foreach ($list as $k=>$v){
            if($v["pid"]=='0'){
                $list[$v["model"]]=$v;
                unset($list[$k]);
            }
        }
        return $list;
    }
    /**
     * 验证登录状态
     * @return bool
     */
    private function checkLogin()
    {
        // 验证当前请求是否在白名单
        if (in_array($this->routeUri, $this->allowAllAction)) {
            return true;
        }
		// 验证登录状态
        if (empty($this->dzcadmin)) {
            $this->redirect('Login/login',302);return false;
        }
		//验证auth
        $this->auth();
        return true;
    }
    /**
     * 获取当前app_id
     */
    protected function getAppId()
    {
        return $this->dzcadmin['app']['id'];
    }
    /**
     * 返回封装后的 API 数据到客户端
     * @param int $code
     * @param string $msg
     * @param string $url
     * @param array $data
     * @return array
     */
    protected function renderJson($code = 1, $msg = '', $url = '', $data = [])
    {
        return compact('code', 'msg', 'url', 'data');
    }
    /**
     * 返回操作成功json
     * @param string $msg
     * @param string $url
     * @param array $data
     * @return array
     */
    protected function renderSuccess($msg = 'success', $url = '', $data = [])
    {
        return $this->renderJson(1, $msg, $url, $data);
    }
    /**
     * 返回操作失败json
     * @param string $msg
     * @param string $url
     * @param array $data
     * @return array
     */
    protected function renderError($msg = 'error', $url = '', $data = [])
    {
        return $this->renderJson(0, $msg, $url, $data);
    }
    /**
     * 获取post数据 (数组)
     * @param $key
     * @return mixed
     */
    protected function postData($key){
        return $this->request->post($key . '/a');
    }

    // 更改数据展示隐藏
    protected function ishow_change($id,$tablename){
        if ($id == 0) { return $this->renderError('非法操作');  }

        $now = db($tablename)->where(['id' => $id])->value('ishow');
        $mod = db($tablename)->where(['id' => $id])->update(['ishow' => $now == 1 ? 2 : 1]);
        if ($mod) {
            // 日志

            return $this->renderSuccess('操作成功');
        } else {
            return $this->renderError('操作失败');
        }
    }

    // 删除数据
    protected function isdel_change($id,$tablename){
        if ($id == 0) { return $this->renderError('非法操作');  }
        $mod = db($tablename)->where(['id' => $id])->update(['isdel' => 2]);
        if ($mod) {
            // 日志

            return $this->renderSuccess('操作成功');
        } else {
            return $this->renderError('操作失败');
        }
    }
    // 更改排序
    protected function sort_change($id,$tablename){
        if ($id == 0) {
            return $this->renderError('非法操作');
        }
        $num = input('num/d',99,'intval');
        $num = $num == 0 ? 99 : $num;

        $mod = db($tablename)->where(['id' => $id])->update(['sort' => $num]);
        if ($mod) {
            // 日志

            return $this->renderSuccess('操作成功');
        } else {
            return $this->renderError('操作失败');
        }
    }

    // 更改备注信息
    protected function content_change($id,$tablename,$field,$comm){
        if ($id == 0) {
            return $this->renderError('非法操作');
        }
        $mod = db($tablename)->where(['id' => $id])->update([$field => $comm]);
        if ($mod) {
            // 日志

            return $this->renderSuccess('操作成功');
        } else {
            return $this->renderError('操作失败');
        }
    }
}