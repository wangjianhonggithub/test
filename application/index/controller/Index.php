<?php
namespace app\index\controller;
use app\common\model\Setting as setmodel;
class Index  extends \think\Controller
{

    public function index()
    {
		
        return $this->redirect('manage/index/index',302);
    }

    public function asd(){
    	echo (new setmodel)->getItem('website')['address'];
    }
}
