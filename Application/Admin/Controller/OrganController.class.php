<?php
namespace Admin\Controller;
use Think\Controller;
class OrganController extends CommonController{
	public function index()
	{

	}

	//检测机构列表
	public function lists()
	{
		$level_one[]='>检测机构';
		$level_one[]="";
		$level_two[]='>检测机构列表';
		$level_two[]=U('Admin/Organ/lists');
		$level_three[]='';
		$level_three[]=U('Admin/Product/classify_lists_son');
		$this->assign('level_one',$level_one);
		$this->assign('level_two',$level_two);
		$this->assign('level_three',$level_three);
		$User=M('institution');
		$list=$User->select();
		$this->assign('list',$list);
		$this->display('lists');
	}

	//修改检测机构
	public function update()
	{
		if(!IS_POST)
		{
			$level_one[]='>检测机构';
			$level_one[]="";
			$level_two[]='>检测机构列表';
			$level_two[]=U('Admin/Organ/lists');
			$level_three[]='>修改机构信息';
			$level_three[]=U('Admin/Organ/update');
			$this->assign('level_one',$level_one);
			$this->assign('level_two',$level_two);
			$this->assign('level_three',$level_three);
			$id=I('get.id');
			$map['id']=$id;
			$list=M('institution')->where($map)->find();
			$this->assign('list',$list);
			$this->display('update');
		}
		else
		{
			// print_r($_FILES); die;
			$data['name']=I('post.name');
			$data['address']=I('post.address');
			$data['content']=I('post.content');
			$data['phone']=I('post.phone');
			$data['url']=I('post.url');

			if(!empty($_FILES['logo']['name']))
			{
				$logo=$this->upload($_FILES['logo']);
				$logo = 'Uploads'.'/'.$logo;
			}

			if(!empty($_FILES['photo']['name']))
			{
				$photo=$this->upload($_FILES['photo']);
				$photo = 'Uploads'.'/'.$photo;
			}
			
			if($logo)
			{
				$logo_url=$this->logo_thumb($logo);
				$data['logo']=$logo_url;
			}

			if($photo)
			{
				$img_url=$this->img_thumb($photo);
				$data['img']=$img_url;
			}
				
			$map['id']=I('post.id');
			M('institution')->where($map)->data($data)->save();
			$this->success('修改成功！',U('Admin/Organ/lists'));
		}

		
	}

	//删除检测机构
	public function del()
	{
		if(IS_AJAX){
			$id=I('post.id');
			if(!empty($id))
			{
				$map['id']=$id;
			}
			$re = M('institution')->where($map)->delete();
			
			if(!empty($re)){

				echo json_encode(1);
			}
			
		}
		
	}

	//检测机构添加
	public function add()
	{
		if(IS_POST)
		{
			$name=I('post.name');
			$content=I('post.content');
			$address=I('post.address');
			$phone=I('post.phone');
			$url=I('post.url');

			if(!empty($name))
			{
				$data['name']=$name;
			}
			if(!empty($content))
			{
				$data['content']=$content;
			}
			if(!empty($address))
			{
				$data['address']=$address;
			}
			if(!empty($phone))
			{
				$data['phone']=$phone;
			}
			if(!empty($url))
			{
				$data['url']=$url;
			}

			if(!empty($_FILES['logo']['name']))
			{
				$logo=$this->upload($_FILES['logo']);
				$logo = 'Uploads'.'/'.$logo;
				if($logo)
				{
					$logo_url=$this->logo_thumb($logo);
					$data['logo']=$logo_url;
				}
			}
			if(!empty($_FILES['photo']['name']))
			{
				$photo=$this->upload($_FILES['photo']);
				$photo = 'Uploads'.'/'.$photo;
				if($photo)
				{
					$img_url=$this->img_thumb($photo);
					$data['img']=$img_url;
				}
			}

		
			if(empty($data))
			{

				$this->error('非法数据添加!');
			}
			
			$User=M('institution');
			$re=$User->data($data)->add();
			if($re)
			{
				$this->success('添加成功！',U('Admin/Organ/lists'));
			}
			else
			{
				$this->error('添加失败!');
			}
			

		}
		else
		{
			$level_one[]='>检测机构';
			$level_one[]="";
			$level_two[]='>检测机构列表';
			$level_two[]=U('Admin/Organ/lists');
			$level_three[]='>添加检测机构';
			$level_three[]=U('Admin/Organ/add');
			$this->assign('level_one',$level_one);
			$this->assign('level_two',$level_two);
			$this->assign('level_three',$level_three);
			$this->display('add');
		}
		
	}



	//图片上传
	public function upload($upfile)
	{
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =     3145728 ;// 设置附件上传大小
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath  =      'organ/original/'; // 设置附件上传目录
		// 上传文件 
		$upfile=$upfile;
		$info   =   $upload->uploadOne($upfile);
		if(!$info)
		{
		// 上传错误提示错误信息    
			$this->error($upload->getError());
		}
		else{
			// 上传成功 获取上传文件信息      
			        
			$img_url=$info['savepath'].$info['savename'];    
		}
			return $img_url;

	}

	//图像等比例缩小裁剪
	public function logo_thumb($img_url)
	{
		$image = new \Think\Image(); 
		$img_url = './'.$img_url;
		$image->open("$img_url");
		$img_name=$this->img_name().'.jpg';
		$re_small_url=__ROOT__.'/'.'Uploads'.'/'.'organ'.'/'.'change_small'.'/'.$img_name;
		$small_url='./'.'Uploads'.'/'.'organ'.'/'.'change_small'.'/'.$img_name;
		// echo $small_url; die;
		// 生成一个居中裁剪为30*30的缩略图并保存
		$image->thumb(130, 157,\Think\Image::IMAGE_THUMB_CENTER)->save($small_url);
		return $re_small_url;
	}

		//图像等比例缩小裁剪
	public function img_thumb($img_url)
	{
		$image = new \Think\Image(); 
		$img_url = './'.$img_url;
		$image->open("$img_url");
		$img_name=$this->img_name().'.jpg';
		$re_small_url=__ROOT__.'/'.'Uploads'.'/'.'organ'.'/'.'img'.'/'.$img_name;
		$small_url='./'.'Uploads'.'/'.'organ'.'/'.'img'.'/'.$img_name;
		// echo $small_url; die;
		// 生成一个居中裁剪为30*30的缩略图并保存
		$image->thumb(100, 100,\Think\Image::IMAGE_THUMB_SCALE)->save($small_url);
		return $re_small_url;
	}


	//生成随机图像名称
	public function img_name()
	{
		$time=I('server.REQUEST_TIME');
		return $time.str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
	}
}