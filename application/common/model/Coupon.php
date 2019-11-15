<?php
namespace app\common\model;

use think\Db;
use DzcOrg\PageOrg;   // 分页

/**
 * 优惠券模型
 * Class Coupon
 * @package app\common\model
 */
class Coupon extends BaseModel
{
    protected $shownum = 12;    // 每页展示数量

    // 获取优惠券列表
    public function getcoupon_list($page = 0, $url = '', $kwords = '')
    {
        $model = db('coupon');
        $where[] = ['isdel', '=', 1];

        if (!empty($kwords)) {
            if (is_array($kwords)) {
                // 分类
                if ($kwords['cid']) {
                    $kwords['cid'] = $kwords['cid'] == -1 ? 0 : $kwords['cid'];
                    $where[] = ['cid', '=', $kwords['cid']];
                }
                // 编号
                if ($kwords['sid']) {
                    $where[] = ['sid', 'like', '%' . $kwords['sid'] . '%'];
                }
                // 名称
                if ($kwords['name']) {
                    $where[] = ['name', 'like', '%' . $kwords['name'] . '%'];
                }
                // 注册赠送
                if ($kwords['isreget']) {
                    $where[] = ['isreget', '=', $kwords['isreget']];
                }
            } else {
                $where[] = ['name', 'like', '%' . $kwords . '%'];
            }
        }
        // dump($where);die;
        $page = isset($page) ? intval($page) : 0;

        $fields = '';
        // $sort = 'datestamp desc,stime asc,sort asc,id asc';
        $sort = 'sort asc,id asc';

        if ($page == 0) {
            $where[] = ['ishow', '=', 1];
            $fields =  $url == '' ? 'id,sid,cid,name' : $url;

            // 获取全部
            $lists = $model->where($where)->order($sort)->field($fields)->select();
            foreach ($lists as $k => $val) {
                if($val['cid'] == 0){
                    $lists[$k]['classname'] = '全部';
                }else{
                    $lists[$k]['classname'] = db('courseclass')->where(['id' => $val['cid']])->value('name');
                }
            }
            return $lists;
        }

        $lists = $model->where($where)->order($sort)->page($page, $this->shownum)->field($fields)->select();
        foreach ($lists as $k => $val) {
            if($val['cid'] == 0){
                $lists[$k]['classname'] = '全部';
            }else{
                $lists[$k]['classname'] = db('courseclass')->where(['id' => $val['cid']])->value('name');
            }
        }

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

    // 获取优惠券
    public function getcoupon_one($id)
    {
        if ($id) {
            return db('coupon')->where(['id' => $id])->find();
        }
    }

    // 添加优惠券
    public function addcoupon()
    {
        // dump(input());die;
        $add_data = [
            'sid' => getonlynum(),  // 唯一编号
            'name' => input('name/s', '', 'trim'),
            'cid' => input('cid/d', 0, 'intval'),
            'etype' => input('effect_type/d', 0, 'intval'),
            'effectstamp' => strtotime(input('effectstamp')),
            'loseefficacystamp' => strtotime(input('loseefficacystamp')),

            'isreget' => input('isreget/d', 1, 'intval'),
            'manyuan' => input('manyuan/d', 0, 'intval'),
            'jianyuan' => input('jianyuan/d', 0, 'intval'),
            'validays' => input('validays/d', 1, 'intval'),

            'intro' => input('intro/s', ''),
            
            'getcode' => getonlynum(16),

            'addstamp' => time(),
            'modstamp' => time(),
        ];
        // dump($add_data);die;
        $add = db('coupon')->insert($add_data);
        if ($add) {
            return true;
        } else {
            return false;
        }
    }
    // 编辑
    public function modcoupon($id)
    {
        // dump(input());die;

        $mod_data = [
            'name' => input('name/s', '', 'trim'),
            'cid' => input('cid/d', 0, 'intval'),
            'etype' => input('effect_type/d', 0, 'intval'),
            'effectstamp' => strtotime(input('effectstamp')),
            'loseefficacystamp' => strtotime(input('loseefficacystamp')),

            'isreget' => input('isreget/d', 1, 'intval'),
            'manyuan' => input('manyuan/d', 0, 'intval'),
            'jianyuan' => input('jianyuan/d', 0, 'intval'),
            'validays' => input('validays/d', 1, 'intval'),
            'intro' => input('intro/s', ''),

            'modstamp' => time(),
        ];

        // dump($mod_data);die;
        $mod = db('coupon')->where(['id' => $id])->update($mod_data);
        if ($mod) {
            return true;
        } else {
            return false;
        }
    }

    // 发放优惠券
    public function sendcoupon($uid='', $sid, $isnew=1){

        $coupon = db('coupon')->where(['sid'=>$sid,'isdel'=>1])->find();
        if($coupon == '' || $uid == ''){  return false;   }
        // 过期时间
        // 判断优惠券生效类型
        if ($coupon['etype'] == 2){
            $pastamp = strtotime(get_ab_date(date("Y-m-d"), $coupon['validays']));
            $validays = $coupon['validays'];
            $effectstamp = 0;
        }elseif ($coupon['etype'] == 1){
            $pastamp = $coupon['loseefficacystamp'];
            $time_diff = get_time_diff($coupon['effectstamp'], $coupon['loseefficacystamp']);
            $validays = $time_diff['day'];
            $effectstamp = $coupon['effectstamp'];
        }

        $add_data = [
            'uid' => $uid,
            'cpsid' => $coupon['sid'],
            'name' => $coupon['name'],
            'cid' => $coupon['cid'],
            'stype' => $coupon['stype'],
            'etype' => $coupon['etype'],
            'coupon_range' => $coupon['coupon_range'],
            'manyuan' => $coupon['manyuan'],
            'jianyuan' => $coupon['jianyuan'],
            'intro' => $coupon['intro'],
            'addstamp' => time(),
            'modstamp' => time(),
            'validays' => $validays,
            'pastamp' => $pastamp,
            'effectstamp' => $effectstamp,
        ];

        $add_data_log = [
            'uid' => $uid,
            'name' => $add_data['name'],
            'addstamp' => time()
        ];
        if($isnew == 1){
            // 管理员发放
            $add_data_log['stype'] = 3;
            // 获取管理员信息
            $dzcadmin = $_SESSION['think']['dzcadmin'];
            $add_data_log['adminid'] = $dzcadmin['user_id'];
            $add_data_log['content'] = $dzcadmin['real_name'].'(账号:'. $dzcadmin['user_name'].') 发放';
        }else{
            // 注册
            $add_data_log['stype'] = 1;
            $add_data_log['content'] = '新用户注册赠送';
        }
        // 启动事务
        Db::startTrans();
        try {
            // 表前缀
            $prefix = config('database.prefix');
            // 添加数据
            $cpsid = Db::table($prefix . 'user_coupon')->insertGetId($add_data);

            $add_data_log['cpsid'] = $cpsid;
            Db::table($prefix . 'log_coupon')->insert($add_data_log);
            
            // 提交事务
            Db::commit();
            return true;
            exit;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
    }
    // 给新注册用户发放优惠券
    public function sendnewuser($uid){
        // 判断用户是否领取过
        // $isget = db('log_coupon')->where(['uid'=>$uid,''])->value('id');

        // 新用户发送优惠券
        $newlist = $this->getcoupon_list(0,'sid',['isreget'=>2]);
        foreach($newlist as $val){
            $this->sendcoupon($uid,$val['sid'],2);
        }
        return true;
    }

    // 获取用户全部优惠券
    public function getuser_coupon_list($uid, $fields = 'uid,cpsid,name,cid', $kwords=''){
        // 删除过期七天的优惠券
        $this->coupon_del($uid);
        // 过期优惠券
        $this->coupon_past($uid);

        $model = db('user_coupon');
    
        $where[] = ['uid', '=', $uid];
        $where[] = ['isdel', '=', 1];
        $where[] = ['ispast', '=', 1];
        $where[] = ['isuse', '=', 1];

        if (!empty($kwords)) {
            if (is_array($kwords)) {
                // 分类
                if ($kwords['cid']) {
                    $where[] = ['cid', 'in', $kwords['cid']];
                }
                // 满足金额
                if ($kwords['manyuan']) {
                    $where[] = ['manyuan', '<=', $kwords['manyuan']];
                }
            } else {
                $where[] = ['name', 'like', '%' . $kwords . '%'];
            }
        }
        $sort = 'addstamp desc';
        // 获取全部
        $list = $model->where($where)->order($sort)->field($fields)->select();

        foreach($list as $k=>$val){
            if($val['cid'] == 0){
                $list[$k]['classname'] = '全部'; 
            }else{
                $list[$k]['classname'] = db('courseclass')->where(['id'=>$val['cid']])->value('name');
            }
            $list[$k]['pastdate'] = date('Y-m-d', $val['pastamp']);
        }
        // dump($list);die;
        return $list;
    }

    // 删除过期订单
    private function coupon_del($uid){
        $delwhere[] = ['uid', '=', $uid];
        $delwhere[] = ['ispast', '=', 2];
        $delwhere[] = ['pastamp', '<=', time() - (7 * 24 * 60 * 60)];
        db('user_coupon')->where($delwhere)->update(['isdel' => 2, 'modstamp' => time()]);
    }

    // 过期订单
    private function coupon_past($uid){
        $pastwhere[] = ['uid', '=', $uid];
        $pastwhere[] = ['pastamp', '<=', time()];
        db('user_coupon')->where($pastwhere)->update(['ispast' => 2, 'modstamp' => time()]);
    }

    // 获取用户优惠券记录
    public function getcoupon_loglist($page = 0, $url = '', $kwords = ''){
        $model = db('log_coupon');

        if (!empty($kwords)) {
            if (is_array($kwords)) {
                // 类型
                if ($kwords['stype']) {
                    $where[] = ['stype', '=', $kwords['stype']];
                }
                // 名称
                if ($kwords['name']) {
                    $where[] = ['name', 'like', '%' . $kwords['name'] . '%'];
                }
                // 管理员
                if ($kwords['adminid']) {
                    $where[] = ['adminid', '=', $kwords['adminid']];
                }
                // 注册赠送
                if ($kwords['uid']) {
                    $where[] = ['uid', '=', $kwords['uid']];
                }
            } else {
                $where[] = ['name', 'like', '%' . $kwords . '%'];
            }
        }
        
        // dump($where);die;
        
        $page = isset($page) ? intval($page) : 0;
        
        $fields = '';
        $sort = 'addstamp desc,id asc';

        if ($page == 0) {
            // $fields =  $url == '' ? 'id,sid,cid,name' : $url;
            $lists = $model->where($where)->order($sort)->select();
            return $lists;
        }

        $lists = $model->where($where)->order($sort)->page($page, $this->shownum)->field($fields)->select();
        $stypearr = [1=>'注册领取',2=>'客户使用',3=>'管理员发放'];

        foreach ($lists as $k => $val) {
            $lists[$k]['stype'] = $stypearr[$val['stype']];
            $lists[$k]['nickname'] = db('user')->where(['uid' => $val['uid']])->value('nickname');
        }
        // dump($lists);die;
        
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

}