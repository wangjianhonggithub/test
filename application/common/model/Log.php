<?php
namespace app\common\model;

use DzcOrg\PageOrg;   // 分页

/**
 * 日志模型
 * Class Coupon
 * @package app\common\model
 */
class Log extends BaseModel
{
    // 支付回调记录
    public function payNotify($data){
        $add_data = [
            'date' => date("Y-m-d H:i", time()),
            'comm' => json_encode($data)
        ];
        db('pay_comm')->insert($add_data);
    }

    // 上课定时任务记录
    public function attendclasslog($data){
        $add_data = [
            'date' => date("Y-m-d H:i", time()),
            'comm' => json_encode($data)
        ];
        db('log_remind')->insert($add_data);
    }


}