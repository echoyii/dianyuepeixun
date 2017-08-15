<?php
namespace Admin\Controller;
use Think\Controller;
class ProductController extends CommonController{
	public function lists()
	{
		if(IS_GET){
			$level_one[]='>产品管理';
			$level_one[]="";
			$level_two[]='>产品列表';
			$level_two[]=U('Admin/Product/lists');
			$level_three[]='';
			$level_three[]=U('Admin/User/add_user');
			$this->assign('level_one',$level_one);
			$this->assign('level_two',$level_two);
			$this->assign('level_three',$level_three);

			//////////////////////////////////////////////////////////////////
			$User=M('product');
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


			//查询产品对应的分类
			foreach ($list as $k => $v) 
			{
				$map['id']=$v['classify'];
				$list[$k]['classify']=M('food_classify')->where($map)->getField('name');
			}

			// print_r($list); die;
			$this->assign('list',$list);
			$this->assign('page',$show);
			$this->display('lists');
		}
	}


	//添加产品
	public function add_product()
	{
		if(IS_POST)
		{
			$name=I('post.name');
			$brand=I('post.brand');
			$classify_son=I('post.classify_son');
			$barcode=I('post.barcode');
			$remark=I('post.remark');
			// echo $classify_son; die;

			if(!empty($name))
			{
				$data['name']=$name;
			}
			if(!empty($brand))
			{
				$data['brand']=$brand;
			}
			if(!empty($classify_son))
			{
				$data['classify']=$classify_son;
			}
			if(!empty($barcode))
			{
				$data['barcode']=$barcode;
			}
			if(!empty($remark))
			{
				$data['remark']=$remark;
			}
			// print_r($_FILES); die;
			if(!empty($_FILES['product_img']['size'])){

				$data['imgurl'] =__ROOT__.'/Uploads/'.$this->upload();
				// echo $data['imgurl']; die;
			}

			$data['ctime']=I('server.REQUEST_TIME');
			$User=M('product');
			$re=$User->data($data)->add();
			if($re)
			{
				$this->success('添加成功!',U('Admin/Product/lists'));
			}
			else
			{
				$this->error('添加失败!');
			}

		}
		else
		{
			$level_one[]='>产品管理';
			$level_one[]="";
			$level_two[]='>产品列表';
			$level_two[]=U('Admin/Product/lists');
			$level_three[]='>添加产品';
			$level_three[]=U('Admin/Product/add_product');
			$this->assign('level_one',$level_one);
			$this->assign('level_two',$level_two);
			$this->assign('level_three',$level_three);

			///////////////////////////////////////////////////////////////////
			$User=M('food_classify');
			$map['pid']=0;
			$map['state']=1;
			$list=$User->where($map)->select();
			$this->assign('list',$list);
			$this->display('add_product');
		}
		
	}

	//文件上传累
	public function upload()
	{
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =     5242880 ;// 设置附件上传大小
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath  =      'product/original/'; // 设置附件上传目录
		// 上传文件 
		$info   =   $upload->uploadOne($_FILES['product_img']);
		if(!$info)
		{
		// 上传错误提示错误信息    
			$this->error($upload->getError());
		}
		else
		{
			// 上传成功 获取上传文件信息    
			return $info['savepath'].$info['savename'];    
		}
	}

	// 图像等比例缩小裁剪
	// public function img_thumb1($img_url)
	// {
	// 	$image = new \Think\Image(); 
	// 	$img_url = './'.$img_url;
	// 	$image->open("$img_url");
	// 	$img_name=$this->img_name().'.jpg';
	// 	$re_small_url=__ROOT__.'/'.'Uploads'.'/'.'product'.'/'.'change_small'.'/'.$img_name;
	// 	$small_url='./'.'Uploads'.'/'.'product'.'/'.'change_small'.'/'.$img_name;
	// 	// echo $small_url; die;
	// 	// 生成一个居中裁剪为30*30的缩略图并保存
	// 	$image->thumb(50, 50,\Think\Image::IMAGE_THUMB_FILLED)->save($small_url);
	// 	return $re_small_url;
	// }

