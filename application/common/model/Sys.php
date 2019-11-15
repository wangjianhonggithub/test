<?php
namespace app\common\model;
use think\Model;
/**
 * 系统配置模型
 * @param
 */
class Sys extends Model{
	// 传入关键字返回 isopen字段 1展示 2隐藏
	public function getsys($key=''){
		if($key == ''){
			// 获取全部 列表
			return db('sys')->select();
		}

		return db('sys')->where(['key'=>$key])->value('isopen');
	}

	// 更改状态 1展示 2隐藏
	public function modsys($key='', $isopen=1){
		if($key == ''){
			return false;
		}
		switch ($key) {
			case 'coupon':
				$this->modsys_coupon($isopen);
				break;
			case 'package':
				$this->modsys_package($isopen);
				break;
			
			default:
				# code...
				break;
		}

		// 更改状态
		db('sys')->where(['key'=>$key])->update(['isopen'=> $isopen]);
	}

	// 更改优惠券配置
	private function modsys_coupon($isopen=1){
		$where[] = ['id', 'in', '100338,100339,100361'];
		db('menu')->where($where)->update(['isopen'=> $isopen]);
	}

	// 更改课程包配置
	private function modsys_package($isopen=1){

	}
	
}