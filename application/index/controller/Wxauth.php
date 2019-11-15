<?php
namespace app\index\controller;

error_reporting(E_ERROR | E_PARSE);    // 设置报错级别 不加这个太严格了

// 微信授权控制器
class Wxauth extends \think\Controller {
    public function __construct(){
        parent::__construct();

        // 公众平台秘钥  微信授权登录用
        $this->WX_AUTH_APP_ID_AUTH      = 'wx660bdd180966b788';
        $this->WX_AUTH_APP_KEY_AUTH     = '08282e586e0e1401c418b9c4c529c2c3';
        $this->WX_AUTH_CALLBACK_AUTH    = 'http://hotyiranjianshen.com/index.php?s=/index/Wxauth/wxcallback';
    }

    // 授权登录
    public function wxlogin(){
        // -------配置-------
        $AppID      = $this->WX_AUTH_APP_ID_AUTH;
        $AppSecret  = $this->WX_AUTH_APP_KEY_AUTH;
        $callback   =  $this->WX_AUTH_CALLBACK_AUTH; //回调地址

        // 微信登录
        // -------生成唯一随机串防CSRF攻击-------
        $state  = md5(uniqid(rand(), TRUE));
        $_SESSION['wx_state'] = $state; //存到SESSION

        $callback = urlencode($callback);
        // echo $callback;die();

        // 微信登录
        $wxurl="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$AppID."&redirect_uri=".$callback."&response_type=code&scope=snsapi_userinfo&state=".$state."#wechat_redirect";

        // echo $wxurl;die;
        $this->redirect($wxurl,301);
    }
    
    // 微信授权登录回调
    public function wxcallback(){

        $code   = input('code');
        $wxback = input('wxback/s');

        $AppID      = $this->WX_AUTH_APP_ID_AUTH;
        $AppSecret  = $this->WX_AUTH_APP_KEY_AUTH;
    
        $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$AppID.'&secret='.$AppSecret.'&code='.$code.'&grant_type=authorization_code';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        $json =  curl_exec($ch);
        curl_close($ch);
        
        //得到 access_token 与 openid
        $arr = json_decode($json,1);
        // dump($arr);die();  
        
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$arr['access_token'].'&openid='.$arr['openid'].'&lang=zh_CN';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        $json = curl_exec($ch);
        curl_close($ch);
        
        // 得到 用户资料
        $arr = json_decode($json,1);

        // dump($arr);die;
        // array(10) {
        //   ["openid"] => string(28) "onfBduJnLLBiIq9X67cykA3Ej2F0"
        //   ["nickname"] => string(6) "hehehe"
        //   ["sex"] => int(1)
        //   ["language"] => string(5) "zh_CN"
        //   ["city"] => string(9) "哈尔滨"
        //   ["province"] => string(9) "黑龙江"
        //   ["country"] => string(6) "中国"
        //   ["headimgurl"] => string(129) "http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eo0wns9M6S3C79BunHmia0R21fiaw1kf9zMjvoGRadUEogzQ7yh1b24xvNov2KkyeYOrknmGODoqMsg/132"
        //   ["privilege"] => array(0) {
        //   }
        //   ["unionid"] => string(28) "oc5fiv9O38wkdes-P4onLIH2qM6g"
        // }
    
        $isdup = db('user_wx')->where(['unionid'=>$arr['unionid']])->find();
        if($isdup == ''){
            $add_data = [
                'unionid' => $arr['unionid'],
                'openid_wx' => $arr['openid']
            ];
            db('user_wx')->insert($add_data);
        }
        // 跳转页面
        $this->redirect(url('authsuccess'),301);
    }
    // 成功展示页面
    public function authsuccess(){
        // 不走layout模板
        $this->view->engine->layout(false);
        return $this->fetch();
    }
}