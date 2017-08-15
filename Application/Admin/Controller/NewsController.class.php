<?php
namespace Admin\Controller;
use Think\Controller;
class NewsController extends CommonController{

	//添加资讯新闻
	public function add(){

		if(IS_POST){

			$title = I('post.title');
			if(!empty($title)){

				$data['title'] = $title;
			}else{

				$this->error('标题不能为空!','',1);
			}

			$description = I('post.description');
			if(!empty($description)){

				$data['description'] = $description;
			}else{

				$this->error('简介不能为空!','',1);
			}

			$author = I('post.author');
			if(!empty($author)){

				$data['author'] = $author;
			}else{

				$data['author'] = '佚名';
			}

			$content = I('post.content');
			if(!empty($content)){

				$data['content'] = $content;
			}else{

				$this->error('内容不能为空!','',1);
			}

			$rank = I('post.rank');
			if(!empty($rank)){

				$data['rank'] = $rank;
			}

			if(!empty($_FILES['thumb']['name'])){

				$data['thumb'] =__ROOT__.'/Uploads'.'/'.$this->upload($_FILES['thumb']);
			}else{

				$this->error('略缩图不能为空!','',1);
			}

			$stime = I('post.stime');
			// echo $stime; die;
			if(!empty($stime)){

				$data['stime'] = strtotime($stime);
			}else{

				$data['stime'] = I('server.REQUEST_TIME');
			}

			// print_r($data); die;
			$data['ctime'] = I('server.REQUEST_TIME');

			$User = M('news');

			$result = $User->data($data)->add();
			if(!empty($result)){

				$this->success('添加成功',U('Admin/News/lists'));
			}else{

				$this->error('添加失败!');
			}

		}else{

			$this->display();
		}
	}

		//资讯列表
	public function lists(){

		if(!IS_POST){

			$User = M('news');

			/**
			数据分页显示开始
			**/
			$count      = $User->where(array('active' => 1))->count();// 查询满足要求的总记录数
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
			$list = $User->where(array('active' => 1))->order('rank desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			/**
			数据分页结束
			**/
			$this->assign('show',$show);
			$this->assign('list',$list);
			$this->display();
		}
	}


	//删除资讯  删除资讯将数据表active修改为0  1为显示状态 0 为删除状态
	public function del(){

		$id = I('post.id');

		if(!empty($id)){

			$map['id'] = $id;
		}

		$data['active'] = 0;
		$User = M('news');

		$result = $User->where($map)->data($data)->save();

		if(!empty($result)){

			echo json_encode(1);
		}
	}

	//资讯详情显示
	public function details(){

		if(IS_GET){

			$id = I('get.id');
			if(!empty($id)){

				$map['id'] = $id;
			}

			$User = M('news');

			$list = $User->where($map)->find();

			$list['content'] = html_entity_decode($list['content']);

			$this->assign('list',$list);
			$this->display();
		}
	}

	//修改资讯
	public function modify(){

		if(IS_GET){

			$id = I('get.id');
			if(!empty($id)){

				$map['id'] = $id;
			}

			$User = M('news');

			$list = $User->where($map)->find();
			$list['content'] = html_entity_decode($list['content']);

			$this->assign('list',$list);
			$this->display();
		}else{

			$id = I('post.id');
			if(!empty($id)){

				$map['id'] = $id;
			}else{

				$this->error('参数错误!','',1);
			}

			$title = I('post.title');
			if(!empty($title)){

				$data['title'] = $title;
			}

			$author = I('post.author');
			if(!empty($author)){

				$data['author'] = $author;
			}

			$description = I('post.description');
			if(!empty($description)){

				$data['description'] = $description;
			}

			$content = I('post.content');
			if(!empty($content)){

				$data['content'] = $content;
			}

			$rank = I('post.rank');
			if(!empty($rank)){

				$data['rank'] = $rank;
			}

			if(!empty($_FILES['thumb']['name'])){

				$data['thumb'] =__ROOT__.'/Uploads'.'/'.$this->upload($_FILES['thumb']);
			}

			$stime = I('post.stime');
			if(!empty($stime)){

				$data['stime'] = strtotime($stime);
			}
			// echo $data['stime']; die;
			$data['ctime'] = I('server.REQUEST_TIME');

			$User = M('news');

			$result = $User->where($map)->data($data)->save();

			if(!empty($result)){

				$this->success('添加成功!',U('Admin/News/lists'));
			}else{

				$this->error('添加失败!','',1);
			}

		}

	}


	//图片单个上传
	private function upload($file){    
		$upload = new \Think\Upload();// 实例化上传类    
		$upload->maxSize   =     3145728 ;// 设置附件上传大小    
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型    
		$upload->savePath  =      'news/'; // 设置附件上传目录    // 上传单个文件     
		$info   =   $upload->uploadOne($file);    
		if(!$info) {
		// 上传错误提示错误信息        
			$this->error($upload->getError());    
		}else{
		// 上传成功 获取上传文件信息         
			return $info['savepath'].$info['savename'];    
		}
	}

	//生成随机图像名称
	public function img_name()
	{
		$time=I('server.REQUEST_TIME');
		return $time.str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
	}

}