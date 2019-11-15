<?php
namespace app\api\controller;

use app\common\model\Ads as admodel;

/**
 * 广告控制器
 * Class App
 * @package app\api\controller
 */
class Ads extends Controller
{
    public function adlist(){
        // 分组id
        $gid = input('gid/d',1,'intval');

        $model = new admodel();
        $list = $model->getad_list(0, 'id,gid,name,imgpath',['gid'=> $gid, 'ishow'=>1]);
        if($list){
            return ajax(200, $list[0]['gname'], $list);
        }
        return ajax(400);
    }

    
}
