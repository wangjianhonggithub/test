<?php
namespace app\api\controller;

use think\facade\Cache;

use app\common\model\App as appmodel;
use app\common\model\User as UserModel;
use app\common\model\Coupon as cpmodel;
use app\common\library\wechat\WxUser;

class Login extends Controller
{
    private $token;
    // private $gender = ['未知', '男', '女']; // 性别

    // 微信登录
    public function wxlogin()
    {   
        $umodel = new UserModel;
        $post = $this->request->post();
        // 微信登录 获取session_key
        $session = $this->getsession($post['code'], $post['app_id']);
        // dump($session);die;
        if($session == 400){    return ajax(500, 'session_key 获取失败');   }

        $uid = db('user')->where(['openid_app' => $session['openid']])->value('uid');
        if($uid){
            // 登录
            $user = $umodel->get_detail($uid, 'uid,openid_app,unionid,nickname,headimg,islock,lockstamp');
            if($session['unionid'] && $user['unionid'] == ''){
                // 关注公众号才有这个唯一标示
                // 保存起来
                // 获取一下子公众平台openid
                $openid_wx = db('user_wx')->where(['unionid'=>$session['unionid']])->value('openid_wx');
                db('user')->where(['uid' => $uid])->update(['openid_wx'=>$openid_wx,'unionid'=>$session['unionid']]);
            }
            
            // 判断是否锁定 锁定过期
            if($user['islock'] == 2){
                return ajax(301,'用户已被锁定,无法登陆');
            }
        }else{
            $userInfo = json_decode(htmlspecialchars_decode($post['user_info']), true);
            // 注册
            $add_data = [
                'uid' => $umodel->getuid(getonlynum()),
                'openid_app' => $session['openid'],
                'nickname' => preg_replace('/[\xf0-\xf7].{3}/', '', $userInfo['nickName']),
                'gender' => $userInfo['gender'],
                'headimg' => $userInfo['avatarUrl'],
                'country' => $userInfo['country'],
                'province' => $userInfo['province'],
                'city' => $userInfo['city'],
                'language' => $userInfo['language'],

                'addstamp' => time(),
                'modstamp' => time(),
                'fromtype' => 1,
            ];

            if($session['unionid']){
                // 关注公众号才有这个唯一标示
                $add_data['unionid'] = $session['unionid'];

                // 获取一下子公众平台openid
                $add_data['openid_wx'] = db('user_wx')->where(['unionid'=>$session['unionid']])->value('openid_wx');
            }
            
            $add = db('user')->insert($add_data);
            if(!$add){  return ajax(400,'用戶注册失败');}

            $uid = $add_data['uid'];

            // 新用户注册给优惠券
            (new cpmodel)->sendnewuser($uid);
        }

        // 生成token (session3rd)
        // 记录缓存, 7天
        $this->token = $this->token($session['openid']);
        Cache::set($this->token, $session, 86400 * 7);

        return ajax(200, $uid, $this->getToken());
    }

    // 微信解密 获取session
    private function getsession($code, $appid)
    {
        // 获取当前小程序信息
        $wxConfig = (new appmodel)->getAppCache($appid);
        // 微信登录 (获取session_key)
        $WxUser = new WxUser($wxConfig['appkey'], $wxConfig['app_secret']);
        if (!$session = $WxUser->sessionKey($code)){
            return 400;
        }
        return $session;
    }

    private function token($openid)
    {
        return md5($openid . $this->app_id . 'dzctoken');
    }

    public function getToken()
    {
        return $this->token;
    }
    
    

    /**
     * app/web/等手机号登录
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
	public function app_login()
    {
		
        $model = new UserModel;
		$user=$model->app_login($this->request->post());
		
		if($user){
			return $this->renderSuccess([
				'user_id' => $user,
				'token' => $model->getToken()
			]);
		}
		return $this->renderError('登录失败');
    }
	/*
	*app/web/等手机号注册
	* @return array
	*/
	public function app_reg(){
		$model = new UserModel;
        return $this->renderSuccess([
            'user_id' => $model->reg($this->request->post()),
            'token' => $model->getToken()
        ]);
	
	}
    

}
