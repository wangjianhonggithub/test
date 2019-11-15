<?php
namespace app\api\controller;
/**
 * 登录限制
 * Class App
 * @package app\api\controller
 */
class Islogin extends Controller
{
    public function initialize()
    {
        parent::initialize();
        // dump($this->checklogin());die;
        // 判断是否登录
        switch ($this->checklogin()) {
            case 'notoken':
                echo echojson(-1);die;break;
            case 'nopenid':
                echo echojson(-1);die;break;
            case 'nouser':
                echo echojson(-1);die;break;
            case 'islock':
                //锁定
                echo echojson(801);die;break;
            default:
                # code...
                break;
        }
    }
}
