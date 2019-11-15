<?php
namespace app\index\controller;

use app\common\model\Wechat as wxmodel;
use app\common\model\Order as ordermodel;
use app\common\model\Setting as setmodel;
use app\common\model\Log as logmodel;

error_reporting(E_ERROR | E_PARSE);    // 设置报错级别 不加这个太严格了
/**
 * 发送消息控制器
 */
class Send  extends \think\Controller
{
	// 上课提醒
	public function classremind(){
		set_time_limit(0);
    	$mess = input('mess/s','','trim');
    	if($mess == '' || $mess != 'asdasdxiakeasdasd'){
    		// 记录回调信息 测试用
    		(new logmodel)->attendclasslog('mess error');die;
    	}

		// 获取一小时之内上课的订单
		$list = $this->getorder_list();

        if(!empty($list)){

	        foreach($list as $val){

	        	$coucomm = db('course')->where(['sid'=>$val['sid']])->field('name,datestamp,stime,etime,coid,cid')->find();
				$coucomm['classname'] = db('courseclass')->where(['id' => $coucomm['cid']])->value('name');
				// $coucomm['coname'] = db('coach')->where(['id' => $coucomm['coid']])->value('name');
				$coucomm['datestamp'] = date("Y-m-d", $coucomm['datestamp']);
				$coucomm['stime'] = substr($coucomm['stime'], 0, 5);
				$coucomm['etime'] = substr($coucomm['etime'], 0, 5);

				// dump($coucomm);die;

	        	$userids = db('user')->where(['uid'=>$val['uid']])->field('openid_wx,unionid')->find();
	        	$openid = $userids['openid_wx'];
	        	if($openid == ''){
	        		$openid = db('user_wx')->where(['unionid'=>$userids['unionid']])->value('openid_wx');
	        		if($openid == ''){
	        			continue;
	        		}
	        	}

	  			$sendrun = $this->sendmessage(1,$openid,$coucomm);
	  			// dump($sendrun);
	  			if($sendrun['errmsg'] == 'ok'){
	  				// 更改订单通知状态
	  				db('order')->where(['oid'=>$val['oid']])->update(['isend'=>2]);
	  			}
	        }
        	(new logmodel)->attendclasslog('success');   
        }else{
        	(new logmodel)->attendclasslog('no data');   
        }
    }

	// 下课
    public function classover(){
    	set_time_limit(0);

    	$mess = input('mess/s','','trim');
    	if($mess == '' || $mess != 'asdasdxiakeasdasd'){
    		return ajax(500,'非法操作');
    	}

    	$sid = input('sid/s','','trim');
    	if($sid == ''){
    		return ajax(400,'课程编号为空');
    	}
        // 获取该课程的全部订单
        $list = $this->getorder_list($sid);

        if(!empty($list)){
        	$coucomm = db('course')->where(['sid'=>$sid])->field('name,datestamp,stime,etime,coid,cid')->find();
			$coucomm['classname'] = db('courseclass')->where(['id' => $coucomm['cid']])->value('name');
			// $coucomm['coname'] = db('coach')->where(['id' => $coucomm['coid']])->value('name');
			$coucomm['datestamp'] = date("Y-m-d", $coucomm['datestamp']);
			$coucomm['stime'] = substr($coucomm['stime'], 0, 5);
			$coucomm['etime'] = substr($coucomm['etime'], 0, 5);

	        foreach($list as $val){
	        	$userids = db('user')->where(['uid'=>$val['uid']])->field('openid_wx,unionid')->find();
	        	$openid = $userids['openid_wx'];
	        	if($openid == ''){
	        		$openid = db('user_wx')->where(['unionid'=>$userids['unionid']])->value('openid_wx');
	        		if($openid == ''){
	        			continue;
	        		}
	        	}

	  			$this->sendmessage(2,$openid,$coucomm);
	        }
	        return ajax(200);
        }else{
        	return ajax(400,'没有数据');
        }
    }

