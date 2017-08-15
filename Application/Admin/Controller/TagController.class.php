<?php
namespace Admin\Controller;
use Think\Controller;
class TagController extends CommonController{

	//标签列表
	public function lists(){

		if(IS_GET){

			$User = M('product_tags');

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

			$this->assign('list',$list);
			$this->assign('show',$show);
			$this->display();
		}
	}



	//添加标签
	public function add(){

		if(IS_POST){

			$tag_name = I('tag_name');
			if(!empty($tag_name)){

				$data['tag_name'] = $tag_name;
			}else{

				$this->error('标签名不能为空','',1);
			}

			$rank = I('post.rank');
			if(!empty($rank)){

				$data['rank'] = $rank;
			}

			// print_r($_FILES); die;

			if(!empty($_FILES['thumb']['name'])){

				$thumb =__ROOT__.'/Uploads/'.$this->upload($_FILES['thumb']);
				
				$data['thumb'] = $thumb;
			}else{

				$this->error('图片不能为空','',1);
			}

			$data['ctime'] = I('server.REQUEST_TIME');
			$User = M('product_tags');

			$result = $User->data($data)->add();
			if(!empty($result)){

				$this->success('添加成功!',U('Admin/Tag/lists'),1);
			}else{

				$this->error('添加失败!','',1);
			}
		}

	}
        
        
        /*
         * 产品列表
         */
        public function tag_product_lists() {
            $tagid = I('get.tagid');
            $DB = M("product_tags_relationship");
            $map['tagid']=$tagid;
            
            $res = $DB->where($map)->select();
            foreach ($res as $key => $value) {
                $res[$key]['pinfo']= $this->_get_third_party_pinfo($value['pid']);
            }
            
            $this->assign('list', $res);
            
            $this->display();
        }
        
        
        
        /*
         * 添加产品
         */
        public function tag_product_search() {
            $search = I('get.search');
            $tagid = I('get.tagid');
            if($search)
            {
                //获取所有的第三方信息
                $sortname= I('get.sortname');
                $keyword = I('get.keyword');
                
                $map['category']=$sortname;
                if(!empty($keyword))
                {
                    $map['product_name']=array("LIKE","%$keyword%");
                }
                $DB = M("product_thirdparty");
                
                $res = $DB->where($map)->select();
//                print_r($DB->getLastSql());
                $this->assign('list', $res);
//                print_r($res);
                
                
                $this->display("tag_product_search_list");
            }
            else
            {
                $this->assign('tagid', $tagid);
               $this->display(); 
            }
            
        }
        
        
        /*
         * 添加产品的搜索结果列表
         */
        public function tag_product_search_result() {
            
            if(IS_POST)
            {
//                print_r($_POST);
                $tagid = I('post.tagid');
                $pid = I('post.pid');
//                print_r($pid);
                $ctime=  I('server.REQUEST_TIME');
                $DB = M("product_tags_relationship");
                foreach ($pid as $key => $value) {
                    //判断数据是否存在
                    if($this->_chk_tagid_pid($tagid, $value))
                    {
                       $datalist[]=array('tagid'=>$tagid,'pid'=>$value,'ctime'=>$ctime); 
                    }
                    
                    
                }
//                print_r($datalist);
//                exit();
                if($DB->addAll($datalist))
                {
                    $this->success('添加成功');
                }
                else
                {
                    $this->error("添加失败");
                }
//                print_r($datalist);
                
            }
            else
            {
                $search = I('get.search');
                $tagid = I('get.tagid');
                if($search)
                {
                    //获取所有的第三方信息
                    $sortname= I('get.sortname');
                    $keyword = I('get.keyword');

                    $map['category']=$sortname;
                    if(!empty($keyword))
                    {
                        $map['product_name']=array("LIKE","%$keyword%");
                    }
                    $DB = M("product_thirdparty");

                    $res = $DB->where($map)->select();
    //                print_r($DB->getLastSql());
                    $this->assign('list', $res);
                    $this->assign('tagid', $tagid);
                    
    //                print_r($res);


                    $this->display("tag_product_search_list");
                }
            }
            
            
            
        }
        
