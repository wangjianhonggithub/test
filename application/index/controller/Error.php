<?php
namespace Home\index\controller;
use think\Controller;

class Error extends Controller{
	protected $menulist,$id,$sid,$action;
	public function __construct(){
		// echo $_SERVER['REQUEST_URI'];
	}

	public function _empty(){
		// $this->redirect(url('index/index'));//空控制器处理   
		$this->redirect('/404');//空控制器处理   
		die;
	}
}