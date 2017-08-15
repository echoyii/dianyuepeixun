<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends CommonController
{
	public function index()
	{
		
		

		//获取到待审核项目数量  待发布项目数量  一星期注册用户
		if(IS_GET){
			//获取待审核项目数
			$fundings_num_zero = M('fundings')->where(array('state' => 0))->count('id');

			//获取待发布抽样中的项目数
			$fundings_num_two = M('fundings')->where(array('state' => 2))->count('id');

			//获取最近一星期的注册用户数量
			$time = strtotime("last monday");
			// echo $time;
			$map['ctime'] = array('gt',$time);
			$members_num = M('members')->where($map)->count();


		}
		$this->assign('fundings_num_zero',$fundings_num_zero);
		$this->assign('fundings_num_two',$fundings_num_two);
		$this->assign('members_num',$members_num);
		$this->display('index');
	}


	//用户详细信息
	public function profile()
	{
		$level_one='用户';
		$level_two='用户信息';
		$this->assign('level_one',$level_one);
		$this->assign('level_two',$level_two);
		$this->display('profile');
	}


	//网站登录
	public function login()
	{
		if(IS_POST)
		{
			$username=I('post.username');
			$password=I('post.password');
			if(!empty($username))
			{
				$map['username']=$username;
			}
			$User=M('admin');
			$admin=$User->where($map)->find();
			if($admin['password']==md5($password))
			{
				session_start();
				//记录用户名
				$_SESSION['fd_username'] = $admin['username'];
				//记录用户的session凭证
				$login_voucher = md5(I('server.REQUEST_TIME')).md5($admin['username']).md5($admin['password']);
				$_SESSION['login_voucher'] = $login_voucher;

				$this->success('登录成功!',U('Admin/Index/index'));

			}
			else
			{
				$this->error('登录失败!');
			}
		}
		else
		{
			$this->display('login');
		}
	}



	//注销登录
	public function logout()
	{
		session(null);
		$this->success('注销成功!',U('Admin/Index/login'));
	}


}