	//删除产品
	public function del_product()
	{
		$id=I('post.id');

		if(!empty($id))
		{
			$map['id']=$id;
		}
		$User=M('product');
		$re=$User->where($map)->delete();

		if($re)
		{

			echo json_encode(1);
		}
	}


	//修改产品信息
	public function update_product()
	{
		if(IS_POST)
		{
			$id=I('post.id');
			$name=I('post.name');
			$brand=I('post.brand');
			$classify_son=I('post.classify_son');
			$barcode=I('post.barcode');
			$remark=I('post.remark');
			// echo $classify_son; die;


			if(!empty($name))
			{
				$map['id']=$id;
			}
			if(!empty($name))
			{
				$data['name']=$name;
			}
			if(!empty($brand))
			{
				$data['brand']=$brand;
			}
			if(!empty($classify_son))
			{
				$data['classify']=$classify_son;
			}
			if(!empty($barcode))
			{
				$data['barcode']=$barcode;
			}
			if(!empty($remark))
			{
				$data['remark']=$remark;
			}

			// print_r($_FILES); die;
			if(!empty($_FILES['product_img']['size'])){


				$data['imgurl'] =__ROOT__.'/Uploads/'.$this->upload();
				// echo $data['imgurl']; die;
			}

			$User=M('product');
			$re=$User->where($map)->data($data)->save();
			if($re)
			{
				$this->success('修改成功!',U('Admin/Product/lists'));
			}
			else
			{
				$this->error('修改失败!');
			}

		}
		else
		{
			$id=I('get.id');

			if(!empty($id))
			{
				$map['id']=$id;
			}

			$list=M('product')->where($map)->select();
			// print_r($list); die;

			$User_2=M('food_classify');
			foreach ($list as $k => $v) 
			{
				$map['id']=$v['classify'];
				$list[$k]['classify']=$User_2->where($map)->find();
				$mas['id']=$list[$k]['classify']['pid'];
				$list[$k]['f_classify']=$User_2->where($mas)->getField('name');
			}

			$gap['pid']=0;
			$gap['state']=1;
			$lists=M('food_classify')->where($gap)->select();


			$this->assign('list',$list);
			$this->assign('lists',$lists);


			$this->display('update_product');
		}
	}

	//产品分类管理->分类列表
	public function classify_lists()
	{
		if(IS_GET)
		{
			$level_one[]='>产品管理';
			$level_one[]="";
			$level_two[]='>分类列表';
			$level_two[]=U('Admin/Product/classify_lists');
			$level_three[]='';
			$level_three[]=U('Admin/User/add_user');
			$this->assign('level_one',$level_one);
			$this->assign('level_two',$level_two);
			$this->assign('level_three',$level_three);
			$User=M('food_classify');
			$map['state']=1;
			$map['pid']=0;
			$list=$User->where($map)->select();
			foreach ($list as $k => $v) 
			{
				if($v['state']==1)
				{
					$list[$k]['state']='显示';
				}
				else
				{
					$list[$k]['state']='隐藏';
				}
			}
			// print_r($list); die;
			$this->assign('list',$list);
			$this->display('classify_lists');
		}
	}

	//添加主分类
	public function add_classify()
	{
		if(IS_POST)
		{
			$name=I('post.name');
			$state=I('post.state');
			if(!empty($name))
			{
				$data['name']=$name;
			}else{
				$this->error('分类名不能为空!','',1);
			}
			if(isset($state))
			{
				$data['state']=$state;
			}

			if(!empty($_FILES['classify_img']['name'])){

				$data['thumb'] = __ROOT__.'/Uploads'.'/'.$this->classify_upload($_FILES['classify_img']);

			}
			$data['pid']=0;
			$User=M('food_classify');
			if($User->data($data)->add())
			{
				$this->success('添加成功！',U('Admin/Product/classify_lists'));
			}
			else
			{
				$this->error('添加失败！');
			}
		}
		else
		{
			$level_one[]='>产品管理';
			$level_one[]="";
			$level_two[]='>分类列表';
			$level_two[]=U('Admin/Product/classify_lists');
			$level_three[]='>添加主分类';
			$level_three[]=U('Admin/Product/add_classify');
			$this->assign('level_one',$level_one);
			$this->assign('level_two',$level_two);
			$this->assign('level_three',$level_three);
			$this->display('add_classify');
		}

		
	}


