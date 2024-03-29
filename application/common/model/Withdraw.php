<?php
namespace app\common\model;
use app\common\exception\BaseException;
use app\common\service\Message;
/**
 * 分销商提现明细模型
 * Class Apply
 * @package app\common\model\dealer
 */
class Withdraw extends BaseModel
{
    protected $name = 'agent_withdraw';
	/**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'update_time',
    ];
    /**
     * 打款方式
     * @var array
     */
    public $payType = [
        10 => '微信',
        20 => '支付宝',
        30 => '银行卡',
    ];
    /**
     * 申请状态
     * @var array
     */
    public $applyStatus = [
        10 => '待审核',
        20 => '审核通过',
        30 => '驳回',
        40 => '已打款',
    ];
    /**
     * 关联分销商用户表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User','user_id','user_id');
    }
    /**
     * 提现详情
     * @param $id
     * @return Apply|static
     * @throws \think\exception\DbException
     */
    public static function detail($id)
    {
        return self::get($id);
    }
    /**
     * 获取分销商提现列表
     * @param null $user_id
     * @param int $apply_status
     * @param int $pay_type
     * @param string $search
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($user_id = null, $apply_status = -1, $pay_type = -1, $search = '')
    {
		$where=[];
		$user_id > 0 && $where[]=['withdraw.user_id', '=', $user_id];
        $apply_status > 0 && $where[]=['withdraw.apply_status', '=', $apply_status];
        $pay_type > 0 && $where[]=['withdraw.pay_type', '=', $pay_type];
		!empty($search) && $where[]=['user.nickName', 'like', "%$search%"];
        // 构建查询规则
    return $this->alias('withdraw')
            ->with(['user'])
            ->field('withdraw.*, user.nickName, user.avatarUrl')
            ->join('user', 'user.user_id = withdraw.user_id')->where($where)
            ->order(['withdraw.create_time' => 'desc'])->paginate(15, false, [
            'query' => \request()->request()
        ]);
        // 查询条件
    }
    /**
     * 获取分销商提现明细
     * @param $user_id
     * @param int $apply_status
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getLists($user_id, $apply_status = -1)
    {
		$where[]=['user_id','=',$user_id];
		$apply_status > -1 && $where[]=['apply_status','=',$apply_status];
        return $this->order(['create_time' => 'desc'])
			->where($where)
            ->paginate(15, false, [
                'query' => \request()->request()
            ]);
    }
    /**
     * 提交申请
     * @param User $dealer
     * @param $data
     * @return false|int
     * @throws BaseException
     */
    public function submit($dealer, $data)
    {
        // 数据验证
        $this->validation($dealer, $data);
        // 新增申请记录
        $this->save(array_merge($data, [
            'user_id' => $dealer['user_id'],
            'apply_status' => 10,
            'app_id' => self::$app_id,
        ]));
        // 冻结用户资金
        $dealer->freezeMoney($data['money']);
        return true;
    }
	/**
     * 分销商提现审核
     * @param $data
     * @return bool
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function submits($data)
    {
        if ($data['apply_status'] == '30' && empty($data['reject_reason'])) {
            $this->error = '请填写驳回原因';
            return false;
        }
        // 更新申请记录
        $data['audit_time'] = time();
        $this->allowField(true)->save($data);
        // 提现驳回：解冻分销商资金
        $data['apply_status'] == '30' && User::backFreezeMoney($this['user_id'], $this['money']);
        // 发送模板消息
        (new Message)->withdraw($this);
        return true;
    }
    /**
     * 数据验证
     * @param $dealer
     * @param $data
     * @throws BaseException
     */
    private function validation($dealer, $data)
    {
        // 结算设置
        $settlement = Setting::getItem('settlement');
        // 最低提现佣金
        if ($data['money'] <= 0) {
            throw new BaseException(['msg' => '提现金额不正确']);
        }
        if ($dealer['money'] <= 0) {
            throw new BaseException(['msg' => '当前用户没有可提现佣金']);
        }
        if ($data['money'] > $dealer['money']) {
            throw new BaseException(['msg' => '提现金额不能大于可提现佣金']);
        }
        if ($data['money'] < $settlement['min_money']) {
            throw new BaseException(['msg' => '最低提现金额为' . $settlement['min_money']]);
        }
        if (!in_array($data['pay_type'], $settlement['pay_type'])) {
            throw new BaseException(['msg' => '提现方式不正确']);
        }
        if ($data['pay_type'] == '20') {
            if (empty($data['alipay_name']) || empty($data['alipay_account'])) {
                throw new BaseException(['msg' => '请补全提现信息']);
            }
        } elseif ($data['pay_type'] == '30') {
            if (empty($data['bank_name']) || empty($data['bank_account']) || empty($data['bank_card'])) {
                throw new BaseException(['msg' => '请补全提现信息']);
            }
        }
    }
	/**
     * 确认打款
     * @return bool
     * @throws \think\exception\PDOException
     */
    public function money()
    {
        $this->startTrans();
        try {
            // 更新申请状态
            $this->allowField(true)->save([
                'apply_status' => 40,
                'audit_time' => time(),
            ]);
            // 更新分销商累积提现佣金
            User::totalMoney($this['user_id'], $this['money']);
            // 记录分销商资金明细
            Capital::add([
                'user_id' => $this['user_id'],
                'flow_type' => 20,
                'money' => -$this['money'],
                'describe' => '申请提现',
            ]);
            // 发送模板消息
            (new Message)->withdraw($this);
            // 事务提交
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }
}