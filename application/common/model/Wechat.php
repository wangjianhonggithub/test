<?php
namespace app\common\model;
use think\Model;
/**
 *公众号操作类
 *@param
 */
class Wechat extends Model{
	public function tokeniset(){
		// 公众号秘钥
		$res = $this->gettoken('wx660bdd180966b788','08282e586e0e1401c418b9c4c529c2c3');
		
		// 开放平台秘钥 网站应用
		// $res=$this->gettoken('wxc0fedf6ac969fda9','a87aa819ba894010e986a8d551f4fb50');

		$resinfo = json_decode($res,true);
		$access_token = $resinfo['access_token'];
		
		return $access_token;
		

		// 放入数据库
		$wxtoken=db("wxtoken")->where("id","=",1)->find();
		if(time()>$wxtoken['edates']){
			$res=$this->gettoken('wx07f460a2c626205f','8fe435a0f67be9baa169b0dbaf642443');
			$resinfo=json_decode($res,true);
			$access_token=$resinfo['access_token'];
			$save['access_token']=$access_token;
			$save['edates']=time()+7000;
			db("wxtoken")->where("id","=",1)->update($save);
		}else{
			$access_token=$wxtoken['access_token'];
		}
		return $access_token;
	}
	
	/**
	 * 获取access_token
	 * @param  [type] $appid     [description]
	 * @param  [type] $appsecret [description]
	 * @return [type]            [description]
	 */
	public function gettoken($appid,$appsecret)
	{
		$curl = curl_init();
        $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
        curl_setopt($curl, CURLOPT_URL, $url);         
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($curl);
        curl_close($curl);
        return  $data;
	}

	/**
	 * 微信登录获取access_token
	 * @param  [type] $appid  [description]
	 * @param  [type] $secret [description]
	 * @param  [type] $code   [description]
	 * @return [type]         [description]
	 */
	public function login2token($appid,$secret,$code)
	{
		$curl = curl_init();
        $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$secret}&code={$code}&grant_type=authorization_code";
        curl_setopt($curl, CURLOPT_URL, $url);         
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($curl);
        curl_close($curl);
        return  $data;
	}

	/**
	 * openid获取用户信息
	 * @return [type] [description]
	 */
	public function openid2info($access_token,$openid)
	{
		$curl = curl_init();
        $url="https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        curl_setopt($curl, CURLOPT_URL, $url);         
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($curl);
        curl_close($curl);
        return  $data;
	}

	/**
	 * 所有关注的用户列表
	 * @param  [type] $access_token [description]
	 * @param  [type] $nextopenid   [description]
	 * @return [type]               [description]
	 */
	public function getalluser($access_token,$nextopenid)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$access_token.'&next_openid='.$nextopenid);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$data = curl_exec($curl);
		curl_close($curl);
		$userlist=json_decode($data);
		$alluser=$userlist->data->openid;
		return $alluser;
	}

	/**
	 * 提交post接口
	 * @param  [type] $access_token [description]
	 * @param  [type] $url          [description]
	 * @param  [type] $postdata     [description]
	 * @return [type]               [description]
	 */
	public function setindustry($access_token,$url,$postdata){
		$curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url.$access_token); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata); // Post提交的数据包
        $res = curl_exec($curl); // 执行操作
        curl_close($curl); // 关闭CURL会话
        return $res; // 返回数据，json格式
	}

	public function getpostapi($access_token,$url,$postdata){
		$curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url.$access_token); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata); // Post提交的数据包
        $res = curl_exec($curl); // 执行操作
        curl_close($curl); // 关闭CURL会话
        return $res;
	}

	/**
	 * 获取get接口信息
	 * @param  [type] $url [description]
	 * @return [type]      [description]
	 */
	public function getinfo($url){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$data = curl_exec($curl);
		curl_close($curl);
		$res=json_decode($data);
		return $res;
	}

	/**
	 * 消息群发接口
	 * @param  [type] $userlist [description]
	 * @param  [type] $content  [description]
	 * @return [type]           [description]
	 */
	public function msg2users($access_token,$userlist,$content)
	{
		$users=json_encode($userlist);
		$text='{
				   "touser":'.$users.',
				    "msgtype": "text",
				    "text": { "content": "'.$content.'"}
				}';

		$url = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=".$access_token;
	    // $postData = http_build_query($text); //做一层过滤
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL,            $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
	    curl_setopt($ch, CURLOPT_POST,           1 );
	    curl_setopt($ch, CURLOPT_POSTFIELDS,     $text );
	    $result=curl_exec ($ch);
	    curl_close($ch);
	    dump($result);exit;
	}

	/**
	 * 对象 转 数组
	 * @param object $obj 对象
	 * @return array
	 */
	function object_to_array($obj) {
	    $obj = (array)$obj;
	    foreach ($obj as $k => $v) {
	        if (gettype($v) == 'resource') {
	            return;
	        }
	        if (gettype($v) == 'object' || gettype($v) == 'array') {
	            $obj[$k] = (array)object_to_array($v);
	        }
	    }
	 
	    return $obj;
	}
}