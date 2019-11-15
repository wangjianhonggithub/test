<?php
namespace app\common\model;
use think\facade\Request;
use think\facade\Cache;
use DzcOrg\PageOrg;   // 分页
/**
 * 分销商模型
 * Class Item
 * @package app\common\model
 */
class Distributions extends BaseModel
{
    public function get_all_data($page = 0, $url = '')
    {
        $page = isset($page) ? intval($page) : 0;
        $pageshow = 10;
        $order = "level_type asc,id desc";

        $lists = db('distributor_grade')->where('isdel','=',1)->order($order)->page($page, $pageshow)->select();
        $count = db('distributor_grade')->where('isdel','=',1)->count(); //总计多少条
        $pagetotal = ceil($count / $pageshow); //总共有几页
        $pageorg = new PageOrg($count, $page, $pageshow);
        $pageshow = $pageorg->getpage_manage($page, $url);

        $return = array(
            'list' => $lists,           // 列表
            'pageshow' => $pageshow,    // 分页
            'count' => $count,          // 数据总数
            'pagetotal' => $pagetotal,  // 总页数
        );
        return  $return;
    }
    //    获取单条
    public function get_one($id)
    {
        return db('distributor_grade')->where('id',$id)->where('isdel',1)->find();
    }
    // 添加操作
    public function add_grade()
    {

        $add_data = [
            'level_name' => input('level_name/s', '', 'trim'),
            'level_type' => input('level_type/d', 0, 'intval'),
            'rate1' => input('rate1/d',0 , 'intval'),
            'rate2' => input('rate2/d',0 , 'intval'),
            'rate3' => input('rate3/d',0 , 'intval'),
            'introduction' => input('introduction/s','' , 'intval'),

            'join_price' => input('join_price/d',0 , 'intval'),
            'reward' => input('reward/d',0 , 'intval'),
            'order_money' => input('order_money/d',0 , 'intval'),

            'level_intro' => input('level_intro/s', '', 'trim'),


            'addstamp' => time(),
            'modstamp' => time(),
        ];
        if($add_data['rate1']+$add_data['rate2']+$add_data['rate3'] > 100){
            $this->error = '三级佣金比例之和不得超过100';return false;
        }

        if(!$this->check_level($add_data['level_type'])){
            $this->error = '分销等级与现有的重复';return false;
        }


        $add = db('distributor_grade')->insert($add_data);
        if ($add) {
            return true;
        } else {
            return false;
        }
    }

    // 修改操作
    public function mod_grade($id)
    {
        $data = $this->get_one($id);
        if(!$data || empty($id)){
            $this->error = '数据错误';return false;
        }

        $mod_data = [
            'level_name' => input('level_name/s', '', 'trim'),
            'level_type' => input('level_type/d', 0, 'intval'),
            'rate1' => input('rate1/d',0 , 'intval'),
            'rate2' => input('rate2/d',0 , 'intval'),
            'rate3' => input('rate3/d',0 , 'intval'),
            'introduction' => input('introduction/s','' , 'intval'),

            'join_price' => input('join_price/d',0 , 'intval'),
            'reward' => input('reward/d',0 , 'intval'),
            'order_money' => input('order_money/d',0 , 'intval'),

            'level_intro' => input('level_intro/s', '', 'trim'),


            'modstamp' => time(),
        ];
        if($mod_data['rate1']+$mod_data['rate2']+$mod_data['rate3'] > 100){
            $this->error = '三级佣金比例之和不得超过100';return false;
        }

        if(!$this->check_level($mod_data['level_type'],$id)){
            $this->error = '分销等级与现有的重复';return false;
        }


        $mod = db('distributor_grade')->where('id',$id)->update($mod_data);
        if ($mod) {
            return true;
        } else {
            return false;
        }
    }

    //   判断分销等级是否存在
    public function check_level($level,$id = 0)
    {
        $where[] = ['level_type','=',$level];
        $where[] = ['id','neq',$id];
        $where[] = ['isdel','=',1];
        $data = db('distributor_grade')->where($where)->select();
        if ($data){
            return false;
        }
        return true;
    }

    //    获取分销商列表
    public function get_all_user($page = 0, $url = '', $kwords = '')
    {
        if (!empty($kwords)) {
                // 微信名称
                if ($kwords['nickname']) {
                    $where[] = ['nickname', 'like', '%' . $kwords['nickname'] . '%'];
                }
                //  分销商等级
                if ($kwords['level']) {
                    $where[] = ['lid', '=', $kwords['level']];
                }

        }
        $page = isset($page) ? intval($page) : 0;
        $pageshow = 10;
        $order = "u.become_time desc";
        $where[] = ['is_grade','=',2];
        $field = 'u.id,u.uid,u.nickname,u.is_grade,u.commission,dg.level_name,u.become_time,u.parent_id,dg.id as lid';

        $lists = db('user')
                    ->alias('u')
                    ->join('distributor_grade dg','dg.id = u.lid','left')
                    ->order($order)
                    ->page($page, $pageshow)
                    ->where($where)
                    ->field($field)
                    ->select();
        foreach ($lists as $key => $value){
            if ($value['parent_id']){
                $lists[$key]['directly_online'] = db('user')->where('id',$value['parent_id'])->value('nickname');
            }else{
                $lists[$key]['directly_online'] = '无';
            }
            //   一级会员总数
            $lists[$key]['user1'] = db('user')->where('parent_id',$value['id'])->count();
            //    二级会员总数
            $data2 = db('user')->where('parent_id',$value['id'])->select();
            $lists[$key]['user2'] = 0;
            $lists[$key]['user3'] = 0;
            foreach ($data2 as $k=>$v){
                $lists[$key]['user2'] += db('user')->where('parent_id',$v['id'])->count();

            //   三级会员数
                $data3 = db('user')->where('parent_id',$v['id'])->select();

                foreach ($data3 as $ks=>$vs){
                    $lists[$key]['user3'] += db('user')->where('parent_id',$vs['id'])->count();
                }
            }

            $lists[$key]['user'] = $lists[$key]['user1']+$lists[$key]['user2']+$lists[$key]['user3'];

        }

        $count = db('user')->where($where)->count(); //总计多少条
        $pagetotal = ceil($count / $pageshow); //总共有几页
        $pageorg = new PageOrg($count, $page, $pageshow);
        $pageshow = $pageorg->getpage_manage($page, $url);

        $return = array(
            'list' => $lists,           // 列表
            'pageshow' => $pageshow,    // 分页
            'count' => $count,          // 数据总数
            'pagetotal' => $pagetotal,  // 总页数
        );
        return  $return;
    }

