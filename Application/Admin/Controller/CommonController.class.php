<?php
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller {
    public function _initialize()
    {
        //当进入页面不为login时 检测用户的登录状态
        if(ACTION_NAME!='login')
        {
            $this->chk_login();
        }	


        //检测用户是否有操作权限
        if(CONTROLLER_NAME!='Index')
        {
            $this->chk_node();
        }

        //定义唯一的标签名字
        $menu_select_tag = CONTROLLER_NAME."_".ACTION_NAME;
        $this->assign('menu_select_tag',$menu_select_tag);	
    }


    //检测登录状态
    public function chk_login()
    {
        
        //检测session的登录时间是否为空
    	$login_voucher=$_SESSION['login_voucher'];
    	// var_dump($login_voucher); die;
    	if(!empty($login_voucher))
        {
            //当为登录状态是 将用户名赋值到模板
    		$this->assign('wel_username',session('fd_username'));
    	}
        else
        {
    		$this->error('请先登录!',U('Admin/Index/login'));
    		
    	}
    }


    //检测用户是否用有权限操作
    public function chk_node()
    {
        $chk_name = CONTROLLER_NAME.'_'.ACTION_NAME;
        $username = session('fd_username');

        //当登录用户不为超级管理员admin时，检测用户是否拥有操作权限
        if($username != 'admin')
        {
            $list = M('admin')->where(array('username' => $username))->find();

            //将字符串以‘,’为节点拆分为数组
            $list['permission_area'] = explode(',',$list['permission_area']);

            //循环permission_area 并替换权限id
            foreach ($list['permission_area'] as $key => $value) 
            {
                # code...
                $list['permission_area'][$key] = M('node')->where(array('id' => $value))->find();
            }

            //循环查询cid所表示的控制器
            foreach ($list['permission_area'] as $k => $v) 
            {
                # code...
                $list['permission_area'][$k]['cid'] = M('controller')->where(array('id' => $v['cid']))->getField('name');
                $list['permission_area'][$k]['node_name'] = $v['cid'].'_'.$v['name'];
            }

            //将数组中的控制器名和方法名用'_'连接
            foreach ($list['permission_area'] as $k => $v) 
            {
                # code...
                $list['permission_area'][$k]['node_name'] = $v['cid'].'_'.$v['name'];
                $list['node'][] = $list['permission_area'][$k]['node_name'];
            }

            $node=$list['node'];

            //检测当前用户所操作的方法是否包含在权限表中
            if(!in_array($chk_name,$node)){
                $this->error('没有操作权限!请联系管理员!');
            }
        }

        
    }
}