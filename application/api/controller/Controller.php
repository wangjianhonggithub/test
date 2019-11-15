<?php
namespace app\api\controller;
use think\facade\Cache;
use app\common\model\User as UserModel;
use app\common\exception\BaseException;
use think\Controller as ThinkController;
use think\exception\HttpException;

/**
 * API控制器基类
 * Class BaseController
 * @package app\store\controller
 */

error_reporting(E_ERROR | E_PARSE);    // 设置报错级别 不加这个太严格了

class Controller extends ThinkController
{
    const JSON_SUCCESS_STATUS = 1;  // 成功返回
    const JSON_ERROR_STATUS   = 0;  // 失败返回  

    /* @ver $app_id 小程序id */
    protected $app_id;
    protected $dzcitem; // 项目标示 保留
    
    /**
     * 基类初始化
     * @throws BaseException
     */
    public function initialize()
    {
        $this->app_id = $this->getAppId();
        
        // 当前项目
        // $this->dzcitem = $this->getdzcitem();
    }
    // 当前项目
    private function getdzcitem()
    {
        if (!$dzcitem = $this->request->param('dzcitem')) {
            throw new BaseException(['msg' => '缺少参数,无法定位项目']);
        }
        if ($dzcitem != 'dzcyiranjianshen_xcx') {
            throw new BaseException(['msg' => '定位项目失败']);
        }
        return $dzcitem;
    }

    // 获取当前小程序ID
    private function getAppId(){
        if (!$app_id = $this->request->param('app_id')) {
            throw new BaseException(['msg' => '缺少必要的参数：app_id']);
        }
        return $app_id;
    }

    /**
     * 获取当前用户信息
     * @param bool $is_force
     * @return UserModel|bool|null
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    protected function getUser($is_force = true)
    {
        // if (!$token = $this->request->param('token')) {
        //     $is_force && $this->throwError('缺少必要的参数：token', -1);
        //     return false;
        // }
        // if (!$user = UserModel::getUser($token)) {
        //    $is_force && $this->throwError('没有找到用户信息', -1);
        //     return false;
        // }
        // return $user;
            
        // dump(Cache::get('a6ca605973bb3c61ebf37acb112b4c28')['openid']);
        // die;


        // if (!$token = $this->request->param('token')) {
        //     return 'notoken';die;
        // }
    }

    protected function checklogin(){
        // dump(input());
        $token = input('token/s','','trim');
        if ($token == '') {
            return 'notoken';
        }
        $openid =  Cache::get($token)['openid'];
        if($openid == ''){
            return 'nopenid';
        }
        $user = (new UserModel)->get_detail(['openid_app'=>$openid], 'uid,openid_app,openid_wx,unionid,nickname,gender,headimg,islock,lockstamp');
        if($user == ''){
            return 'nouser';
        }
        // 判断是否锁定
        if($user['islock'] == 2){
            return 'islock';
        }
        if($user['openid_wx'] == ''){
            $openid_wx = db('user_wx')->where(['unionid'=>$user['unionid']])->value('openid_wx');
            if($openid_wx){
                db('user')->where(['uid'=>$user['uid']])->update(['openid_wx'=>$openid_wx]);
            }
        }
        $this->user = $user;
    }
    /**
     * 输出错误信息
     * @param int $code
     * @param $msg
     * @throws BaseException
     */
    protected function throwError($mess, $code = 0)
    {
        throw new BaseException(['code' => $code, 'mess' => $mess]);
        // throw new \think\Exception($mess, $code);
        // exception('异常消息', 100006);
    }
    
    /**
     * 返回封装后的 API 数据到客户端
     * @param int $code
     * @param string $msg
     * @param array $data
     * @return array
     */
    protected function renderJson($code = self::JSON_SUCCESS_STATUS, $msg = '', $data = [])
    {
        return compact('code', 'msg', 'url', 'data');
    }
    /**
     * 返回操作成功json
     * @param string $msg
     * @param array $data
     * @return array
     */
    protected function renderSuccess($data = [], $msg = 'success')
    {
        return $this->renderJson(self::JSON_SUCCESS_STATUS, $msg, $data);
    }
    /**
     * 返回操作失败json
     * @param string $msg
     * @param array $data
     * @return array
     */
    protected function renderError($msg = 'error', $data = [])
    {
        return $this->renderJson(self::JSON_ERROR_STATUS, $msg, $data);
    }
    /**
     * 获取post数据 (数组)
     * @param $key
     * @return mixed
     */
    protected function postData($key)
    {
        return $this->request->post($key . '/a');
    }

}