<?php
namespace app\api\controller;

use app\common\model\Setting as setmodel;

/**
 * 公共控制器 站点信息
 * Class App
 * @package app\api\controller
 */
class Home extends Controller
{
    // 站点信息
    public function website(){

        $data = (new setmodel)->getItem('website');
        // 富文本转图片路径
        $data['zhuyi'] = changebody($data['zhuyi']); 
        $data['mianze'] = changebody($data['mianze']); 
        $data['kechengbao'] = changebody($data['kechengbao']);
        // 流程转数组
        $data['kechengliucheng'] = explode('丨', trim($data['kechengliucheng'])); 
  
        return ajax(200, 'website', $data);
    }

    // 


}
