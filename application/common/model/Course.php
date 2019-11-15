<?php
namespace app\common\model;
use app\common\exception\BaseException;
use think\facade\Cache;
use think\facade\Session;

use DzcOrg\PageOrg;   // 分页

/**
 * 课程模型
 * Class Course
 * @package app\common\model
 */
class Course extends BaseModel{
    protected $shownum = 12;    // 每页展示数量

    public function getclass_list($page=0,$url='',$kwords=''){

        $model = db('courseclass');
        $where[] = ['isdel','=',1];

        if (!empty($kwords)) {
            if (is_array($kwords)) {
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

        $page = isset($page) ? intval($page) : 0;
        
        $fields = '';
        $sort = 'sort asc,id asc';

        if($page == 0){
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
    // 获取分类
    public function getclass_one($id){
        if ($id) {
            return db('courseclass')->where(['id'=>$id])->find();
        }
    }
    // 添加
    public function addclass(){
        $add_data = [
            'name' => input('name/s','','trim'),
            'sort' => input('sort/d',99,'intval'),
            'ishow' => input('ishow/d',1,'intval'),
            'addstamp' => time(),
            'modstamp' => time(),
        ];
        $add = db('courseclass')->insert($add_data);
        if($add){
            return true;
        }else{
            return false;
        }
    }
    // 编辑
    public function modclass($id){
        $mod_data = [
            'name' => input('name/s','','trim'),
            'sort' => input('sort/d',99,'intval'),
            'ishow' => input('ishow/d',1,'intval'),
            'modstamp' => time(),
        ];
        $mod = db('courseclass')->where(['id'=>$id])->update($mod_data);
        if($mod){
            return true;
        }else{
            return false;
        }
    }

    // 获取教练列表
    public function getcoach_list($page = 0, $url = '', $kwords = ''){
        $model = db('coach');
        $where = array(
            'isdel' => 1
        );

        if ($kwords) {
            $where['name'] = ['like', '%' . $kwords . '%'];
        }

        $page = isset($page) ? intval($page) : 0;

        $fields = '';
        $sort = 'sort asc,id asc';

        if ($page == 0) {
            $fields =  $url == ''? 'id,name' : $url;
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
    public function getcoach_one($id){
        if ($id) {
            return db('coach')->where(['id' => $id])->find();
        }
    }
    // 添加
    public function addcoach(){
        $pathid = input('headpath/d', 0, 'intval');
        if($pathid == 0){
            $this->error = '请上传教练头像';return false;
        }
        // 获取原始路径
        $headpath = db('upload_file')->where(['id'=>$pathid])->value('file_name');
        if($headpath == ''){
            $this->error = '图片上传失败';
            return false;
        }
        $headpath = config('dzc.document_path') . $headpath;

        $add_data = [
            'name' => input('name/s', '', 'trim'),
            'headpath' => $headpath,
            'title1' => input('title1/s', '', 'trim'),
            'title2' => input('title2/s', '', 'trim'),
            'content' => input('content/s', '', 'trim'),
            'addstamp' => time(),
            'modstamp' => time(),
        ];
        // dump($add_data);die;
        $add = db('coach')->insert($add_data);
        if ($add) {
            return true;
        } else {
            return false;
        }
    }
    // 编辑
    public function modcoach($id){

        $mod_data = [
            'name' => input('name/s', '', 'trim'),
            'title1' => input('title1/s', '', 'trim'),
            'title2' => input('title2/s', '', 'trim'),
            'content' => input('content/s', '', 'trim'),
            'modstamp' => time(),
        ];
        $headpath = input('headpath');
        if(is_numeric(input('headpath'))){
            // 数字就差图片原始路径
            $pathid = input('headpath/d', 0, 'intval');
            if ($pathid == 0) {
                $this->error = '请上传教练头像';return false;
            }
            // 获取原始路径
            $headpath = db('upload_file')->where(['id' => $pathid])->value('file_name');
            if ($headpath == '') {
                $this->error = '图片上传失败';return false;
            }
            $headpath = config('dzc.document_path') . $headpath;

            $mod_data['headpath'] = $headpath;
        }

        // dump($mod_data);die;
        $mod = db('coach')->where(['id' => $id])->update($mod_data);
        if ($mod) {
            return true;
        } else {
            return false;
        }
    }

    // 更改课程状态
    public function change_course_state($sid=''){
        // 过期昨天的课程
        $daywhere[] = ['isdel', '=', '1'];
        $daywhere[] = ['ispast', '=', '1'];
        $daywhere[] = ['datestamp', '<', strtotime(date('Y-m-d', time()))];

        // 过期日期今天开课的课程
        $timewhere[] = ['isdel', '=', '1'];
        $timewhere[] = ['ispast', '=', '1'];
        $timewhere[] = ['datestamp', '=', strtotime(date('Y-m-d', time()))];
        $timewhere[] = ['stime', '<', date('H:i:s', time())];

        // 满员更改
        $fullwhere[] = ['isdel', '=', '1'];
        $fullwhere[] = ['ispast', '=', '1'];
        $fullwhere[] = ['isfull', '=', '1'];

        if($sid){
            // 更改这一个课程
            $daywhere[] = ['sid','=',$sid];
            db('course')->where($daywhere)->update(['ispast'=>2]);

            $timewhere[] = ['sid','=',$sid];
            db('course')->where($timewhere)->update(['ispast' => 2]);

            $fullwhere[] = ['sid','=',$sid];
            $fullcomm = db('course')->where($fullwhere)->field('id,totnum,nownum')->select();
            if ($fullcomm['nownum'] >= $fullcomm['totnum']) {
                $fullcomm = db('course')->where(['id' => $fullcomm['id']])->update(['isfull' => 2]);
            }
        }else{

            db('course')->where($daywhere)->update(['ispast'=>2]);
            db('course')->where($timewhere)->update(['ispast' => 2]);

            $fulllist = db('course')->where($fullwhere)->field('id,totnum,nownum')->select();
            foreach($fulllist as $val){
                if($val['nownum'] >= $val['totnum']){
                    $fulllist = db('course')->where(['id'=>$val['id']])->update(['isfull'=>2]);
                }
            }
        }
    }

    // 获取课程列表
    public function getcourse_list($page = 0, $url = '', $kwords = ''){
        // 更改课程状态
        $this->change_course_state();

        $model = db('course');
        $where[] = ['isdel', '=', 1];
        // 历史记录
        if ($kwords['history'] == 1) {
            $where[] = ['ispast', '=', 2];
        } else {
            $where[] = ['datestamp', '>=', strtotime(date('Y-m-d', time()))];
            // $where[] = ['ispast', '=', 1];
        }

        if (!empty($kwords)) {
            if(is_array($kwords)){
                // 分类
                if($kwords['cid']){     $where[] = ['cid', '=', $kwords['cid']]; }
                // 教练
                if($kwords['coid']){    $where[] = ['coid', '=', $kwords['coid']]; }
                // 展示隐藏
                if($kwords['ishow']){   $where[] = ['ishow', '=', $kwords['ishow']]; }
                // 状态
                if($kwords['state']){
                    switch ($kwords['state']) {
                        case 11:
                            // 可预约
                            $where[] = ['ispast', '=', 1];
                            $where[] = ['isfull', '=', 1];
                            break;
                        case 22:
                            // 已满员
                            $where[] = ['isfull', '=', 2];
                            break;
                        case 33:
                            // 已结束
                            $where[] = ['ispast', '=', 2];
                            break;
                        case 44:
                            // 隐藏课程
                            $where[] = ['ishow', '=', 2];
                            break;
                        default:
                            # code...
                            break;
                    }
                }
                // 编号
                if($kwords['sid']){     $where[] = ['sid', 'like' , '%'.$kwords['sid'].'%']; }
                // 名称
                if($kwords['name']){    $where[] = ['name', 'like', '%' . $kwords['name'] . '%']; }
                // 日期
                if($kwords['datestamp']){
                    $datestamp = strtotime($kwords['datestamp']);
                    $where[] = ['datestamp', '=', $datestamp];
                }
            }else{
                $where[] = ['name', 'like', '%' . $kwords . '%'];
            }
        }
        // dump($where);die;
        $page = isset($page) ? intval($page) : 0;

        // $sort = 'datestamp desc,stime asc,sort asc,id asc';
        $sort = 'datestamp desc,sort asc,id asc';
        if ($page == 0) {
            // $where['ishow'] = 1;
            $fields =  $url == '' ? 'id,name' : $url;

            // 获取全部
            $lists = $model->where($where)->order($sort)->field($fields)->select();
            foreach ($lists as $k => $val) {
                $lists[$k]['classname'] = db('courseclass')->where(['id' => $val['cid']])->value('name');
                $lists[$k]['coname'] = db('coach')->where(['id' => $val['coid']])->value('name');

                $lists[$k]['datestamp'] = date("Y-m-d", $val['datestamp']);
                $lists[$k]['stime'] = substr($val['stime'], 0, 5);
                $lists[$k]['etime'] = substr($val['etime'], 0, 5);
            }

            return $lists;
        }

        $fields = 'id,sid,cid,name,coid,price,complexity,target,datestamp,stime,etime,listbgimg,maxnum,totnum,nownum,sort,ishow,ispast,isfull,modstamp';

        // $lists = $model->where($where)->order($sort)->page($page, $this->shownum)->field($fields)->fetchsql(1)->select();dump($lists);die;

        $lists = $model->where($where)->order($sort)->page($page, $this->shownum)->field($fields)->select();
        foreach($lists as $k=>$val){
            $lists[$k]['classname'] = db('courseclass')->where(['id'=>$val['cid']])->value('name');
            $lists[$k]['coname'] = db('coach')->where(['id'=>$val['coid']])->value('name');

            $lists[$k]['datestamp'] = date("Y-m-d", $val['datestamp']);
            $lists[$k]['stime'] = substr($val['stime'], 0 , 5);
            $lists[$k]['etime'] = substr($val['etime'], 0 , 5);
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

    // 课程详情
    public function getcourse_detail($sid, $fields=''){
        if ($sid) {
            $this->change_course_state($sid);

            return db('course')->where(['sid' => $sid])->field($fields)->find();
        }
    }
    public function getcourse_one($id, $fields=''){
        if ($id) {
            return db('course')->where(['id' => $id])->field($fields)->find();
        }
    }

    // 添加
    public function addcourse(){
        // dump(input());die;

        $datestamp = strtotime(input('datestamp/s',date("Y-m-d"), 'trim'));
        $times = explode(' - ',input('times/s','','trim'));

        $listbgimgpathid = input('listbgimg/d', 0, 'intval');
        if ($listbgimgpathid == 0) {    $this->error = '请上传列表背景图';return false;}
        // 获取原始路径
        $listbgimg = db('upload_file')->where(['id' => $listbgimgpathid])->value('file_name');
        if ($listbgimg == '') { $this->error = '图片上传失败';return false;}
        $listbgimg = config('dzc.document_path') . $listbgimg;

        $insbgimgpathid = input('insbgimg/d', 0, 'intval');
        if ($insbgimgpathid == 0) {    $this->error = '请上传内页背景图';return false;}
        // 获取原始路径
        $insbgimg = db('upload_file')->where(['id' => $insbgimgpathid])->value('file_name');
        if ($insbgimg == '') { $this->error = '图片上传失败';return false;}
        $insbgimg = config('dzc.document_path') . $insbgimg;

        $add_data = [
            'cid' => input('cid/d', 0, 'intval'),
            'sid' => getonlynum(),  // 唯一编号
            'name' => input('name/s', '', 'trim'),
            'coid' => input('coid/d', 0, 'intval'),
            'price' => input('price/d', 0, 'intval'),
            'complexity' => input('complexity/s', '', 'trim'),
            'target' => input('target/s', '', 'trim'),

            'datestamp' => $datestamp,
            'stime' => $times[0],
            'etime' => $times[1],

            'listbgimg' => $listbgimg,
            'insbgimg' => $insbgimg,

            'maxnum' => input('maxnum/d', 0, 'intval'),
            'totnum' => input('totnum/d', 0, 'intval'),
            'nownum' => input('nownum/d', 0, 'intval'),

            'content1' => input('content1/s', '', 'trim'),
            'content2' => input('content2/s', '', 'trim'),
            
            'sort' => input('sort/d', 0, 'intval'),
            'ishow' => input('ishow/d', 1, 'intval'),
            'addstamp' => time(),
            'modstamp' => time(),
        ];
        
        // dump($add_data);die;
        $add = db('course')->insert($add_data);
        if ($add) {
            return true;
        } else {
            return false;
        }
    }
    // 编辑
    public function modcourse($id){
        // dump(input());die;

        $datestamp = strtotime(input('datestamp/s', date("Y-m-d"), 'trim'));
        $times = explode(' - ', input('times/s', '', 'trim'));

        $mod_data = [
            'cid' => input('cid/d', 0, 'intval'),
            'name' => input('name/s', '', 'trim'),
            'coid' => input('coid/d', 0, 'intval'),
            'price' => input('price/d', 0, 'intval'),
            'complexity' => input('complexity/s', '', 'trim'),
            'target' => input('target/s', '', 'trim'),

            'datestamp' => $datestamp,
            'stime' => $times[0],
            'etime' => $times[1],

            'maxnum' => input('maxnum/d', 0, 'intval'),
            'totnum' => input('totnum/d', 0, 'intval'),
            'nownum' => input('nownum/d', 0, 'intval'),

            'content1' => input('content1/s', '', 'trim'),
            'content2' => input('content2/s', '', 'trim'),

            'sort' => input('sort/d', 0, 'intval'),
            'ishow' => input('ishow/d', 1, 'intval'),
            'modstamp' => time(),
        ];

        if (is_numeric(input('listbgimg'))) {
            // 数字就查询图片原始路径
            $listbgimgpathid = input('listbgimg/d', 0, 'intval');
            if ($listbgimgpathid == 0) {    $this->error = '请上传列表背景图';return false;   }
            
            // 获取原始路径
            $listbgimg = db('upload_file')->where(['id' => $listbgimgpathid])->value('file_name');
            if ($listbgimg == '') {     $this->error = '图片上传失败';return false; }
            $listbgimg = config('dzc.document_path') . $listbgimg;

            $mod_data['listbgimg'] = $listbgimg;
        }

        if (is_numeric(input('insbgimg'))) {
            // 数字就查询图片原始路径
            $insbgimgpathid = input('insbgimg/d', 0, 'intval');
            if ($insbgimgpathid == 0) {    $this->error = '请上传内页背景图';return false;   }
            
            // 获取原始路径
            $insbgimg = db('upload_file')->where(['id' => $insbgimgpathid])->value('file_name');
            if ($insbgimg == '') {     $this->error = '图片上传失败';return false; }
            $insbgimg = config('dzc.document_path') . $insbgimg;

            $mod_data['insbgimg'] = $insbgimg;
        }

        // dump($mod_data);die;

        $mod = db('course')->where(['id' => $id])->update($mod_data);
        if ($mod) {
            return true;
        } else {
            return false;
        }
    }

    // 获取课程报名列表
    public function getcourse_reglist($page = 0, $url = '', $kwords = '')
    {
        $model = db('course_list');
        $where[] = ['isdel', '=', 1];
        
        if (!empty($kwords)) {
            if (is_array($kwords)) {
                // 课程编号
                if ($kwords['sid']) {
                    $where[] = ['sid', '=', $kwords['sid']];
                }
                // 会员
                if ($kwords['uid']) {
                    $where[] = ['uid', '=', $kwords['uid']];
                }
                // 订单
                if ($kwords['oid']) {
                    $where[] = ['oid', '=', $kwords['oid']];
                }               
                // 名称
                if ($kwords['phone']) {
                    $where[] = ['phone', 'like', '%' . $kwords['phone'] . '%'];
                }
                // 备注
                if ($kwords['content']) {
                    $where[] = ['content', 'like', '%' . $kwords['content'] . '%'];
                }
                
                // 状态
                if ($kwords['stype']) {
                    $where[] = ['stype', '=', $kwords['stype']];
                }
            } else {
                $where[] = ['name', 'like', '%' . $kwords . '%'];
            }
        }
        // dump($where);die;
        $page = isset($page) ? intval($page) : 0;

        $sort = 'addstamp desc,id asc';
        $gender = ['未知', '男', '女'];
        $stypearr = [1=>'用户报名',2=>'后台添加'];
        // $fields = 'id,sid,cid,name,coid,price,complexity,target,datestamp,stime,etime,listbgimg,maxnum,totnum,nownum,sort,ishow,ispast,isfull,modstamp';
        $fields = '';

        if ($page == 0) {
            // $fields =  $url == '' ? 'id,name' : $url;

            // 获取全部
            $lists = $model->where($where)->order($sort)->field($fields)->select();
            foreach ($lists as $k => $val) {
                $lists[$k]['userinfo'] = db('user')->where(['uid' => $val['uid']])->field('nickname,gender,headimg')->find();
                $lists[$k]['userinfo']['gender'] = $gender[$lists[$k]['userinfo']['gender']];
                $lists[$k]['stype'] = $stypearr[$val['stype']];
            }
            return $lists;
        }

        $lists = $model->where($where)->order($sort)->page($page, $this->shownum)->field($fields)->select();
        foreach ($lists as $k => $val) {
            $lists[$k]['userinfo'] = db('user')->where(['uid' => $val['uid']])->field('nickname,gender,headimg')->find();
            $lists[$k]['userinfo']['gender'] = $gender[$lists[$k]['userinfo']['gender']];
            $lists[$k]['stype'] = $stypearr[$val['stype']];
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

    // 添加报名信息
    public function addcoursereg(){

        $add_data = [
            'sid' => input('sid/s','','trim'),
            'uid' => 0,
            'number' => input('number/d', 1, 'intval'),
            'phone' => input('phone/s','','trim'),
            'content' => input('content/s','','trim'),
            'stype' => 2,
            'addstamp' => time(),
        ];
        // 判断课程剩余席位是否够用
        $counum = db('course')->where(['sid' => $add_data['sid']])->field('totnum,nownum')->find();

        $counownum = $counum['totnum'] - $counum['nownum'];
        if($counownum < $add_data['number']){
            return ['mess'=>'预约席位已满'];
        }

        // dump($add_data);die;
        $add = db('course_list')->insert($add_data);
        if ($add) {
            db('course')->where(['sid' => $add_data['sid']])->setInc('nownum', $add_data['number']);

            return true;
        } else {
            return ['mess'=>'更新失败'];
        }
    }

    public function getError()
    {
        return $this->error;
    }

}