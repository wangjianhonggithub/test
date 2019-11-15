<?php
namespace app\common\model;
use app\common\exception\BaseException;
use think\facade\Cache;
use think\facade\Session;

use DzcOrg\PageOrg;   // 分页

/**
 * 课程包模型
 * Class Course
 * @package app\common\model
 */
class Packs extends BaseModel{
    protected $shownum = 12;    // 每页展示数量

    // 获取用户课程包列表
    public function getuserpacks_list($page = 0, $url = '', $uid=0, $kwords = '')
    {
        // 更改课程包状态
        // 删除过期七天的课程包
        $this->userpacks_del($uid);
        // 过期课程包
        $this->userpacks_past($uid);

        $model = db('user_packs');
        $where[] = ['isdel', '=', 1];
        // $where[] = ['isuse', '=', 1];
        
        if($uid){
            $where[] = ['uid', '=', $uid];
        }
        if (!empty($kwords)) {
            if (is_array($kwords)) {
                // 分类
                if ($kwords['cid']) {
                    $where[] = ['cid', 'in', $kwords['cid']];
                }
                // 编号
                if ($kwords['sid']) {
                    $where[] = ['sid', 'like', '%' . $kwords['sid'] . '%'];
                }
                // 名称
                if ($kwords['name']) {
                    $where[] = ['name', 'like', '%' . $kwords['name'] . '%'];
                }
                // 是否使用
                if ($kwords['isuse']) {
                    $where[] = ['isuse', '=', $kwords['isuse']];
                }
            } else {
                $where[] = ['name', 'like', '%' . $kwords . '%'];
            }
        }
        $page = isset($page) ? intval($page) : 0;

        $fields = '';
        $sort = 'modstamp desc,id asc';
        if ($page == 0) {
            $fields =  $url == '' ? 'id,sid,cid,name' : $url;
            // 获取全部
            $lists = $model->where($where)->order($sort)->field($fields)->select();
            foreach($lists as $k=>$val){
                $lists[$k]['classname'] = $val['cid'] == 0?'全部': db('courseclass')->where(['id' => $val['cid']])->value('name');
            }   
            // dump($lists);die;
            return $lists;
        }

        $lists = $model->where($where)->order($sort)->page($page, $this->shownum)->field($fields)->select();
        foreach ($lists as $k => $val) {
            $lists[$k]['classname'] = $val['cid'] == 0?'全部': db('courseclass')->where(['id' => $val['cid']])->value('name');
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
    // 获取用户课程包详情
    public function getuserpacks_one($id,$fields='')
    {
        if ($id) {
            return db('user_packs')->where(['id' => $id])->field($fields)->find();
        }
    }
    
    // 删除过期订单
    private function userpacks_del($uid=0)
    {
        if($uid){
            $delwhere[] = ['uid', '=', $uid];
        }
        $delwhere[] = ['ispast', '=', 2];
        $delwhere[] = ['pastamp', '<=', time() - (7 * 24 * 60 * 60)];
        db('user_packs')->where($delwhere)->update(['isdel' => 2, 'modstamp' => time()]);
    }

    // 过期订单
    private function userpacks_past($uid=0)
    {
        if($uid){
            $pastwhere[] = ['uid', '=', $uid];
        }
        $pastwhere[] = ['pastamp', '<=', time()];
        db('user_packs')->where($pastwhere)->update(['ispast' => 2, 'modstamp' => time()]);
    }

    // 获取课程包列表
    public function getpacks_list($page = 0, $url = '', $kwords = '')
    {
        $model = db('packs');
        $where[] = ['isdel', '=', 1];
        
        if (!empty($kwords)) {
            if (is_array($kwords)) {
                if ($kwords['ishow']) {
                    $where[] = ['ishow', '=', $kwords['ishow']];
                }
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
            foreach($lists as $k=>$val){
                $lists[$k]['classname'] = $val['cid']==0?'全部':db('courseclass')->where(['id' => $val['cid']])->value('name');
            }   
            return $lists;
        }

        $lists = $model->where($where)->order($sort)->page($page, $this->shownum)->field($fields)->select();
        foreach ($lists as $k => $val) {
            $lists[$k]['classname'] = $val['cid']==0?'全部':db('courseclass')->where(['id' => $val['cid']])->value('name');
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

    // 获取课程包
    public function getpacks_one($id)
    {
        if ($id) {
            return db('packs')->where(['id' => $id])->find();
        }
    }

    // 添加课程包
    public function addpacks()
    {
        // dump(input());die;
        $add_data = [
            'cid' => input('cid/d', 0, 'intval'),
            'sid' => getonlynum(),  // 唯一编号
            'name' => input('name/s', '', 'trim'),
            'price' => input('price/d', 0, 'intval'),
            'tothour' => input('tothour/d', 0, 'intval'),
            'validays' => input('validays/d', 0, 'intval'),
            'everyuan' => input('everyuan/d', 0, 'intval'),
            'saveyuan' => input('saveyuan/d', 0, 'intval'),
            'intro' => input('intro/s', '', 'trim'),

            'sort' => input('sort/d', 0, 'intval'),
            'ishow' => input('ishow/d', 1, 'intval'),
            'addstamp' => time(),
            'modstamp' => time(),
        ];
        // dump($add_data);die;
        $add = db('packs')->insert($add_data);
        if ($add) {
            return true;
        } else {
            return false;
        }
    }
    // 编辑
    public function modpacks($id)
    {
        // dump(input());die;

        $mod_data = [
            'cid' => input('cid/d', 0, 'intval'),
            'name' => input('name/s', '', 'trim'),
            'price' => input('price/d', 0, 'intval'),
            'tothour' => input('tothour/d', 0, 'intval'),
            'validays' => input('validays/d', 0, 'intval'),
            'everyuan' => input('everyuan/d', 0, 'intval'),
            'saveyuan' => input('saveyuan/d', 0, 'intval'),
            'intro' => input('intro/s', '', 'trim'),

            'sort' => input('sort/d', 0, 'intval'),
            'ishow' => input('ishow/d', 1, 'intval'),
            'modstamp' => time(),
        ];

        // dump($mod_data);die;
        $mod = db('packs')->where(['id' => $id])->update($mod_data);
        if ($mod) {
            return true;
        } else {
            return false;
        }
    }

    // 获取课程包记录列表
    public function getpacks_loglist($page = 0, $url = '', $kwords = '')
    {
        $model = db('log_packs');

        if (!empty($kwords)) {
            if (is_array($kwords)) {
                if ($kwords['packsid']) {
                    $where[] = ['packsid', '=', $kwords['packsid']];
                }

                // 分类
                if ($kwords['stype']) {
                    $where[] = ['stype', '=', $kwords['stype']];
                }
                // 名称
                if ($kwords['name']) {
                    $where[] = ['name', 'like', '%' . $kwords['name'] . '%'];
                }
                // 会员编号
                if ($kwords['uid']) {
                    $where[] = ['uid', 'like', '%' . $kwords['uid'] . '%'];
                }
            } else {
                $where[] = ['packsid', '=', $kwords];
            }
        }
        // dump($where);die;
        $page = isset($page) ? intval($page) : 0;

        $fields = '';
        $sort = 'addstamp desc,id asc'; // 时间降序

        if ($page == 0) {
            // $where[] = ['ishow', '=', 1];
            // $fields =  $url == '' ? 'id,sid,cid,name' : $url;
            $sort = 'addstamp asc,id asc'; // 升序

            // 获取全部
            $lists = $model->where($where)->order($sort)->field($fields)->select();
            return $lists;
        }

        $lists = $model->where($where)->order($sort)->page($page, $this->shownum)->field($fields)->select();
        foreach ($lists as $k => $val) {
            $lists[$k]['coname'] = db('course')->where(['sid' => $val['cosid']])->value('name');
            $lists[$k]['nickname'] = db('user')->where(['uid' => $val['uid']])->value('nickname');
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
}