        /*
         * 增加单个tagid 和产品的关联
         */
        public function add_product_to_tagid() {
            $tagid = I("get.tagid");
            $pid = I("get.pid");
//            echo 1;
            if($this->_chk_tagid_pid($tagid, $pid)){
//                echo 2;
                $DB = M("product_tags_relationship");
                $data['tagid']=$tagid;
                $data['pid']=$pid;
                $data['ctime']=I("server.REQUEST_TIME");
                
                if($DB->add($data))
                { 
//                    echo 3;
                    echo 1;
                }
                else
                {
//                     echo 4;
                    echo 0;
                }
            }
            else
            {
//                 echo 5;
                echo 0;
            }
            
            
        }
        
        
        /*
         * 删除标签和产品的关系
         */
        public function del_tag_pid_relationship() {
            $tagid=I('get.tagid');
            $pid = I('get.pid');
            
            $map['tagid']=$tagid;
            $map['pid']=$pid;
            $DB = M("product_tags_relationship");
            
            if($DB->where($map)->delete())
            {
                $this->success("删除成功");
            }
            else
            {
                $this->error("删除失败");
            }
            
        }
        
        /*
         * 判断tagid 和 pid 的关系
         *  
         * 
         */
        private function _chk_tagid_pid($tagid,$pid) {
            $map['tagid']= $tagid;
            $map['pid'] = $pid;
            
            $DB = M("product_tags_relationship");
            $res = $DB->where($map)->find();
//            print_r($res);
            if(!empty($res))
            {
                return 0;
            }
            else
            {
                return 1;
            }
        }




        /*
         * 获取第三方产品信息
         */
        private function _get_third_party_pinfo($pid) {
            $DB = M("product_thirdparty");
            $res = $DB->find($pid);
            
            return $res;
        }


        //上传单张图片
	private function upload($file){    
		$upload = new \Think\Upload();// 实例化上传类    
		$upload->maxSize   =     3145728 ;// 设置附件上传大小    
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型    
		$upload->savePath  =      'tag_img/'; // 设置附件上传目录 
		$info   =   $upload->uploadOne($file);    
		if(!$info) {
		// 上传错误提示错误信息        
			$this->error($upload->getError());    
		}else{
		// 上传成功 获取上传文件信息         
			return $info['savepath'].$info['savename'];    
		}
	}


	//删除标签
	public function del(){

		if(IS_AJAX){

			$id = I('post.id');
			if(!empty($id)){

				$map['id'] = $id;
				$data['active'] = 0;
			}

			$User = M('product_tags');

			$result = $User->where($map)->data($data)->save();

			if(!empty($result)){

				echo json_encode(1);
			}else{

				echo json_encode(0);
			}
		}
	}


	//对标签进行修改
	//
	public function modify(){

		if(IS_POST){

			$id = I('post.id');
			if(!empty($id)){

				$map['id'] = $id;
			}else{

				$this->error('参数出错!','',1);
			}

			$tag_name = I('post.tag_name');
			if(!empty($tag_name)){

				$data['tag_name'] = $tag_name;
			}

			$rank = I('post.rank');
			if(!empty($rank)){

				$data['rank'] = $rank;
			}

			if(!empty($_FILES['thumb']['name'])){

				$thumb =__ROOT__.'/Uploads/'.$this->upload($_FILES['thumb']);
				
				$data['thumb'] = $thumb;
			}

			$User = M('product_tags');

			$result = $User->where($map)->data($data)->save();

			if(!empty($result)){

				$this->success('修改成功!',U('Admin/Tag/lists'),1);
			}else{

				$this->error('修改失败!','',1);
			}
		}
	}



/**
    搜索页面标签
**/

    //搜索标签列表
    public function search_lists(){

        if(IS_GET){
            $DB_search_tags = M('search_tags');
            $map['type'] = 0;
            $list = $DB_search_tags->where($map)->order('rank desc')->select();

            $this->assign('list',$list);
            $this->display();
        }
    }

