<?php
namespace app\api\controller;
use think\Db;   // 数据库

use app\api\controller\Islogin;
use app\common\model\App as AppModel;

use app\common\model\AppPrepayId as AppPrepayIdModel;
use app\common\library\wechat\WxPay;

use app\common\model\Course as cmodel;
use app\common\model\Order as ordermodel;
use app\common\model\Packs as packsmodel;       // 课程包

// use app\common\model\Cart as CartModel;

/**
 * 支付控制器
 */
class Pay extends Islogin
{
    public function initialize(){
        parent::initialize();
    }

    // 购买课程包
    public function paypacks(){
        $sid = input('sid/s','','trim');
        if($sid == ''){
            return ajax(301,'课程包编号为空');
        }
        $packs = db('packs')->where(['sid' => $sid])->find();
        if($packs['isdel'] == 2){
            return ajax(301,'课程包已删除');
        }
        // 查看是否购买过这个课程包
        $ishave = db('user_packs')->where(['uid'=>$this->user['uid'],'sid'=>$sid,'isuse'=>1,'ispast'=>1])->value('id');
        if($ishave){
            return ajax(301,'您已购买此课程包,请勿重复购买');
        }

        // 生成订单号 能找到课程包编号的 不生成订单
        $order = [
            'oid' => $sid.'_'.$this->user['uid'].'_'.substr(time(), -8),
            'price' => $packs['price'],
            // 'price' => 0.01
        ];

        // 统一下单API
        $wxConfig = AppModel::getAppCache();
        $WxPay = new WxPay($wxConfig);
        // echo base_url() . 'index.php/api/'. 'Wxnotify/callback_packs';die;

        $payment = $WxPay->unifiedorder($order['oid'], $this->user['openid_app'], $order['price'], 'Wxnotify/callback_packs');
        if($payment['code']){
            return ajax(501, $payment['mess']);
        }
        return ajax(200,'', $payment);
    }

    // 课程报名支付
    public function payorder()
    {
        $sid = input('sid/s', '', 'trim');
        if ($sid == '') {   return ajax(301, '课程编号为空');}
        // dump(input());die;

        $course = (new cmodel)->getcourse_detail($sid);
        $price = $course['price'];

        // 剩余席位
        $nownumber = $course['totnum'] - $course['nownum'];
        $number = input('number/s',1,'intval');
        if($nownumber < $number){
            return ajax(301,'课程剩余席位不足,无法预约');
        }
        if($number == 2){
            $price = $price*2;
        }
        // 优惠券
        $cpsid = input('cpsid/d',0,'intval');
        if($cpsid){
            $cpcomm = db('user_coupon')->where(['id' => $cpsid])->field('id,uid,name,manyuan,jianyuan')->find();
            if($cpcomm['uid'] != $this->user['uid']){
                return ajax(301,'代金券信息与用户信息不符');
            }
            if($course['price'] < $cpcomm['manyuan']){
                return ajax(301,'代金券条件不符,请核对');
            }
            $price = $price - $cpcomm['jianyuan'];
        }
        // 删除未付款订单 如果这个课程存在就返回代付款信息
        $oid = $this->temp_order($price, input());
        // 生成个未付款订单
        $order_data = [
            'oid' => $oid,
            'price' => $price
            // 'price' => 0.01
        ];
        
        // 统一下单API
        $wxConfig = AppModel::getAppCache();
        $WxPay = new WxPay($wxConfig);

        $payment = $WxPay->unifiedorder($order_data['oid'], $this->user['openid_app'], $order_data['price'], 'Wxnotify/callback');
        // dump($payment);die;
        
        if ($payment['code']) {
            return ajax(501, $payment['mess']);
        }
        return ajax(200, '', $payment);
    }

    // 生成临时订单
    private function temp_order($price,$data){
        $sid = input('sid/s', '', 'trim');
        // 查看是否有临时订单
        $temp = (new ordermodel)->get_temporder($this->user['uid'],$sid);
        if($temp){
            // 更新临时订单
            $temporder = (new ordermodel)->mod_temporder($this->user['uid'], $data, $price);
        }else{
            // 新增临时订单
            $temporder = (new ordermodel)->add_temporder($this->user['uid'], $data, $price);
        }
        if($temporder['code'] == 400){
            return ajax(400,'临时订单生成失败,请重试');
        }
        return $temporder['mess'];
    }

