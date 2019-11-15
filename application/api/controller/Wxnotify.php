<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use app\common\model\Log as logmodel;

use app\common\model\Order as ordermodel;
use app\common\model\App as AppModel;

use app\common\model\Integral;
/**
 * 支付成功异步通知接口
 * Class Notify
 * @package app\api\controller
 */
error_reporting(E_ERROR | E_PARSE );    // 报错级别

class Wxnotify extends Controller
{
    /**
     * 课程包支付成功异步通知
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function callback_packs()
    {
        if (!$xml = file_get_contents('php://input')) {
            $this->returnCode(false, 'Not found DATA');
        }
        // 将服务器返回的XML数据转化为数组
        $data = $this->fromXml($xml);

        (new logmodel)->payNotify($data);   // 记录回调信息 测试用

        $wxConfig = AppModel::getAppCache(10001);
        
        // 设置支付秘钥
        $this->config['apikey'] = $wxConfig['apikey'];
        // 保存微信服务器返回的签名sign
        $dataSign = $data['sign'];
        // sign不参与签名算法
        unset($data['sign']);
        // 生成签名
        $sign = $this->makeSign($data);
        // 判断签名是否正确  判断支付状态
         if (($sign === $dataSign) && ($data['return_code'] == 'SUCCESS') && ($data['result_code'] == 'SUCCESS')) { 

            $do = $this->packrun($data);
            if($do){
                $this->returnCode(true, 'OK');
            }else{
                $this->returnCode(fasle, '处理失败');
            }
        }
        // 返回状态
        $this->returnCode(false, '签名失败');
	}
    // 课程包回调处理
    public function packrun($data){
        // 订单编号
        $oid = $data['out_trade_no'];        // 订单
        $price = $data['total_fee'];         // 价钱
        $flowcode = $data['transaction_id']; // 流水单号

        $oidarr = explode('_',$oid);

        $sid = $oidarr[0];  // 课程包
        $uid = $oidarr[1];  // 会员编号

        $packcomm = db('packs')->where(['sid'=>$sid])->find();
        // 给用户添加课程包
        $add_userpack_data = [
            'uid' => $uid,
            'sid' => $sid,
            'cid' => $packcomm['cid'],
            'name' => $packcomm['name'],
            'price' => $packcomm['price'],
            'intro' => $packcomm['intro'],
            'tothour' => $packcomm['tothour'],
            'hashour' => 0,
            'validays' => $packcomm['validays'],
            'pastamp' => strtotime(get_ab_date(date("Y-m-d"), $packcomm['validays'])),
            'addstamp' => time(),
            'modstamp' => time(),
        ];

        // 记录支付
        $add_log_data = [
            'uid' => $uid,
            'oid' => $sid,
            'stype' => 1,
            'price' => $price,
            'flowcode' => $flowcode,
            'addstamp' => time(),
        ];

        // 用户课程包记录
        $add_packlog_data = [
            'uid' => $uid,
            'sid' => $sid,
            'name' => $packcomm['name'],
            'tothour' => $packcomm['tothour'],
            'stype' => 1,
            'addstamp' => time(),
        ];

        // 启动事务
        Db::startTrans();
        try{
            // 表前缀
            $prefix = config('database.prefix');

            // 给用户添加课程包 
            $add_userpack = Db::table($prefix.'user_packs')->insertGetId($add_userpack_data);
            
            // 记录支付
            Db::table($prefix.'log_pay')->insert($add_log_data);
       
            // 提交事务
            Db::commit();  

            $add_packlog_data['packsid'] = $add_userpack;
            Db::table($prefix.'log_packs')->insert($add_packlog_data);

            return true;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
    }

    /**
     * 课程支付成功异步通知
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
	public function callback()
	{
        if (!$xml = file_get_contents('php://input')) {
            $this->returnCode(false, 'Not found DATA');
        }
        // 将服务器返回的XML数据转化为数组
        $data = $this->fromXml($xml);

        (new logmodel)->payNotify($data);   // 记录回调信息 测试用

        $oid = $data['out_trade_no'];
        $temporder = db('order_temp')->where(['oid'=>$oid, 'stype'=>1])->find();
        if($temporder == ''){
             $this->returnCode(false, '订单不存在');
        }
        $wxConfig = AppModel::getAppCache(10001);
    
        // 设置支付秘钥
        $this->config['apikey'] = $wxConfig['apikey'];
        // 保存微信服务器返回的签名sign
        $dataSign = $data['sign'];
        // sign不参与签名算法
        unset($data['sign']);
        // 生成签名
        $sign = $this->makeSign($data);
        // 判断签名是否正确  判断支付状态
        if (($sign === $dataSign) && ($data['return_code'] == 'SUCCESS') && ($data['result_code'] == 'SUCCESS')) { 

            $do = $this->orderun($data);
            if($do){
                $this->returnCode(true, 'OK');
            }else{
                $this->returnCode(fasle, '处理失败');
            }
        }
        // 返回状态
        $this->returnCode(false, '签名失败');
	}

    // 订单处理
    public function orderun($data){
        // 订单编号
        $oid = $data['out_trade_no'];        // 订单
        $price = $data['total_fee'];         // 价钱
        $flowcode = $data['transaction_id']; // 流水单号

        $temporder = db('order_temp')->where(['oid'=>$oid, 'stype'=>1])->find();
        
        $uid = $temporder['uid'];

        $course = db('course')->where(['sid'=>$temporder['sid']])->find();

        // 开课时间戳 通知 改状态用
        $pastamp = strtotime(date("Y-m-d", $course['datestamp']).' '.$course['stime']);
        // 临时订单添加到正式订单
        $add_order_data = [
            'oid' => $oid,
            'uid' => $uid,
            'sid' => $temporder['sid'],
            'phone' => $temporder['phone'],
            'number' => $temporder['number'],
            'course_cid' => $course['cid'],
            'course_name' => $course['name'],
            'course_price' => $course['price'],
            'course_date' => $course['datestamp'],
            'course_stime' => $course['stime'],
            'course_etime' => $course['etime'],
            'pastamp' => $pastamp,
            'price' => $course['price'] * $temporder['number'],
            'addstamp' => time(),
            'modstamp' => time(),
            'paytype' => 1,
        ];

        // 是否使用优惠券
        if($temporder['cpsid']){
            $coupon = db('user_coupon')->where(['id'=>$temporder['cpsid']])->find();
            $add_order_data['cpsid'] = $coupon['id'];
            $add_order_data['cprice'] = $coupon['jianyuan'];
            $add_order_data['paytype'] = 2;

            // 订单价钱
            $add_order_data['price'] = $add_order_data['price'] - $coupon['jianyuan'];

            // 更改优惠券使用状态 记录优惠券使用记录
            $add_logcoupon_data = [
                'uid' => $uid,
                'oid' => $oid,
                'cpsid' => $temporder['cpsid'],
                'name' => $coupon['name'],
                'stype' => 2,
                'content' => '用户购买课程 '.$temporder['sid'].' 使用优惠券 '.$coupon['cpsid'],
                'addstamp' => time(),
            ];
        }

        // 添加支付记录
        $add_log_data = [
            'uid' => $uid,
            'oid' => $oid,
            'stype' => 2,
            'price' => $price,
            'flowcode' => $flowcode,
            'addstamp' => time(),
        ];

        // 添加报名列表
        $add_course_list_data = [
            'sid' => $course['sid'],
            'uid' => $uid,
            'oid' => $oid,
            'number' => $temporder['number'],
            'phone' => $temporder['phone'],
            'stype' => 1,
            'addstamp' => time(),
        ];
        
        // 启动事务
        Db::startTrans();
        try{
            // 表前缀
            $prefix = config('database.prefix');

            // 添加订单
            Db::table($prefix.'order')->insert($add_order_data);

            // 更改临时订单
            Db::table($prefix.'order_temp')->where(['oid'=>$oid])->update(['stype'=>2,'modstamp'=>time()]);
            
            // 记录支付
            Db::table($prefix.'log_pay')->insert($add_log_data);
            
            // 添加课程报名信息
            Db::table($prefix.'course_list')->insert($add_course_list_data);

            // 课程更改报名人数
            Db::table($prefix.'course')->where(['sid'=>$temporder['sid']])->setInc('nownum',$temporder['number']);
            
            // 提交事务
            Db::commit();
            // 是否使用优惠券
            if($temporder['cpsid']){
                // 更改优惠券使用状态 记录优惠券使用记录
                Db::table($prefix.'user_coupon')->where(['id'=>$temporder['cpsid']])->update(['modstamp'=>time(),'isuse'=>2]);
                Db::table($prefix.'log_coupon')->insert($add_logcoupon_data);
            }
            return true;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }

    }

    /**
     * 将xml转为array
     * @param $xml
     * @return mixed
     */
    private function fromXml($pay)
    {
        // 禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($pay, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }
	/**
     * 生成签名
     * @param $values
     * @return string 本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    private function makeSign($values)
    {
        //签名步骤一：按字典序排序参数
        ksort($values);
        $string = $this->toUrlParams($values);
        //签名步骤二：在string后加入KEY
        $string = $string . '&key=' . $this->config['apikey'];
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }
	/**
     * 格式化参数格式化成url参数
     * @param $values
     * @return string
     */
    private function toUrlParams($values)
    {
        $buff = '';
        foreach ($values as $k => $v) {
            if ($k != 'sign' && $v != '' && !is_array($v)) {
                $buff .= $k . '=' . $v . '&';
            }
        }
        return trim($buff, '&');
    }
	/**
     * 返回状态给微信服务器
     * @param boolean $return_code
     * @param string $msg
     */
    private function returnCode($return_code = true, $msg = null)
    {
        // 返回状态
        $return = [
            'return_code' => $return_code ? 'SUCCESS' : 'FAIL',
            'return_msg' => $msg ?: 'OK',
        ];
        // 记录日志
        log_write([
            'describe' => '返回微信支付状态',
            'data' => $return
        ]);
        die($this->toXml($return));
    }
    /**
     * 输出xml字符
     * @param $values
     * @return bool|string
     */
    private function toXml($values)
    {
        if (!is_array($values)
            || count($values) <= 0
        ) {
            return false;
        }
        $xml = "<xml>";
        foreach ($values as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }
}