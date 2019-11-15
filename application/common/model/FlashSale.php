<?php
namespace app\common\model;

use DzcOrg\PageOrg;   // 分页
use think\Db;

/**
 * 抢购模型
 * Class Coupon
 * @package app\common\model
 */
class FlashSale extends BaseModel
{
    protected $shownum = 15;    // 每页展示数量

    // 获取抢购活动列表
    public function flashsale_list($page = 0, $url = '', $kwords = '')
    {
        $model = db('flash_sale');
        $where[] = ['isdel','=',1];

        if (!empty($kwords)) {
            if (is_array($kwords)) {
                // 名称
                if ($kwords['title']) {
                    $where[] = ['title', 'like', '%' . $kwords['title'] . '%'];
                }
                if ($kwords['goods_name']) {
                    $where[] = ['goods_name', 'like', '%' . $kwords['goods_name'] . '%'];
                }
                if ($kwords['status']) {
                    if($kwords['status'] ==1){//未开始
                        $where[] = ['startstamp', '>', time()];
                    }elseif ($kwords['status'] ==2){//进行中
                        $where[] = ['startstamp', '<=', time()];
                        $where[] = ['endstamp', '>=', time()];
                    }elseif ($kwords['status'] ==3){//已结束
                        $where[] = ['endstamp', '<', time()];
                    }

                }
            } else {
                $where[] = ['title', 'like', '%' . $kwords . '%'];
            }
        }

        $page = isset($page) ? intval($page) : 0;

        $fields = '';

        $sort = 'endstamp desc,id desc';


        $lists = $model->where($where)->order($sort)->page($page, $this->shownum)->field($fields)->select();
        $count = $model->where($where)->count(); //总计多少条
        $pagetotal = ceil($count / $this->shownum); //总共有几页
        $pageorg = new PageOrg($count, $page, $this->shownum);
        $pageshow = $pageorg->getpage_manage($page, $url);

        $return = array(
            'list' => $lists,           // 列表
            'pageshow' => $pageshow,    // 分页
            'count' => $count,          // 数据总数
            'pagetotal' => $pagetotal,  // 总页数
        );
        return  $return;
    }


    // 添加活动
    public function addfalshsale()
    {

        $goods_id = input('goods_id/d', 0, 'intval');
        $goods_data = Db::name('item')->where('goods_id',$goods_id)->find();
        if (!$goods_data){
            $this->error = '商品参数有问题';return false;
        }
        $add_data = [
            'title' => input('title/s', '', 'trim'),
            'goods_id' => $goods_id,
            'price' => input('price/s', '', 'trim'),
            'goods_num' => input('goods_num/d', 0, 'intval'),
            'buy_limit' => input('buy_limit/d',0 , 'intval'),
            'description' => input('description/s','' , 'intval'),

            'startstamp' => strtotime(input('startstamp/s', '', 'trim')),
            'endstamp' => strtotime(input('endstamp/s', '', 'trim')),
            'goods_name' => $goods_data['goods_name'],

            'addstamp' => time(),
            'modstamp' => time(),
        ];
        //  查商品
        $goods_count = $this->checkgoods($goods_id,$add_data['startstamp'],$add_data['endstamp']);
        if ($goods_count){
            $this->error = '该活动商品此时间内已参加其他活动';return false;
        }
        // 查库存
        if($add_data['goods_num'] > $goods_data['store_count']){
            $this->error = '该商品库存不足';return false;
        }

        $add = db('flash_sale')->insert($add_data);
        if ($add) {
            return true;
        } else {
            return false;
        }
    }
    // 获取所有在售商品
    public function getgoods()
    {
        $where[] = ['is_on_sale','=',1];
        $where[] = ['store_count','egt',1];
        $data = Db::name('item')->where($where)->field('goods_id,goods_name,store_count')->select();
        return $data;
    }


    // 获取单条
    public function get_one($id)
    {
        if ($id) {
            $res = db('flash_sale')->where(['id' => $id])->find();
            return $res;
        }
        $this->error = '参数错误';return false;
    }


    // 编辑
    public function modfalshsale($id)
    {
        // dump(input());die;

        $goods_id = input('goods_id/d', 0, 'intval');
        $goods_data = Db::name('item')->where('goods_id',$goods_id)->find();
        if (!$goods_data){
            $this->error = '商品参数有问题';return false;
        }
        $mod_data = [
            'title' => input('title/s', '', 'trim'),
            'goods_id' => $goods_id,
            'price' => input('price/s', '', 'trim'),
            'goods_num' => input('goods_num/d', 0, 'intval'),
            'buy_limit' => input('buy_limit/d',0 , 'intval'),
            'description' => input('description/s','' , 'intval'),

            'startstamp' => strtotime(input('startstamp/s', '', 'trim')),
            'endstamp' => strtotime(input('endstamp/s', '', 'trim')),
            'goods_name' => $goods_data['goods_name'],

            'modstamp' => time(),
        ];
        // 查商品
        $goods_count = $this->checkgoods($goods_id,$mod_data['startstamp'],$mod_data['endstamp'],$id);
        if ($goods_count){
            $this->error = '该活动商品此时间内已参加其他活动';return false;
        }
        // 查库存
        if($mod_data['goods_num'] > $goods_data['store_count']){
            $this->error = '该商品库存不足';return false;
        }

        $mod = db('flash_sale')->where(['id' => $id])->update($mod_data);
        if ($mod) {
            return true;
        } else {
            return false;
        }
    }

    //    结束活动
    public function end($id)
    {
        $data = $this->get_one($id);
        if(time() > $data['endstamp'] && $data['is_end'] == 1)
        {
            $this->error = '数据错误';return false;
        }
        $result = db('flash_sale')->where(['id'=>$id])->update(['endstamp'=>time(),'is_end'=>1,'modstamp'=>time()]);
        if ($result) {
            return  true;
        }else{
            return false;
        }
    }

    //    检测商品时间内是否重复参加
    public function checkgoods($goods_id,$start,$end,$id = 0)
    {
        $where[] = ['id','neq',$id];
        $where[] = ['goods_id','=',$goods_id];
        $where[] = ['startstamp','<=',$end];
        $where[] = ['endstamp','>=',$start];

        $goods_data = db('flash_sale')->where($where)->count();
        return $goods_data;
    }

    // api   检测活动是否已经结束
    public function isend($id)
    {
        $data = $this->get_one($id);
        if($data['endstamp'] < time()){
            $this->error = '该活动已经结束';return false;
        }
        return true;
    }

    // api   获取所有进行中的抢购活动
    public function get_all_ing()
    {
        $data = db('flash_sale')
                ->where('startstamp','<=',time())
                ->where('endstamp','>=',time())
                ->select();
        return $data;
    }
}