    // 获取订单列表
    public function getorder_list($sid=0){
        // 更新订单状态
        (new ordermodel)->order_past('');

        $model = db('order');
        $where[] = ['isdel', '=', 1];        	

        if($sid == 0){
	        // 获取一个小时之内开课的订单 上课提醒
	     	$pastamp = time()+(3600*1);
	        $where[] = ['pastamp', '<', $pastamp];
	        $where[] = ['stype', '=', 1];
	        $where[] = ['isend', '=', 1];
        }else{
        	// 获取指定课程的订单 下课
	        $where[] = ['sid', '=', $sid];
        }

        // dump($where);die;

        // 获取全部
        $list = $model
        		->where($where)
        		->field('oid,uid,sid')
        		->select();
        return $list;
    }

    // stype 1上课 2下课 $openid coucomm 课程详情
    public function sendmessage($stype,$openid,$coucomm=''){

      	// 定义模板消息
      	switch ($stype) {
      		case 1:
      			$address = (new setmodel)->getItem('website')['address'];
      			// 上课
      			$postdata = [
			        'touser' 		=> $openid,
			        'template_id' 	=> 'sDhOVTzpiJBYwgs7ljBet5TG9YDgfSAI5H54DVXLMsM', // 模板编号
			        // 'url' 			=> 'https://app.tjxfit.com/', // 点击链接

		        	// 'miniprogram'	=> [	// 小程序
		        	// 	'appid' 	=> db('app')->where(['app_id'=>10001])->value('appkey'),
		        	// 	'pagepath'	=> 'Order/Order',	// 路径
		        	// ],
		       
		        	'data' 	=> [
		        		'first' => [
		                    'value' => '您预约的课程将在一个小时内开始,快准备一下去在训练吧',
		                    // 'color' => '#e93425',
		        		],
		        		'keyword1' => [
		                    'value' => $coucomm['name'],
		        		],
		        		'keyword2' => [
		                    'value' => $coucomm['datestamp'].' '.$coucomm['stime'],
		        		],
		        		'keyword3' => [
		                    'value' => $address?$address:'北京市丰台区西马场南里二区4号楼底商',
		        		],
		        		'remark' => [
		                    // 'value' => '',
		                    // 'color'=>'#e93425',
		        		]
		        	],
		        ];
      			break;
      		case 2:
		        // 下课
		        $postdata = [
			        'touser' 		=> $openid,
			        'template_id' 	=> '-bx0B1C0t7TrPkljfV3JVQe3oJhm2WjCKwNG01Wh-Vs', // 模板编号
			        'url' 			=> 'https://app.tjxfit.com/', // 点击链接

		        	// 'miniprogram'	=> [	// 小程序
		        	// 	'appid' 	=> '',
		        	// 	'pagepath'	=> '',	// 路径
		        	// ],
		       
		        	'data' 	=> [
		        		'first' => [
		                    'value' => '恭喜您完成了本次课程',
		                    // 'color' => '#e93425',
		        		],
		        		'keyword1' => [
		                    'value' => $coucomm['classname'].' '.$coucomm['name'],
		        		],
		        		'keyword2' => [
		                    'value' => $coucomm['datestamp'].' '.$coucomm['stime'].'-'.$coucomm['etime'],
		        		],
		        		'remark' => [
		                    'value' => '您的运动报告已生成，请点击查看',
		                    'color'=>'#e93425',
		        		]
		        	],
		        ];
      			break;
      		
      		default:
      			# code...
      			break;
      	}
      	// dump($postdata);die();
        $postdata = json_encode($postdata);
        
        $wxmodel = new wxmodel();
        $access_token = $wxmodel->tokeniset();
        
        // 调用地址
        $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=";
        $comm = $wxmodel->getpostapi($access_token,$url,$postdata);
        $json2arr = json_decode($comm,true);

        return $json2arr;
    }

}