	//主分类图片上传
	private function classify_upload($file){    
		$upload = new \Think\Upload();// 实例化上传类    
		$upload->maxSize   =     3145728 ;// 设置附件上传大小    
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型    
		$upload->savePath  =      'product/classify/'; // 设置附件上传目录    // 上传单个文件     
		$info   =   $upload->uploadOne($file);    
		if(!$info) {
		// 上传错误提示错误信息        
			$this->error($upload->getError());    
		}else{
		// 上传成功 获取上传文件信息         
			return $info['savepath'].$info['savename'];    
		}
	}



	//添加子分类
	public function add_son_classify()
	{
		if(IS_POST) 
		{
			$id=I('post.id');
			$name=I('post.name');
			$state=I('post.state');
			if(!empty($id))
			{
				$data['pid']=$id;
			}
			if(!empty($name))
			{
				$data['name']=$name;
			}
			if(isset($state))
			{
				$data['state']=$state;
			}
			$re=M('food_classify')->data($data)->add();
			if($re)
			{
				$this->success('添加成功！');
			}
		}
		else
		{
			$level_one[]='>分类管理';
			$level_one[]="";
			$level_two[]='>分类列表';
			$level_two[]=U('Admin/Product/classify_lists');
			$level_three[]='>添加子分类';
			$level_three[]=U('Admin/Product/add_son_classify');
			$this->assign('level_one',$level_one);
			$this->assign('level_two',$level_two);
			$this->assign('level_three',$level_three);
			$id=I('get.id');
			if(!empty($id))
			{
				$map['id']=$id;
			}
			$list=M('food_classify')->where($map)->find();
			$this->assign('list',$list);
			$this->display('add_son_classify');
		}
	}

	//删除主分类
	public function del_classify()
	{
		if(IS_AJAX)
		{
			$id=I('post.id');
			if(!empty($id))
			{
				$map['id']=$id;
			}
			$User=M('food_classify');
			$re=$User->where($map)->delete();
			if($re)
			{
				echo json_encode(1);
			}

		}
	}

	//修改主分类
	public function update_classify()
	{
		if(IS_POST){
			$id=I('post.id');
			$name=I('post.name');
			$state=I('post.state');
			if(!empty($name))
			{
				$data['name']=$name;
			}else{

				$this->error('分类名不能为空!','',1);
			}
			if(isset($state))
			{
				$data['state']=$state;
			}
			if(!empty($id))
			{
				$map['id']=$id;
			}else{

				$this->error('参数出错!','',1);
			}
			// print_r($_FILES); die;
			if(!empty($_FILES['classify_img']['name'])){

				$data['thumb'] = __ROOT__.'/Uploads'.'/'.$this->classify_upload($_FILES['classify_img']);

			}

			// print_r($data); die;
			$re=M('food_classify')->where($map)->data($data)->save();
			if($re)
			{
				$this->success('修改成功！',U('Admin/Product/classify_lists'));
			}
			else
			{
				$this->error('修改失败!','',1);
			}
		}
		else
		{
			$level_one[]='>产品管理';
			$level_one[]="";
			$level_two[]='>分类列表';
			$level_two[]=U('Admin/Product/classify_lists');
			$level_three[]='>修改分类';
			$level_three[]=U('Admin/Product/update_classify');
			$this->assign('level_one',$level_one);
			$this->assign('level_two',$level_two);
			$this->assign('level_three',$level_three);
			$id=I('get.id');
			if(!empty($id))
			{
				$map['id']=$id;
			}
			$list=M('food_classify')->where($map)->find();
			$this->assign('list',$list);
			$this->display('update_classify');
		}
	}

