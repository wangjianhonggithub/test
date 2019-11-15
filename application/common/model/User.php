<?php
namespace app\common\model;
use think\facade\Request;
use think\facade\Cache;
use app\common\library\wechat\WxUser;
use app\common\exception\BaseException;
use app\common\model\Setting as DealerSettingModel;


use app\common\model\Coupon as couponmodel; // 优惠券模型
use app\common\model\Packs as packsmodel;   // 课程包模型
use DzcOrg\PageOrg;   // 分页

/**
 * 用户模型类
 * Class User
 * @package app\common\model
 */
class User extends BaseModel
{
    protected $shownum = 10;    // 每页展示数量

    private $token;
    protected $name = 'user';
	protected $pk = 'user_id';
    // 性别
    private $gender = ['未知', '男', '女'];
	  /**
     * 隐藏字段
     * @var array
     */
    /* protected $hidden = [
        'app_id',
        'create_time',
        'update_time'
    ]; */
    
    // 获取用户详情
    public function get_detail($para,$fields=''){	

        $where[] = ['isdel', '=', 1];
        if (is_array($para)) {
            if($para['uid']){
                $where[] = ['uid', '=', $para['uid']];
            }
            if($para['openid_app']){
                $where[] = ['openid_app', '=', $para['openid_app']];
            }
        } else {
            $where[] = ['uid','=', $para];
        }
        // return self::get($where, ['address', 'addressDefault']);
		return db('user')->where($where)->field($fields)->find();
    }

    // 检查uid是否重复
    public function getuid($uid = '')
    {
        // $uid = getonlynum();
        $uid = $uid == '' ? getonlynum() : $uid;
        $isdup = db('user')->where(['uid' => $uid])->value('uid');
        if ($isdup) {
            $uid = $this->getuid(getonlynum());
        }
        return $uid;
    }

    // 获取会员列表
    public function getuser_list($page = 0, $url = '', $kwords = '')
    {
        $model = db('user');
        $where[] = ['isdel', '=', 1];
       
        if (!empty($kwords)) {
            if (is_array($kwords)) {
                // 编号
                if ($kwords['uid']) {
                    $where[] = ['uid', 'like', '%'.$kwords['uid'].'%'];
                }
                // 手机号
                if ($kwords['phone']) {
                    $where[] = ['phone', 'like', '%'.$kwords['phone'].'%'];
                }
                // 昵称
                if ($kwords['nickname']) {
                    $where[] = ['nickname', 'like', '%'.$kwords['nickname'].'%'];
                }
                // 性别 1男 2女
                if ($kwords['gender']) {
                    $where[] = ['gender', '=', $kwords['gender']];
                }
                // 锁定
                if ($kwords['islock']) {
                    $where[] = ['islock', '=', $kwords['islock']];
                }
            } else {
                $where[] = ['nickname', 'like', '%' . $kwords . '%'];
            }
        }
        // dump($where);die;
        $page = isset($page) ? intval($page) : 0;

        $sort = 'addstamp desc,id asc';
        if ($page == 0) {
            $fields =  $url == '' ? 'uid,openid_app,nickname,headimg' : $url;

            // 获取全部
            $lists = $model->where($where)->order($sort)->field($fields)->select();
            return $lists;
        }

        $fields = '';

        $lists = $model->where($where)->order($sort)->page($page, $this->shownum)->field($fields)->select();
        foreach ($lists as $k => $val) {
            $lists[$k]['gender'] = $this->gender[$val['gender']];

            // 课程包数量
            $lists[$k]['packsnum'] = count((new packsmodel)->getuserpacks_list(0,'',$val['uid']));
            
            // 优惠券数量
            $lists[$k]['couponnum'] = count((new couponmodel)->getuser_coupon_list($val['uid']));
           
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