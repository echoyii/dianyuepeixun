<?php
namespace Admin\Controller;
use Think\Controller;
class AdvController extends CommonController{


	//广告列表
	public function lists()
	{
		$level_one[]='>广告管理';
		$level_one[]="";
		$level_two[]='>广告列表';
		$level_two[]=U('Admin/Adv/lists');
		$level_three[]='';
		$level_three[]=U('Admin/User/add_user');
		$this->assign('level_one',$level_one);
		$this->assign('level_two',$level_two);
		$this->assign('level_three',$level_three);

		$User=M('adv');
		$list=$User->order('ttype asc,rank desc')->select();
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

			$list[$k]['ctime']=date('Y-m-d H:i:s',$v['ctime']);

			if($v['type']==0)
			{
				$list[$k]['statu']='项目广告';
			}
			else
			{
				if($v['isoutlink']==0)
				{
					$list[$k]['statu']='内部链接';
				}
				else
				{
					$list[$k]['statu']='外部链接';
				}
			}
		}
		// print_r($list);die;
		$this->assign('list',$list);
		$this->display('lists');
	}


	//添加广告
	public function add(){
		
		// echo $_SERVER['HTTP_HOST']; die;
		if(IS_POST)
		{
			$ttype = I('post.ttype');
			$type=I('post.type');
			$title=I('post.title');
			$isoutlink=I('post.isoutlink');
			$url=I('post.url');
			$content=I('post.content');
			$rank=I('post.rank');
			$state=I('post.state');
			$fdid = I('post.fdid');
			if(!empty($ttype)){

				$data['ttype'] = $ttype;
			}

			if(!empty($type)){
				
				if($isoutlink==1){
					$data['isoutlink']=1;
					$data['url']=$url;
				}else{

					$data['isoutlink']=0;
					$data['url']=$url;
				}

				$data['type']=1;

			}else{
				$data['type']=0;
				$data['fdid'] = $fdid;
			}

			if(!empty($title)){

				$data['title']=$title;
			}else{

				$this->error('标题不能为空!');
			}


			if(!empty($content))
			{
				$data['content']=$content;
			}

			if(isset($rank))
			{
				$data['rank']=$rank;
			}

			if(isset($state))
			{
				$data['state']=$state;
			}
			//对上传图片进行处理
			if(!empty($_FILES['photo']['name']))
			{
			//上传图片
				$photo='Uploads'.'/'.$this->upload($_FILES['photo']);
				// echo $photo; die;
				//对图片进行裁剪
				$img=$this->img_thumb2($photo);
				$mini_img=$this->img_thumb1($photo);
				$data['img']=$img;
				$data['mini_img']=$mini_img;
			}


			$data['ctime']=I('server.REQUEST_TIME');

			$User=M('adv');

			$adv_id = $User->data($data)->add();

			if(!empty($adv_id)){

				$map['id'] = $adv_id;
				if($isoutlink==0){
					$data['url'] = "http://".$_SERVER['HTTP_HOST'].__ROOT__."/index.php/Home/Essay/details/id/".$adv_id.".html";
					$re = $User->where($map)->data($data)->save();
					if(!empty($re)){

						$this->success('添加成功!',U('Admin/Adv/lists'));
					}else{

						$this->error('添加失败!');
					}
				}else{

					$this->success('添加成功!',U('Admin/Adv/lists'));
				}
			}else{
				$this->error('添加失败!');
			}
		
		}
		else{
				$level_one[]='>广告管理';
				$level_one[]="";
				$level_two[]='>广告列表';
				$level_two[]=U('Admin/Adv/lists');
				$level_three[]='>添加广告';
				$level_three[]=U('Admin/Adv/add');
				$this->assign('level_one',$level_one);
				$this->assign('level_two',$level_two);
				$this->assign('level_three',$level_three);
				$this->display('add');
		}
		
	}

	//删除广告
	public function del()
	{
		if(IS_AJAX){
			$id=I('post.id');

			if(!empty($id))
			{
				$map['id']=$id;
			}
			$User=M('adv');
			$re=$User->where($map)->delete();

			if($re)
			{
				echo json_encode(1);
			}
		}
	}


	//修改广告
	public function update(){

		$level_one[]='>广告管理';
		$level_one[]="";
		$level_two[]='>广告列表';
		$level_two[]=U('Admin/Adv/lists');
		$level_three[]='>修改广告';
		$level_three[]=U('Admin/Adv/update');
		$this->assign('level_one',$level_one);
		$this->assign('level_two',$level_two);
		$this->assign('level_three',$level_three);
		if(IS_GET){
			$id=I('get.id');
			if(!empty($id))
			{
				$map['id']=$id;
			}
			$User=M('adv');

			$list=$User->where($map)->find();
			$list['content']=html_entity_decode($list['content']);

			$this->assign('list',$list);
			$this->display('update');
			
		}else{

			/*$ttype = I('post.ttype');
			if(!empty($ttype)){

				$data['ttype'] = $ttype;
			}

			$type=I('post.type');
			if(!empty($type)){

				$data['type'] = 1;
			}else{

				$data['type'] = 0;
			}

			$title=I('post.title');
			if(!empty($title)){

				$data['title']=$title;
			}

			$url=I('post.url');
			if(!empty($url)){

				$data['url'] = $url;
			}


			$content=I('post.content');
			if(!empty($content)){

				$data['content']=$content;
			}


			$rank=I('post.rank');
			if(isset($rank)){

				$data['rank']=$rank;
			}


			$state=I('post.state');
			if(!empty($state)){

				$data['state'] = 1;
			}else{

				$data['state'] = 0;
			}

			$id=I('post.id');
			if(!empty($id)){

				$map['id']=$id;
			}

			if(!empty($_FILES['photo']['size'])){
			//上传图片
				$photo='Uploads'.'/'.$this->upload($_FILES['photo']);
				//对图片进行裁剪
				$img=$this->img_thumb2($photo);
				$mini_img=$this->img_thumb1($photo);
				$data['img']=$img;
				$data['mini_img']=$mini_img;
			}
			$data['ctime']=I('server.REQUEST_TIME');
			$User=M('adv');
			$re=$User->where($map)->data($data)->save();
			if($re)
			{
				$this->success('修改成功!',U('Admin/Adv/lists'));
			}
			else
			{
				$this->error('修改失败');
			}*/
			$id = I('post.id');
			if(!empty($id)){

				$map['id'] = $id;
			}
			$ttype = I('post.ttype');
			$type=I('post.type');
			$title=I('post.title');
			$isoutlink=I('post.isoutlink');
			$url=I('post.url');
			$content=I('post.content');
			$rank=I('post.rank');
			$state=I('post.state');
			$fdid = I('post.fdid');
			if(!empty($ttype)){

				$data['ttype'] = $ttype;
			}

			if(!empty($type)){
				
				if($isoutlink==1){
					$data['isoutlink']=1;
					$data['url']=$url;
				}else{

					$data['isoutlink']=0;
					// $data['url']=$url;
				}

				$data['type']=1;

			}else{
				$data['type']=0;
				$data['fdid'] = $fdid;
			}

			if(!empty($title)){

				$data['title']=$title;
			}

			if(isset($rank))
			{
				$data['rank']=$rank;
			}

			if(isset($state))
			{
				$data['state']=$state;
			}
			//对上传图片进行处理
			if(!empty($_FILES['photo']['name']))
			{
			//上传图片
				$photo='Uploads'.'/'.$this->upload($_FILES['photo']);
				// echo $photo; die;
				//对图片进行裁剪
				$img=$this->img_thumb2($photo);
				$mini_img=$this->img_thumb1($photo);
				$data['img']=$img;
				$data['mini_img']=$mini_img;
			}


			$data['ctime']=I('server.REQUEST_TIME');

			$User=M('adv');

			$adv_id = $User->where($map)->data($data)->save();
			// echo $adv_id; die;
			if(!empty($adv_id)){

				if(isset($data['isoutlink']) && $data['isoutlink'] == 0){
					$datas['url'] = "http://".$_SERVER['HTTP_HOST'].__ROOT__."/index.php/Home/Essay/details/id/".$map['id'].".html";
					if(!empty($content)){

						$datas['content']=$content;
					}
					$re = $User->where($map)->data($datas)->save();
					// echo $re; die;
					if(!empty($re)){

						$this->success('修改成功!',U('Admin/Adv/lists'));
					}else{

						$this->error('修改失败!');
					}
				}else{

					$this->success('修改成功!',U('Admin/Adv/lists'));
				}
			}else{
				$this->error('修改失败!');
			}
		}
		
	}

		//文件上传累
	public function upload($file)
	{
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =     5242880 ;// 设置附件上传大小
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath  =      'adv/original/'; // 设置附件上传目录
		// 上传文件 
		$info   =   $upload->uploadOne($file);
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

	//图像等比例缩小裁剪
	public function img_thumb1($img_url)
	{
		$image = new \Think\Image(); 
		$img_url = './'.$img_url;
		$image->open("$img_url");
		$img_name=$this->img_name().'.jpg';
		$re_small_url=__ROOT__.'/'.'Uploads'.'/'.'adv'.'/'.'change_small'.'/'.$img_name;
		$small_url='./'.'Uploads'.'/'.'adv'.'/'.'change_small'.'/'.$img_name;
		// echo $small_url; die;
		// 生成一个居中裁剪为30*30的缩略图并保存
		$image->thumb(120, 50,\Think\Image::IMAGE_THUMB_CENTER)->save($small_url);
		return $re_small_url;
	}
	public function img_thumb2($img_url)
	{
		$image = new \Think\Image(); 
		$img_url = './'.$img_url;
		$image->open("$img_url");
		$img_name=$this->img_name().'.jpg';
		$re_small_url=__ROOT__.'/'.'Uploads'.'/'.'adv'.'/'.'change_small'.'/'.$img_name;
		$small_url='./'.'Uploads'.'/'.'adv'.'/'.'change_small'.'/'.$img_name;
		// echo $small_url; die;
		// 生成一个居中裁剪为30*30的缩略图并保存
		$image->thumb(600, 250,\Think\Image::IMAGE_THUMB_CENTER)->save($small_url);
		return $re_small_url;
	}

	//生成随机图像名称
	public function img_name()
	{
		$time=I('server.REQUEST_TIME');
		return $time.str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
	}

}