    // 报课课程 课程包抵扣
    public function payorder_packs(){
        $sid = input('sid/s','','trim');
        if($sid == ''){
            return ajax(301,'课程编号为空');
        }

        $packsid = input('packsid/s','','trim');
        if($packsid == ''){
            return ajax(301,'课程包编号为空');
        }

        // 查看课程包信息
        $packcomm = (new packsmodel)->getuserpacks_one($packsid); 
        if($packcomm == ''){
            return ajax(301,'课程包不存在');;
        }
        // 判断使用完这次是否课程为空

        $number = input('number/d',1,'intval'); 
        if($packcomm['hashour'] >= $packcomm['tothour']){
            return ajax(301, '课程包剩余课时不足,购买失败');
        }
        $residuehour = $packcomm['tothour'] - $packcomm['hashour'];
        // $residuehour = 12 - 10;
        if($number > $residuehour){
            return ajax(301, '课程包剩余课时不足,购买失败');
        }

        $course = (new cmodel)->getcourse_detail($sid);

        // 生成订单 课程包使用记录 个人课程包减课时 课程报名 课程更改报名人数
        
        // 开课时间戳 通知 改状态用
        $pastamp = strtotime(date("Y-m-d", $course['datestamp']) . ' ' . $course['stime']);
        // 订单信息
        $add_order_data = [
            'oid' => getonlynum(16),
            'uid' => $this->user['uid'],
            'sid' => $sid,
            'phone' => input('phone/s','','trim'),
            'number' => $number,
            'course_cid' => $course['cid'],
            'course_name' => $course['name'],
            'course_price' => $course['price'],
            'course_date' => $course['datestamp'],
            'course_stime' => $course['stime'],
            'course_etime' => $course['etime'],
            'pastamp' => $pastamp,
            'price' => 0,
            'addstamp' => time(),
            'modstamp' => time(),
            'paytype' => 3,
        ];

        // 课程包使用记录 log_packs
        $add_logpacks_data = [
            'uid' => $add_order_data['uid'],
            'oid' => $add_order_data['oid'],
            'packsid' => $packsid,
            'sid' => $packcomm['sid'],
            'cosid' => $sid,
            'name' => $packcomm['name'],
            'tothour' => $packcomm['tothour']-$packcomm['hashour'],
            'hashour' => $number,
            'stype' => 2,
            'addstamp' => time(),
        ];

        // 报名列表
        $add_course_list_data = [
            'sid' => $course['sid'],
            'uid' => $add_order_data['uid'],
            'oid' => $add_order_data['oid'],
            'number' => $add_order_data['number'],
            'phone' => $add_order_data['phone'],
            'stype' => 1,
            'addstamp' => time(),
        ];

        // 启动事务
        Db::startTrans();
        try {
            // 表前缀
            $prefix = config('database.prefix');

            // 添加订单
            Db::table($prefix . 'order')->insert($add_order_data);

            // 添加课程包使用记录
            Db::table($prefix . 'log_packs')->insert($add_logpacks_data);

            // 添加课程报名信息
            Db::table($prefix . 'course_list')->insert($add_course_list_data);

            // 课程更改报名人数
            Db::table($prefix . 'course')->where(['sid' => $sid])->setInc('nownum', $number);

            // 课程包已用完
            if ($packcomm['hashour'] + $number >= $packcomm['tothour']) { 
                Db::table($prefix . 'user_packs')->where(['id' => $packsid])->update(['hashour'=>$packcomm['hashour'] + $number,'modstamp' => time(), 'isuse' => 2]);
            }else{
                Db::table($prefix . 'user_packs')->where(['id' => $packsid])->update(['hashour'=>$packcomm['hashour'] + $number,'modstamp' => time()]);
            }

            // 提交事务
            Db::commit();

            return ajax(200);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
    }


//////////////////////

    /**
     * 订单确认-立即购买
     * @param $item_id
     * @param $goods_num
     * @param $item_sku_id
     * @param $coupon_id
     * @param string $remark
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function buyNow($item_id, $goods_num, $item_sku_id, $coupon_id = null, $remark = '', $prom = '', $other = '')
    {	
        // 商品结算信息
        $model = new OrderModel;
        $order = $model->getBuyNow($this->user, $item_id, $goods_num, $item_sku_id ,$prom,$other);
        if (!$this->request->isPost()) {
            return $this->renderSuccess($order);
        }
        if ($model->hasError()) {
            return $this->renderError($model->getError());
        }
        // 创建订单
        if ($model->createOrder($this->user['user_id'], $order, $coupon_id, $remark,$prom,$item_id,$other)) {
            // 发起微信支付
            return $this->renderSuccess([
                'payment' => $this->unifiedorder($model, $this->user),
                'order_id' => $model['order_id']
            ]);
        }
        $error = $model->getError() ?: '订单创建失败';
        return $this->renderError($error);
    }
    /**
     * 订单确认-购物车结算
     * @param string $cart_ids (支持字符串ID集)
     * @param $coupon_id
     * @param string $remark
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function cart($cart_ids, $coupon_id = null, $remark = '')
    {	
        // 商品结算信息
        $Card = new CartModel($this->user['user_id']);
        $order = $Card->getList($this->user, $cart_ids);
        if (!$this->request->isPost()){
            return $this->renderSuccess($order);
        }
        // 创建订单
        $model = new OrderModel;
        if ($model->createOrder($this->user['user_id'], $order, $coupon_id, $remark)) {
            // 移出购物车中已下单的商品
            $Card->clearAll($cart_ids);
            // 发起微信支付
            return $this->renderSuccess([
                'payment' => $this->unifiedorder($model, $this->user),
                'order_id' => $model['order_id']
            ]);
        }
        return $this->renderError($model->getError() ?: '订单创建失败');
    }
    /**
     * 构建微信支付
     * @param $order
     * @param $user
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    private function unifiedorder($order, $user)
    {
        // 统一下单API
        $wxConfig = AppModel::getAppCache();
        $WxPay = new WxPay($wxConfig);
        $payment = $WxPay->unifiedorder($order['order_no'], $user['open_id'], $order['pay_price']);
        // 记录prepay_id
        $model = new AppPrepayIdModel;
        $model->add($payment['prepay_id'], $order['order_id'], $user['user_id']);
        return $payment;
    }
	/**
     * 我的订单列表
     * @param $dataType
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function lists($dataType)
    {
        $model = new OrderModel;
        $list = $model->getList($this->user['user_id'], $dataType);
        return $this->renderSuccess(compact('list'));
    }
    /**
     * 订单详情信息
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function detail($order_id)
    {
        $order = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        return $this->renderSuccess(compact('order'));
    }
    /**
     * 获取物流信息
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function express($order_id)
    {
        // 订单信息
        $order = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        if (!$order['express_no']) {
            return $this->renderError('没有物流信息');
        }
        // 获取物流信息
        /* @var \app\store\model\Express $model */
        $model = $order['express'];
        $express = $model->dynamic($model['express_name'], $model['express_code'], $order['express_no']);
        if ($express === false) {
            return $this->renderError($model->getError());
        }
        return $this->renderSuccess(compact('express'));
    }
    /**
     * 取消订单
     * @param $order_id
     * @return array
     * @throws \Exception
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function cancel($order_id)
    {
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        if ($model->cancel()) {
            return $this->renderSuccess('收货成功');
        }
        return $this->renderError($model->getError());
    }
    /**
     * 确认收货
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function receipt($order_id)
    {
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        if ($model->receipt($this->user)) {
            return $this->renderSuccess();
        }
        return $this->renderError($model->getError());
    }
    /**
     * 立即支付
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function pay($order_id)
    {
        // 订单详情
        $order = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
		// 判断商品状态、库存
        if (!$order->checkStatusFromOrder($order['goods'])) {
            return $this->renderError($order->getError());
        }
        // 统一下单API
        $wxConfig = AppModel::getAppCache();
        $WxPay = new WxPay($wxConfig);
        $payment = $WxPay->unifiedorder($order['order_no'], $this->user['open_id'], $order['pay_price']);
        // 记录prepay_id
        $model = new AppPrepayIdModel;
        $model->add($payment['prepay_id'], $order['order_id'], $this->user['user_id']);
        return $this->renderSuccess($payment);
    }
}