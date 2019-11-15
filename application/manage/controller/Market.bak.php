<?php

namespace app\manage\controller;

use app\common\model\Assemble as AssembleModel;
use app\common\model\Robbuy as RobbuyModel;
use app\common\model\Coupon as CouponModel;
use app\common\model\Promorder as PromorderModel;
use app\common\model\UserCoupon as UserCouponModel;
use app\common\model\Turntable as TurntableModel;

use app\common\model\Packs as pmodel;
use app\common\model\Course as cmodel;

/**
 * 优惠券管理
 * Class Coupon
 * @package app\manage\controller\market
 */
class Market extends Controller
{
    /* @var CouponModel $model */
    private $model;
    /**
     * 构造方法
     */
    public function initialize()
    {
        parent::initialize();
    }
    /**
     * 优惠券列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function assemble($status = null, $category_id = null, $name = '')
    {
        $model = new AssembleModel;
        $list = $model->getList($status, $category_id, $name, 2);
        return $this->fetch('market/assemble/index', compact('list', 'catgory'));
    }

    /**
     * 删除
     * @param $id
     * @param $table
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function delete($id = '', $table = '')
    {
        $model = "app\\common\\model\\" . $table;
        if (!(new $model)->destroy($id)) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }
    /**
     * 领取记录
     * @param $coupon_id
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function receive($coupon_id = null)
    {
        $model = new UserCouponModel;
        $list = $model->getList($coupon_id);
        return $this->fetch('receive', compact('list'));
    }


    /**
     * 优惠券列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function coupon()
    {
        $list = (new CouponModel)->getList();
        return $this->fetch('market/coupon/index', compact('list'));
    }

    /**
     * 更新优惠券
     * @param $robbuy_id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function editRobbuy($robbuy_id = '')
    {
        $robbuy = new RobbuyModel();
        if (!$this->request->isAjax()) {
            $model = $robbuy->detail($robbuy_id);
            return $this->fetch('market/robbuy/edit', compact('model'));
        }
        $data = $this->postData('robbuy');
        if ($data['robbuy_id'] ? $robbuy->edit($data) : $robbuy->add($data)) {
            return $this->renderSuccess('更新成功', url('market/robbuy'));
        }
        return $this->renderError($robbuy->getError() ?: '更新失败');
    }

    /**
     * 更新优惠券
     * @param $prom_id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function editPromorder($prom_id = '')
    {
        $promorder = new PromorderModel;
        if (!$this->request->isAjax()) {
            $model = $promorder::detail($prom_id);
            $parse_type = array('0' => '直接打折', '1' => '减价优惠', '2' => '固定金额出售', '3' => '买就赠优惠券');
            $coupon = (new CouponModel)->getList();
            return $this->fetch('market/order/edit', compact('model', 'parse_type', 'coupon'));
        }
        $data = $this->postData('prom');
        if ($prom_id ? $promorder->edit($data) : $promorder->add($data)) {
            return $this->renderSuccess('更新成功', url('market/promorder'));
        }
        return $this->renderError($promorder->getError() ?: '更新失败');
    }
    /**
     * 更新优惠券
     * @param $coupon_id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function editCoupon($coupon_id = '')
    {
        // 优惠券详情
        $coupon = new CouponModel;
        if (!$this->request->isAjax()) {
            $model = $coupon::detail($coupon_id);
            return $this->fetch('market/coupon/edit', compact('model'));
        }
        // 更新记录
        $data = $this->postData('coupon');
        if ($coupon->edit($data)) {
            return $this->renderSuccess('更新成功', url('market/coupon'));
        }
        return $this->renderError($coupon->getError() ?: '更新失败');
    }
    /**
     * 领取记录
     * @param $coupon_id
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function receiveCoupon($coupon_id = null)
    {
        $model = new UserCouponModel;
        $list = $model->getLists($coupon_id);
        return $this->fetch('market/coupon/receive', compact('list'));
    }

    /**
     * 查询活动上下架
     * @param $id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function numturntable($id)
    {
        $model = new TurntableModel;
        return $model->numturntable($id);
    }



    // 课程包管理
    public function packslist()
    {
        $para = '';
        $cid = input('cid/d', 0, 'intval');
        if ($cid) {
            $para['cid'] = $cid;
        }

        $sid = input('sid/s', '', 'trim');
        if ($sid) {
            $para['sid'] = $sid;
        }

        $name = input('name/s', '', 'trim');
        if ($name) {
            $para['name'] = $name;
        }
        $this->assign('para', $para);


        // 获取课程分类
        $classlist = (new cmodel)->getclass_list(0);
        $this->assign('classlist', $classlist);

        $page = input('page/d', 1, 'intval');
        $data = (new pmodel)->getpacks_list($page, url('packslist', $para), $para);

        // dump($data);die;
        return $this->fetch('market/packs/list', compact('data'));
    }
    public function packsrun()
    {
        $id = input('id/d', 0, 'intval');
        $pmodel = new pmodel;
        if (!$this->request->isAjax()) {
            if ($id) {
                $comm = $pmodel->getpacks_one($id);
                $this->assign('comm', $comm);
            }
            // 获取课程分类
            $classlist = (new cmodel)->getclass_list(0);
            $this->assign('classlist', $classlist);

            return $this->fetch('market/packs/run');
        }

        // dump(input());die;
        if ($id ? $pmodel->modpacks($id) : $pmodel->addpacks()) {
            return $this->renderSuccess('更新成功', url('packslist'));
        }
        $error = $pmodel->getError() ?: '更新失败';
        return $this->renderError($error);
    }
    // 排序
    public function packsort()
    {
        $id = input('id/d', 0, 'intval');
        return $this->sort_change($id, 'packs');
    }
    // 展示隐藏
    public function packshow()
    {
        $id = input('id/d', 0, 'intval');
        return $this->ishow_change($id, 'packs');
    }
    // 删除
    public function packsdel()
    {
        $id = input('id/d', 0, 'intval');
        return $this->isdel_change($id, 'packs');
    }
}