    //添加搜索标签
    public function add_search_tag(){

        if(IS_POST){

            $keyword = I('post.keyword');
            if(!empty($keyword)){

                $data['keyword'] = $keyword;
            }else{

                $this->error('标签名不能为空!','',1);
            }

            $rank = I('post.rank');
            if(!empty($rank)){

                $data['rank'] = $rank;
            }

            $data['ctime'] = I('server.REQUEST_TIME');
            $data['type'] = 0;

            $DB_search_tags = M('search_tags');
            $result = $DB_search_tags->data($data)->add();

            if(!empty($result)){

                $this->success('添加成功!',U('Admin/Tag/search_lists'),1);
            }
        }
    }


//ajax删除搜索标签
    public function del_search_tag(){

        if(IS_AJAX){

            $id = I('post.id');
            if(!empty($id)){

                $map['id'] = $id;
            }

            $User = M('search_tags');

            $result = $User->where($map)->delete();

            if(!empty($result)){

                echo json_encode(1);
            }else{

                echo json_encode(0);
            }
        }
    }



    //修改搜索标签
    public function modify_search_tag(){

        if(IS_POST){

            $id = I('post.id');
            if(!empty($id)){

                $map['id'] = $id;
            }else{

                $this->error('参数出错!','',1);
            }

            $keyword = I('post.keyword');
            if(!empty($keyword)){

                $data['keyword'] = $keyword;
            }

            $rank = I('post.rank');
            if(!empty($rank)){

                $data['rank'] = $rank;
            }

            $User = M('search_tags');

            $result = $User->where($map)->data($data)->save();

            if(!empty($result)){

                $this->success('修改成功!',U('Admin/Tag/search_lists'),1);
            }else{

                $this->error('修改失败!','',1);
            }
        }
    }



    /**
     * 曝光台标签管理
     */
    public function unsafe_lists(){

        if(IS_GET){

            $DB_search_tags = M('search_tags');
            $map['type'] = 1;
            $list = $DB_search_tags->where($map)->order('rank desc')->select();

            $this->assign('list',$list);

            $this->display();
        }
    }


    /**
     * 添加曝光台标签
     */
    
    public function add_unsafe_tag(){

        if(IS_POST){

            $keyword = I('post.keyword');
            if(!empty($keyword)){

                $data['keyword'] = $keyword;
            }else{

                $this->error('标签名不能为空!','',1);
            }

            $rank = I('post.rank');
            if(!empty($rank)){

                $data['rank'] = $rank;
            }

            $data['ctime'] = I('server.REQUEST_TIME');
            $data['type'] = 1;

            $DB_search_tags = M('search_tags');
            $result = $DB_search_tags->data($data)->add();

            if(!empty($result)){

                $this->success('添加成功!',U('Admin/Tag/unsafe_lists'),1);
            }
        }
    }



    /**
     * 曝光台标签修改
     */
    public function modify_unsafe_tag(){

        if(IS_POST){

            $id = I('post.id');
            if(!empty($id)){

                $map['id'] = $id;
            }else{

                $this->error('参数出错!','',1);
            }

            $keyword = I('post.keyword');
            if(!empty($keyword)){

                $data['keyword'] = $keyword;
            }

            $rank = I('post.rank');
            if(!empty($rank)){

                $data['rank'] = $rank;
            }

            $User = M('search_tags');

            $result = $User->where($map)->data($data)->save();

            if(!empty($result)){

                $this->success('修改成功!',U('Admin/Tag/unsafe_lists'),1);
            }else{

                $this->error('修改失败!','',1);
            }
        }
    }


    //ajax删除曝光台标签
    public function del_unsafe_tag(){

        if(IS_AJAX){

            $id = I('post.id');
            if(!empty($id)){

                $map['id'] = $id;
            }

            $User = M('search_tags');

            $result = $User->where($map)->delete();

            if(!empty($result)){

                echo json_encode(1);
            }else{

                echo json_encode(0);
            }
        }
    }
}