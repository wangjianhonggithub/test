<?php
namespace app\common\model;

use DzcOrg\PageOrg;   // 分页

/**
 * 广告模型
 * Class Coupon
 * @package app\common\model
 */
class Ads extends BaseModel
{
    protected $shownum = 15;    // 每页展示数量

    // 获取优惠券列表
    public function getgroup_list($page = 0, $url = '', $kwords = '')
    {
        $model = db('ad_group');
        $where[] = ['isdel', '=', 1];

        if (!empty($kwords)) {
            if (is_array($kwords)) {
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
            $fields =  $url == '' ? 'id,name' : $url;

            // 获取全部
            $lists = $model->where($where)->order($sort)->field($fields)->select();
            return $lists;
        }

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

    // 获取分组
    public function getgroup_one($id)
    {
        if ($id) {
            return db('ad_group')->where(['id' => $id])->find();
        }
    }
   
    // 添加广告分组
    public function addgroup()
    {
        // dump(input());die;
        $add_data = [
            'name' => input('name/s', '', 'trim'),
            'sort' => input('sort/d', 99, 'intval'),
            'ishow' => input('ishow/d', 1, 'intval'),

            'addstamp' => time(),
            'modstamp' => time(),
        ];
        // dump($add_data);die;
        $add = db('ad_group')->insert($add_data);
        if ($add) {
            return true;
        } else {
            return false;
        }
    }
    // 编辑
    public function modgroup($id)
    {
        // dump(input());die;

        $mod_data = [
            'name' => input('name/s', '', 'trim'),
            // 'sort' => input('sort/d', 0, 'intval'),
            // 'ishow' => input('ishow/d', 1, 'intval'),
            'modstamp' => time(),
        ];

        // dump($mod_data);die;
        $mod = db('ad_group')->where(['id' => $id])->update($mod_data);
        if ($mod) {
            return true;
        } else {
            return false;
        }
    }

    // 广告列表
    public function getad_list($page = 0, $url = '', $kwords = '')
    {
        $model = db('ads');
        $where[] = ['isdel', '=', 1];

        if (!empty($kwords)) {
            if (is_array($kwords)) {
                // 分组
                if ($kwords['gid']) {
                    $where[] = ['gid', '=', $kwords['gid']];
                }
                // 名称
                if ($kwords['name']) {
                    $where[] = ['name', 'like', '%' . $kwords['name'] . '%'];
                }
                if ($kwords['ishow']) {
                    $where[] = ['ishow', '=', $kwords['ishow']];
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
            $fields =  $url == '' ? 'id,gid,name' : $url;

            // 获取全部
            $lists = $model->where($where)->order($sort)->field($fields)->select();
            $gmodel = db('ad_group');
            foreach ($lists as $k => $val) {
                $lists[$k]['gname'] = $gmodel->where(['id' => $val['gid']])->value('name');
            }
            return $lists;
        }

        $lists = $model->where($where)->order($sort)->page($page, $this->shownum)->field($fields)->select();
        $gmodel = db('ad_group');
        foreach($lists as $k=>$val){
            $lists[$k]['gname'] = $gmodel->where(['id'=>$val['gid']])->value('name');
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

    // 获取广告
    public function getad_one($id)
    {
        if ($id) {
            return db('ads')->where(['id' => $id])->find();
        }
    }

    // 添加广告分组
    public function addad()
    {
        // dump(input());die;

        $imgpathid = input('imgpath/d', 0, 'intval');
        if ($imgpathid == 0) {    $this->error = '请上传列表背景图';return false; }
        // 获取原始路径
        $imgpath = db('upload_file')->where(['id' => $imgpathid])->value('file_name');
        if ($imgpath == '') {         $this->error = '图片上传失败';return false; }
        $imgpath = config('dzc.document_path') . $imgpath;

        $add_data = [
            'gid' => input('gid/d', 1, 'intval'),
            'name' => input('name/s', '', 'trim'),
            'imgpath' => $imgpath,

            'sort' => input('sort/d', 99, 'intval'),
            'ishow' => input('ishow/d', 1, 'intval'),

            'addstamp' => time(),
            'modstamp' => time(),
        ];
        // dump($add_data);die;
        $add = db('ads')->insert($add_data);
        if ($add) {
            return true;
        } else {
            return false;
        }
    }
    // 编辑
    public function modad($id)
    {
        // dump(input());die;

        $mod_data = [
            'gid' => input('gid/d', 1, 'intval'),
            'name' => input('name/s', '', 'trim'),

            'sort' => input('sort/d', 99, 'intval'),
            'ishow' => input('ishow/d', 1, 'intval'),
            'modstamp' => time(),
        ];

        if (is_numeric(input('imgpath'))) {
            // 数字就查询图片原始路径
            $imgpathpathid = input('imgpath/d', 0, 'intval');
            if ($imgpathpathid == 0) {  $this->error = '请上传展示图片';return false;}

            // 获取原始路径
            $imgpath = db('upload_file')->where(['id' => $imgpathpathid])->value('file_name');
            if ($imgpath == '') {   $this->error = '图片上传失败';return false;}
            $imgpath = config('dzc.document_path') . $imgpath;

            $mod_data['imgpath'] = $imgpath;
        }

        // dump($mod_data);die;
        $mod = db('ads')->where(['id' => $id])->update($mod_data);
        if ($mod) {
            return true;
        } else {
            return false;
        }
    }
}