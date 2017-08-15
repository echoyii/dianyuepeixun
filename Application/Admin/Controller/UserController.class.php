<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends CommonController{

	public function admin_lists()
	{
		if(!IS_POST)
		{

			$User = M('admin');
			$list = $User->select();

			//循环替换时间
			//取出管理找好对应的管理权限
			//
			foreach ($list as $k => $v) 
			{
				
				$list[$k]['ctime'] = date('Y-m-d H:i:s',$v['ctime']);
				$list[$k]['utime'] = date('Y-m-d H:i:s',$v['utime']);
				$list[$k]['permission_area'] = explode(',', $v['permission_area']);
				foreach ($list[$k]['permission_area'] as $key => $value) 
				{
					# code...
					$list[$k]['permission_area'][$key] = M('node')->where(array('id' => $value))->getField('content');
				}
				$list[$k]['permission_area'] = implode(',', $list[$k]['permission_area']);
			}



			$this->assign('list',$list);

			$level_one[]='>用户管理';
			$level_one[]="";
			$level_two[]='>管理员管理';
			$level_two[]='';
			$level_three[]='>管理员列表';
			$level_three[]=U('Admin/User/admin_lists');
			$this->assign('level_one',$level_one);
			$this->assign('level_two',$level_two);
			$this->assign('level_three',$level_three);
			$this->display('admin_lists');
		}
		
	}

	//用户管理页面
	public function user_lists()
	{
		$level_one[]='>会员管理';
		$level_one[]="";
		$level_two[]='>用户列表';
		$level_two[]=U('Admin/User/user_lists');
		$level_three[]='';
		$level_three[]="";
		$this->assign('level_one',$level_one);
		$this->assign('level_two',$level_two);
		$this->assign('level_three',$level_three);
		$User=M('members');
		/**
		 * 分页输出用户列表
		 */
		/**
			数据分页显示开始
			**/
			$count      = $User->count();// 查询满足要求的总记录数
			$Page       = new \Think\Page($count,40);// 实例化分页类 传入总记录数和每页显示的记录数(25)
			$Page->lastSuffix=false;
		    $Page->setConfig('header','<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;&nbsp;每页<b>40</b>条&nbsp;&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
		    $Page->setConfig('prev','上一页');
		    $Page->setConfig('next','下一页');
		    $Page->setConfig('last','末页');
		    $Page->setConfig('first','首页');
		    $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
			$show       = $Page->show();// 分页显示输出
			// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
			$list = $User->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			/**
			数据分页结束
			**/
		// $list = $User->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach ($list as $k => $v) 
		{
			$list[$k]['ctime']=date('Y-m-d H:i:s',$v['ctime']);
			$list[$k]['utime']=date('Y-m-d H:i:s',$v['utime']);
		}

		//获取所有的分组项目
		$group_lists = M('members_group')->select();

		$this->assign('group_lists',$group_lists);
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display('user_lists');
	}

	//用户添加页面
	public function add_user()
	{
		$level_one[]='>会员管理';
		$level_one[]="";
		$level_two[]='>用户列表';
		$level_two[]=U('Admin/User/user_lists');
		$level_three[]='>添加会员';
		$level_three[]=U('Admin/User/add_user');
		$this->assign('level_one',$level_one);
		$this->assign('level_two',$level_two);
		$this->assign('level_three',$level_three);
		if(IS_POST)
		{
			$mobile=I('post.mobile');
			$state=I('post.state');

			if(!empty($mobile))
			{
				$data['mobile']=trim($mobile);
			}

			if(!empty($state))
			{
				$data['state']=trim($state);
			}

			$password1=I('post.password1');
			$password2=I('post.password2');

			if($password1==$password2)
			{
				$data['password']= md5($password1);
			}
			else
			{
				$this->error('密码不一致请重新输入');
			}
			
			$data['username']=$data['mobile'];

			$gender = I('post.gender');
			if(isset($gender)){

				$data['gender'] = $gender;
			}

			$year = I('post.year');
			$month = I('post.month');
			$day = I('post.day');
			if(!empty($year) && !empty($month) && !empty($day)){

				$data['birthday'] = $year.'-'.$month.'-'.$day;
			}

			$address = I('post.address');
			if(!empty($address)){

				$data['address'] = $address;
			}
			// print_r($data); die;
			if(!empty($_FILES['avatar']['size']))
			{
				$avatar='Uploads'.'/'.$this->upload();
				$avatar_url=$this->img_thumb($avatar);
				$data['avatar']=$avatar_url;
			}
			
			$data['ctime']=I('server.REQUEST_TIME');
			$User=M('members');
			$re=$User->data($data)->add();
			if($re)
			{
				$this->success('添加成功!',U('Admin/User/user_lists'));
			}
			else
			{
				$this->error('添加失败!');
			}
		}
		else
		{
			$this->display('add_user');
		}	
	}

	//用户删除
	public function del_user()
	{
		$id=I('post.id');
		$map['id']=$id;
		$re = M('members')->where($map)->delete();

		if(!empty($re)){

			echo json_encode(1);
		}
		
	}


	//自动截取生日
    private function sub_str_birthday($bir){

        $birthday['year'] = substr($bir,0,4);
        $birthday['month'] = substr($bir,5,2);
        $birthday['day'] = substr($bir,8,2);

        if(substr($birthday['month'],0,1)=='0'){
            $birthday['month'] = substr($birthday['month'],1,1);
        }else{
            $birthday['month'] = $birthday['month'];
        }

        if(substr($birthday['day'],0,1)=='0'){
            $birthday['day'] = substr($birthday['day'],1,1);
        }else{
            $birthday['day'] = $birthday['day'];
        }
        return $birthday;
    }

	//	用户信息修改更新
	public function update_user()
	{
		$id=I('get.id');
		if(!IS_POST)
		{
			$level_one[]='>用户管理';
			$level_one[]="";
			$level_two[]='>用户列表';
			$level_two[]=U('Admin/User/user_lists');
			$level_three[]='>修改用户信息';
			$level_three[]=U('Admin/User/update_user');
			$this->assign('level_one',$level_one);
			$this->assign('level_two',$level_two);
			$this->assign('level_three',$level_three);


			$map['id']=$id;
			$list=M('members')->where($map)->find();
			$list['birthday'] = $this->sub_str_birthday($list['birthday']);
			if($list['state']==1)
			{
				$list['state']='激活';
			}
			else
			{
				$list['state']='冻结';
			}
			$this->assign('list',$list);
			$this->display('update_user');

		}
		else
		{

			$state=I('post.state');
			if(isset($state))
			{
				$data['state']=trim($state);
			}

			$password1=I('post.password1');
			$password2=I('post.password2');

			if($password1==$password2)
			{
				$data['password']= md5($password1);
			}
			else
			{
				$this->error('密码不一致请重新输入');
			}

			$gender = I('post.gender');
			if(isset($gender)){

				$data['gender'] = $gender;
			}

			$year = I('post.year');
			$month = I('post.month');
			$day = I('post.day');
			if(!empty($year) && !empty($month) && !empty($day)){

				$data['birthday'] = $year.'-'.$month.'-'.$day;
			}

			$address = I('post.address');
			if(!empty($address)){

				$data['address'] = $address;
			}
			// print_r($data); die;
			if(!empty($_FILES['avatar']['size']))
			{
				$avatar='Uploads'.'/'.$this->upload();
				$avatar_url=$this->img_thumb($avatar);
				$data['avatar']=$avatar_url;
			}
			
			$id = I('post.id');
			if(!empty($id)){

				$map['id'] = $id;
			}else{

				$this->error('参数出错!','',1);
			}

			$data['ctime']=I('server.REQUEST_TIME');
			$User=M('members');
			$re=$User->where($map)->data($data)->save();

			if(!empty($re)){

				$this->success('修改成功!',U('Admin/User/user_lists'),1);
			}
		}

		
	}


	//节点列表
	public function node()
	{
		if(IS_POST)
		{

		}
		else
		{

			$User = M('node');
			$list = $User->select();

			foreach ($list as $k => $v) 
			{
				# code...'
				$list[$k]['cid'] = M('controller')->where(array('id' => $v['cid']))->getField('content');
			}
			$this->assign('list',$list);
			//节电列表
			$this->display('node');


		}
	}

	//添加节点
	//
	public function add_node()
	{
		if(IS_POST)
		{

			$name=I('post.name');
			if(!empty($name))
			{
				$data['name'] = $name;
			}

			$content = I('post.content');
			if(!empty($content))
			{
				$data['content'] = $content;
			}

			$cid = I('post.cid');
			if(!empty($cid))
			{
				$data['cid'] = $cid;
			}

			$User = M('node');
			$re=$User->data($data)->add();

			if($re)
			{
				$this->success('添加成功!');
			}
			else
			{
				$this->error('添加失败!');
			}

		}
		else
		{
			$User=M('controller');
			$list=$User->select();

			$this->assign('list',$list);

			$this->display('add_node');
		}
	}


	//ajax删除节点
	public function del_node()
	{

		$id = I('post.id');
		if(!empty($id))
		{
			$map['id'] = $id;
		}

		$re = M('node')->where($map)->delete();
		if($re)
		{
			echo json_encode(1);
		}
	}


	//修改节点
	public function update_node()
	{

		if(IS_POST)
		{
			$id = I('post.id');
			if(!empty($id))
			{
				$map['id'] = $id;
			}

			$name = I('post.name');
			if(!empty($name))
			{
				$data['name'] = $name;
			}

			$cid = I('post.cid');
			if(!empty($cid))
			{
				$data['cid'] = $cid;
			}


			$content = I('post.content');
			if(!empty($content))
			{
				$data['content'] = $content;
			}
			$re = M('node')->where($map)->data($data)->save();

			if($re)
			{
				$this->success('修改成功!',U('Admin/User/node'));
			}
			else
			{
				$this->error('修改失败!');
			}

		}
		else
		{
			$id = I('get.id');
			// echo $id; die;
			if(!empty($id)){
				$map['id'] = $id;
			}

			$list = M('node')->where($map)->find();
			$this->assign('list',$list);

			$User=M('controller');
			$controller=$User->select();

			$this->assign('controller',$controller);


			$this->display('update_node');
		}

		


	}

	//管理员添加页面
	public function add_admin()
	{

		if(IS_POST)
		{

			$username = I('post.username');
			if(!empty($username))
			{
				$data['username'] = $username;
			}
			else
			{
				$this->error('用户名不能为空!');
			}

			$passwordone = I('post.passwordone');
			$passwordtwo = I('post.passwordtwo');
			if(!empty($passwordone) && !empty($passwordtwo))
			{
				if($passwordone == $passwordtwo)
				{
					$data['password'] = md5($passwordtwo);
				}
				else
				{
					$this->error('两次密码不一致请重新输入!');
				}
			}
			else
			{
				$this->error('密码不能为空!');
			}

			$node = I('post.node');

			if(!empty($node))
			{
				$permission_area = implode(',',$node);
				$data['permission_area'] = $permission_area;
			}
			$data['ctime'] = I('server.REQUEST_TIME');
			
			$User = M('admin');
			$re = $User->data($data)->add();

			if($re)
			{
				$this->success('添加成功!',U('Admin/User/admin_lists'));
			}
			else
			{
				$this->success('添加失败!');
			}


		}
		else
		{

			$User = M('node');
			$node = $User->select();
			$this->assign('node',$node);


			$level_one[]='>用户管理';
			$level_one[]="";
			$level_two[]='>管理员管理';
			$level_two[]='';
			$level_three[]='>添加管理员';
			$level_three[]=U('Admin/User/add_admin');
			$this->assign('level_one',$level_one);
			$this->assign('level_two',$level_two);
			$this->assign('level_three',$level_three);
			$this->display('add_admin');
		}


		
	}


	//修改管理员信息
	public function update_admin()
	{

		if(IS_POST)
		{
			$id = I('post.id');
			if(!empty($id))
			{
				$map['id'] = $id;
			}
			else
			{
				$this->error('参数出错!');
			}
			$username = I('post.username');
			if(!empty($username))
			{
				$data['username'] = $username;
			}

			$passwordone = I('post.passwordone');
			$passwordtwo = I('post.passwordtwo');
			if(!empty($passwordone) && !empty($passwordtwo))
			{
				if($passwordone == $passwordtwo)
				{
					$data['password'] = md5($passwordtwo);
				}
				else
				{
					$this->error('两次密码不一致请重新输入!');
				}
			}

			$node = I('post.node');

			if(!empty($node))
			{
				$permission_area = implode(',',$node);
				$data['permission_area'] = $permission_area;
			}

			// print_r($map); die;
			
			$User = M('admin');
			$re = $User->where($map)->data($data)->save();

			if($re)
			{
				$this->success('修改成功!',U('Admin/User/admin_lists'));
			}
			else
			{
				$this->success('修改失败!');
			}

		}
		else
		{

			$id = I('get.id');
			if(!empty($id))
			{
				$map['id'] = $id;
			}

			//得到要修改账号的全部信息
			$list = M('admin')->where($map)->find();
			if($list)
			{
				$permission_area = explode(',',$list['permission_area']);
				$node =  M('node')->select();

				//循环检测用户拥有的权限，拥有的打上勾
				foreach ($node as $k => $v) 
				{
					# code...
					if(in_array($v['id'], $permission_area))
					{
						$node[$k]['check'] = 1;
					}
				}
				
			}

			$this->assign('list',$list);
			$this->assign('node',$node);

			$this->display('update_admin');
		}

	}


	//删除管理员
	public function del_admin()
	{

		$id = I('post.id');

		if(!empty($id))
		{
			$map['id'] = $id;
		}

		$re = M('admin')->where($map)->delete();
		if($re)
		{
			echo json_encode(1);
		}

	}


	// //用户详情页面
	// public function user_details(){
	// 	$this->display('user_details');
	// }

	//文件上传累
	public function upload()
	{
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =     5242880 ;// 设置附件上传大小
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath  =      'head/original/'; // 设置附件上传目录
		// 上传文件 
		$info   =   $upload->upload();
		if(!$info)
		{
		// 上传错误提示错误信息    
			$this->error($upload->getError());}else{
			// 上传成功 获取上传文件信息    
			foreach($info as $file)
			{        
				return $file['savepath'].$file['savename'];    
			}
		}
	}

	//图像等比例缩小裁剪
	public function img_thumb($img_url)
	{
		$image = new \Think\Image(); 
		$img_url = './'.$img_url;
		$image->open("$img_url");
		$img_name=$this->img_name().'.jpg';
		$re_small_url=__ROOT__.'/'.'Uploads'.'/'.'head'.'/'.'change_small'.'/'.$img_name;
		$small_url='./'.'Uploads'.'/'.'head'.'/'.'change_small'.'/'.$img_name;
		// echo $small_url; die;
		// 生成一个居中裁剪为30*30的缩略图并保存
		$image->thumb(60, 60,\Think\Image::IMAGE_THUMB_CENTER)->save($small_url);
		return $re_small_url;
	}

	//生成随机图像名称
	public function img_name()
	{
		$time=I('server.REQUEST_TIME');
		return $time.str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
	}





	//用户分组页面
	public function group_lists()
	{

		if(!IS_POST)
		{

			$User = M('members_group');

			$list = $User->select();

			$this->assign('list',$list);
			$this->display();
		}
	}

	//ajax添加添加新的用户分组
	public function add_group()
	{

		$name = I('post.name');

		if(!empty($name))
		{

			$data['name'] = $name;
		}

		$data['ctime'] = I('server.REQUEST_TIME');

		$User = M('members_group');

		$re = $User->data($data)->add();

		if($re)
		{
			echo json_encode(1);
		}
		else
		{
			echo json_encode(0);
		}
	}


	//ajax删除分组
	public function del_group()
	{

		$id = I('post.id');

		if(!empty($id))
		{

			$map['id'] = $id;
		}

		$re = M('members_group')->where($map)->delete();

		if($re)
		{

			echo json_encode(1);
		}
		else
		{

			echo json_encode(0);
		}
	}

	//ajax修改分组名
	public function modify()
	{

		$id = I('post.id');
		if(!empty($id))
		{

			$map['id'] = $id;
		}

		$name = I('post.name');
		if(!empty($name))
		{

			$data['name'] = $name;
		}

		$re = M('members_group')->where($map)->data($data)->save();

		if($re)
		{

			echo json_encode(1);
		}
		else
		{

			echo json_encode(0);
		}
	}



	//ajax添加用户到额分组
	
	public function add_group_user()
	{
		$mid_array = I('post.mid');

		$gid = I('post.gid');



		foreach ($mid_array as $k => $v) 
		{
			if($this->chk_member_in_group($gid,$v))
			{
				$data[] = array('mid' => $v,'gid' => $gid);
			}
			
		}

		$User = M('members_group_user');

		$re = $User->addAll($data);

		if($re)
		{

			echo json_encode(1);
		}
		else
		{

			echo json_encode(0);
		}
		
	}

	/*
	* 判断用户是否存在这个分组
	 */
	private function chk_member_in_group($gid,$mid)
	{
		$map['gid'] = $gid;
		$map['mid'] = $mid;
		$group_member = M('members_group_user')->where($map)->find();

		if(empty($group_member))
		{
			return TRUE;
		}
		else
		{
			return false;
		}
	}


	/**
	查看分组所有用户
	**/
	public function members_group_user()
	{
		if(!IS_POST)
		{

			$gid = I('get.gid');
			if(!empty($gid))
			{

				$map['gid'] = $gid;
			}

			$list = M('members_group_user')->where($map)->select();

			$members = M('members');
			$members_group = M('members_group');

			foreach ($list as $k => $v) 
			{
				
				$list[$k]['m_id'] = $members->where(array('id' => $v['mid']))->getField('username');

				$list[$k]['g_id'] = $members_group->where(array('id' => $v['gid']))->getField('name');
			}
			// print_r($list); die;
			$this->assign('list',$list);
			$this->display();
		}
	}

	//ajax删除分组内的用户
	public function del_members_group_user()
	{

		$id = I('post.id');

		if(!empty($id))
		{

			$map['id'] = $id;
		}

		$re = M('members_group_user')->where($map)->delete();

		if($re)
		{
			echo json_encode(1);
		}
		else
		{
			echo json_encode(0);
		}
	}


	/**
	 * 用户反馈表
	 * 
	 */
	public function feedback(){

		if(IS_GET){

			$DB_feedback = M('feedback');

			/**
			数据分页显示开始
			**/
			$count      = $DB_feedback->count();// 查询满足要求的总记录数
			$Page       = new \Think\Page($count,40);// 实例化分页类 传入总记录数和每页显示的记录数(25)
			$Page->lastSuffix=false;
		    $Page->setConfig('header','<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;&nbsp;每页<b>40</b>条&nbsp;&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
		    $Page->setConfig('prev','上一页');
		    $Page->setConfig('next','下一页');
		    $Page->setConfig('last','末页');
		    $Page->setConfig('first','首页');
		    $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
			$show       = $Page->show();// 分页显示输出
			// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
			// $list = $DB_feedback->where(array('active' => 1))->join("fd_members ON fd_feedback.mid = fd_members.mid")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			/**
			数据分页结束
			**/
		 	$list = $DB_feedback->query("SELECT `fd_feedback`.*,`fd_members`.username AS username FROM fd_feedback LEFT JOIN fd_members ON fd_members.id = fd_feedback.mid  WHERE `active` = 1 ORDER BY id desc LIMIT 0,40");
			// print_r($list);
			foreach ($list as $k => $v) {
				# code...
				if($v['adopt'] == 0){

					$list[$k]['adopt'] = "<span style='background-color:#F90909; color:#fff;'>未采纳</span>";
				}else if($v['adopt'] == 1){

					$list[$k]['adopt'] = "<span style='background-color:#0EB58F; color:#fff;'>已采纳</span>";
				}
			}

			$this->assign('list',$list);
			$this->assign('show',$show);
			$this->display();
		}
	}


	/**
	 * ajax 删除反馈意见
	 */
	public function del_feedback(){

		if(IS_AJAX){
			$id = I('post.id');
			if(!empty($id)){

				$map['id'] = $id;
				$data['active'] = 0;
			}else{

				$this->error('参数错误!','',1);
			}

			$DB_feedback = M('feedback');


			$result = $DB_feedback->where($map)->data($data)->save();

			if(!empty($result)){

				echo json_encode(1);
			}else{
				echo json_encode(0);
			}
		}
	}

	/**
	 * ajax 采纳用户反馈意见
	 */
	
	public function take_on_feedback(){

		if(IS_AJAX){

			$id = I('post.id');
			if(!empty($id)){

				$map['id'] = $id;
				$data['adopt'] = 1;
			}else{

				$this->error('参数错误!','',1);
			}

			$DB_feedback = M('feedback');


			$result = $DB_feedback->where($map)->data($data)->save();

			if(!empty($result)){

				echo json_encode(1);
			}else{
				echo json_encode(0);
			}
		}
	}
}