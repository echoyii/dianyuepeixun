<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends CommonController{
	public function index(){
		$this->display('Index/login');
	}

	// public function login(){
	// 	print_r($_POST);
	// }
}