	//查看主分类所属的子分类
	public function classify_lists_son()
	{
		if(IS_GET)
		{
			$level_one[]='>产品管理';
			$level_one[]="";
			$level_two[]='>分类列表';
			$level_two[]=U('Admin/Product/classify_lists');
			$level_three[]='>子类列表';
			$level_three[]=U('Admin/Product/classify_lists_son');
			$this->assign('level_one',$level_one);
			$this->assign('level_two',$level_two);
			$this->assign('level_three',$level_three);
			$id=I('get.id');
			$fname=I('get.name');
			if(!empty($id))
			{
				$map['pid']=$id;
			}
			$User=M('food_classify');
			$map['state']=1;
			$list=$User->where($map)->select();
			foreach ($list as $k => $v) 
			{
				if($v['state']==1)
				{
					$list[$k]['state']='显示';
				}
				else
				{
					$list[$k]['state']='隐藏';
				}
			}
			$this->assign('fname',$fname);
			$this->assign('list',$list);
			$this->display('classify_lists_son');
		}
		
	}

	//删除子分类
	public function del_classify_son()
	{
		if(IS_AJAX)
		{
			$id=I('post.id');
			if(!empty($id))
			{
				$map['id']=$id;
			}
			$User=M('food_classify');
			$re=$User->where($map)->delete();
			if($re)
			{
				echo json_encode(1);
			}

		}
	}

	//修改子分类
	public function update_classify_son()
	{
		if(IS_POST)
		{
			$id=I('post.id');
			$name=I('post.name');
			$state=I('post.state');
			if(!empty($name))
			{
				$data['name']=$name;
			}
			if(isset($state))
			{
				$data['state']=$state;
			}
			if(!empty($id))
			{
				$map['id']=$id;
			}
			$re=M('food_classify')->where($map)->data($data)->save();
			if($re)
			{
				$this->success('修改成功！',U('Admin/Product/classify_lists_son'));
			}
			else
			{
				$this->error('修改失败！');
			}
		}
		else
		{
			$id=I('get.id');
			if(!empty($id))
			{
				$map['id']=$id;
			}
			$list=M('food_classify')->where($map)->find();
			$this->assign('list',$list);
			$this->display('update_classify_son');
		}
	}


	//ajax获取对应的二级目录
	public function ready_classify()
	{
		$map['pid']=I('post.id');
		$User=M('food_classify');
		$list=$User->where($map)->select();
		echo json_encode($list);
	}



	//修改添加分类所包含的检测套餐
	public function set_package(){

		if(IS_GET){
			//取出所有的检测项目
			$DB_package = M('fundings_package');
			$package_lists = $DB_package->select();

			//取出该分类已选择的项目
			
			$classify_id = I('get.id');
			if(!empty($classify_id)){
				$map['classify_id'] = $classify_id;
				$this->assign('classify_id',$classify_id);
			}else{

				$this->error('id参数错误!','',1);
			}
			$DB_classify_package_relationship = M('classify_package_relationship');
			$package_id = $DB_classify_package_relationship->where($map)->getField('package_id',true);
			//检测该分类所包含的套餐
			foreach ($package_lists as $k => $v) {
				# code...
				if(in_array($v['id'], $package_id)){

					$package_lists[$k]['checked'] = 1;
				}
			}

			$classify_name = I('get.sname');
			// echo $classify_name;die;
			if(!empty($classify_name)){

				$this->assign('classify_name',$classify_name);
			}else{

				$this->error('name参数错误!','',1);
			}
			// print_r($package_lists); die;
			$this->assign('package_lists',$package_lists);
			$this->display();
		}else if(IS_POST){

			$classify_id_p = I('post.classify_id');
			if(!empty($classify_id_p)){

				$classify_id = $classify_id_p;
			}else{

				$this->error('参数错误!','',1);
			}

			$package_id = I('post.package_id');
			if(!empty($package_id)){

				foreach ($package_id as $k => $v) {
					# code...
					$data[] = array('classify_id' => $classify_id,'package_id' => $v,'ctime' => I('server.REQUEST_TIME'));
				}
			}
			// print_r($data);
			$DB = M('classify_package_relationship');
			//插入数据前先对之前数据进行清理更新
			$DB->where(array('classify_id' => $classify_id))->delete();
			$result = $DB->addAll($data);
			// var_dump($result); die;
			if(!empty($result)){

				$this->success('设置成功!',U('Admin/Product/classify_lists'),1);
			}else{

				$this->error('设置失败!','',1);
			}
		}
	}
}