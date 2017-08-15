<?php
namespace M\Controller;
use Think\Controller;
class NewsController extends CommonController {

	//资讯详情
	public function details(){

		$id = I('get.id');
		if(!empty($id)){

			$map['id'] = $id;
		}else{

			$this->error('参数错误');
		}

		$User = M('news');
		$get_new = $User->where($map)->find();
		$get_new['content'] = htmlspecialchars_decode($get_new['content']);
		// print_r($get_new); die;
                
		$this->assign('get_new',$get_new);
		$this->display(); 
	}
        
        //APP来源访问
        
        public function details_for_app(){

		$id = I('get.newsid');
		if(!empty($id)){

			$map['id'] = $id;
		}else{

			$this->error('参数错误');
		}

		$User = M('news');
		$get_new = $User->where($map)->find();
		$get_new['content'] = htmlspecialchars_decode($get_new['content']);
		// print_r($get_new); die;
		$this->assign('get_new',$get_new);
		$this->display(); 
	}
        
        //分享的内容添加尾巴
        
        public function details_for_share(){

		$id = I('get.id');
		if(!empty($id)){

			$map['id'] = $id;
		}else{

			$this->error('参数错误');
		}

		$User = M('news');
		$get_new = $User->where($map)->find();
		$get_new['content'] = htmlspecialchars_decode($get_new['content']);
		// print_r($get_new); die;
		$this->assign('get_new',$get_new);
		$this->display(); 
	}
}
?>