    //  获取分销商等级不带分页
    public static function serach_level()
    {
        return db('distributor_grade')->where('isdel',1)->field('id,level_name')->select();
    }

    //   获取分成日志
    public function commission_list($page = 0, $url = '', $kwords = '')
    {
        $where = [];
        if (!empty($kwords)) {
            // 获佣用户id
            if ($kwords['uid']) {
                $where[] = ['cl.uid', '=', $kwords['uid']];
            }
            //  订单编号
            if ($kwords['oid']) {
                $where[] = ['oid', '=', $kwords['oid']];
            }
            if ($kwords['startstamp']) {
                $where[] = ['cl.addstamp', '>=', $kwords['startstamp']];
            }

            if ($kwords['endstamp']) {
                $where[] = ['cl.addstamp', '<=', $kwords['endstamp']];
            }

        }
//        halt($where);
        $page = isset($page) ? intval($page) : 0;
        $pageshow = 15;
        $order = "id desc";
        $field = 'cl.*,u.nickname as fnickname,us.nickname as order_nickname';

        $lists = db('commission_log')
            ->alias('cl')
            ->join('user u','cl.uid = u.id')//分销商
            ->join('user us','cl.userid = us.id')//下单用户
            ->order($order)
            ->page($page, $pageshow)
            ->where($where)
            ->field($field)
            ->select();
//        foreach ($lists as $k=>$v)
//        {
//            $lists[$k]['order_status'] = db('order')->where('oid',$v['oid'])->value('order_status');
//        }


        $count = db('commission_log')->alias('cl')->where($where)->count(); //总计多少条
        $pagetotal = ceil($count / $pageshow); //总共有几页
        $pageorg = new PageOrg($count, $page, $pageshow);
        $pageshow = $pageorg->getpage_manage($page, $url);

        $return = array(
            'list' => $lists,           // 列表
            'pageshow' => $pageshow,    // 分页
            'count' => $count,          // 数据总数
            'pagetotal' => $pagetotal,  // 总页数
        );
        return  $return;
    }

    public function getuserdetail($id)
    {
        $field = 'u.id,u.uid,u.nickname,u.is_grade,u.phone,u.gender,u.isallow,u.commission,dg.level_name,u.become_time,u.parent_id,dg.id as lid';
        $lists = db('user')
            ->alias('u')
            ->join('distributor_grade dg','dg.id = u.lid','left')
            ->where('u.id',$id)
            ->field($field)
            ->find();
            //        获取直属上级手机号
            $lists['parent_phone'] = db('user')->where('id',$lists['parent_id'])->value('phone');
        return $lists;
    }

    public function check_phone($id,$phone)
    {
        //  要修改的上级用户
        $data = db('user')->where('phone',$phone)->find();
        //  要修改的用户
        $user = db('user')->where('id',$id)->find();
        if ($data){
            if ($phone == $user['phone'] || $data['parent_id'] == $user['id']){
                return json_encode(["code"=>0,"meg"=>"该会员不符合更改上级条件"]);
            }
            if ($user['parent_id'] == $data['id']){
                return json_encode(["code"=>0,"meg"=>"已经是您的上级"]);
            }
            return json_encode(["code"=>1,"meg"=>"符合更改上级"]);
        }
        return json_encode(["code"=>0,"meg"=>"不存在该会员"]);
    }

    public function confirm_phone($id,$phone)
    {
        //  要修改的上级用户
        $data = db('user')->where('phone',$phone)->find();
        //  要修改的用户
        $user = db('user')->where('id',$id)->find();
        if ($data){
            if ($phone == $user['phone'] || $data['parent_id'] == $user['id']){
                return json_encode(["code"=>0,"meg"=>"该会员不符合更改上级条件"]);
            }
            if ($user['parent_id'] == $data['id']){
                return json_encode(["code"=>0,"meg"=>"已经是您的上级"]);
            }
            $res = db('user')->where('id',$id)->update(['parent_id'=>$data['id']]);
            if ($res){
                return json_encode(["code"=>1,"meg"=>"更改上级成功"]);
            }
            return json_encode(["code"=>0,"meg"=>"更改上级失败"]);
        }
        return json_encode(["code"=>0,"meg"=>"不存在该会员"]);
    }

    public function updatedetail($id)
    {
        if(empty($id)){
            return json_encode(["code"=>0,"meg"=>"数据错误"]);
        }

        $mod_data = [
            'phone' => input('phone'),
            'gender' => input('gender/d',0 , 'intval'),
            'isallow' => input('isallow/d',0 , 'intval'),
            'is_grade' => input('is_grade/d',1 , 'intval'),
            'lid' => input('lid/d',0 , 'intval'),

        ];

        $mod = db('user')->where('id',$id)->update($mod_data);
        if ($mod){
            return json_encode(["code"=>1,"meg"=>"修改成功"]);
        }
        return json_encode(["code"=>0,"meg"=>"修改失败"]);
    }

}