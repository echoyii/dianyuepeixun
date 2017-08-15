<?php
namespace Admin\Controller;
use Think\Controller;
class FundingController extends CommonController{

	/**
	待审核项目开始处
	**/
	//待审核的众筹项目列表
	public function new_lists(){
		if(IS_POST){


		}else{

			$User=M('fundings');
			/**
			**数据分页显示开始
			**/
			$count      = $User->where(array('state' => 0))->count();// 查询满足要求的总记录数
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
			// $list = $User->where(array('state' => 0))->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			// $Page->firstRow.','.$Page->listRows
			$list = $User->query("SELECT fd_fundings.*,fd_members.username AS username FROM fd_fundings LEFT JOIN fd_members ON fd_fundings.mid = fd_members.id  WHERE fd_fundings.state = 0 ORDER BY id desc LIMIT $Page->firstRow,$Page->listRows");
			/**
			**数据分页结束
			**/

			//获取对应的套餐类型
			//获取对应的检测机构
			$institutionid=M('institution');
			foreach ($list as $k => $v) {
				$list[$k]['packageid'] = $this->get_package($v['packageid']);
				$list[$k]['institutionid'] = $institutionid->where(array('id' => $v['institutionid']))->getField('name');
			}


			//循环显示项目状态
			foreach ($list as $k => $v) {
				# code...
				switch ($v['state']) {
					case 0:
						$list[$k]['state']='待审核';
						break;
					case 1:
						$list[$k]['state']='发起中';
						break;
					case 2:
						$list[$k]['state']='抽样中';
						break;
					case 3:
						$list[$k]['state']='已完成';
						break;

					default:
						$list[$k]['state']='异常状态';
						break;
				}
                                
            /*
             * 判断是否支付了该订单
             */
           		 $list[$k]['payment_status']=  $this->_chk_founding_payment_status($v['id'], $v['mid']);

           		 /**
           		  * 获取对应项目已筹到的金额总数
           		  */
           		 $list[$k]['finish_money'] = $this->get_finish_money($v['id']);

           		 /**
           		  * 计算目标金额  目标金额=检测金额+样品数量*样品单价
           		  */
           		 $list[$k]['target_money'] = $this->get_target_money($v['id']);
                                 
			}
			// var_dump($show); die;
			$this->assign('list',$list);
			$this->assign('page',$show);// 赋值分页输出
			$this->display('new_lists');
		}
	}

	/**
	 * 获取到目标金额
	 */
	private function get_target_money($fid){

		$map['id'] = $fid;

		$Fundings_conn = M('fundings');
		$result = $Fundings_conn->where($map)->find();

		if(!empty($result)){

			$target_money = $result['emoney'] + $result['smoney'] * $result['samnum'];
			return $target_money;
		}
	}

	/**
	 * 待审核项目项目详情
	 */
	public function new_lists_detail(){

		if(IS_GET){
			$id = I('get.id');
			if(!empty($id))
			{
				$map['id'] = $id;
			}

			$list = M('fundings')->where($map)->find();

			$food_classify = M('food_classify');
			$fundings_package = M('fundings_package');
			$institution = M('institution');
			$members = M('members');
			$testreport = M('testreport');
			$fundings_image = M('fundings_image');
			// print_r($list); die;
			//获取主分类的名称
			$list['first_categoryid'] = $food_classify->where(array('id' => $list['first_categoryid']))->getField('name');

			//获取二级分类的名称
			$list['second_categoryid'] = $food_classify->where(array('id' => $list['second_categoryid']))->getField('name');

			//获取套餐项目对应的名称
			$list['packageid'] = $fundings_package->where(array('id' => $list['packageid']))->getField('name');

			//获取对应机构的名称
			$list['institutionid'] = $institution->where(array('id' => $list['institutionid']))->getField('name');
			//项目的状态
			switch ($list['state']) 
			{
				case 0:
					# code...
					$list['state'] = '待审核项目';
					break;
				case 1:
					# code...
					$list['state'] = '发起中项目';
					break;
				case 2:
					# code...
					$list['state'] = '抽检中项目';
					break;
				case 3:
					$list['state'] = '已完成项目';
					break;
				
				default:
					# code...
					$list['state'] = '异常项目';
					break;
			}

			//获取用户id对应的用户表的用户名
			$list['mid'] = $members->where(array('id'=>$list['mid']))->getField('username');
			//获取检测报告对应的下载地址
			$list['testreport'] = $testreport->where(array('id' => $list['testreportid']))->find();
			/**
	   		  * 获取对应项目已筹到的金额总数
	   		  */
	   		$list['finish_money'] = $this->get_finish_money($map['id']);

	   		 /**
	   		  * 计算目标金额  目标金额=检测金额+样品数量*样品单价
	   		  */
	   		$list['target_money'] = $this->get_target_money($map['id']);
			//将储存图片的id的字符串转化为数组
			$list['sampleid'] = explode(',', $list['sampleid']);
			foreach ($list['sampleid'] as $k => $v) 
			{
				# code...
				$list['sampleid'][$k] = $fundings_image->where(array('id' => $v))->getField('img_url'); 
			}
				// print_r($list); die;
			$this->assign('list',$list);
			$this->display();
		}
	}

	/**
	 * 通过code获取省份和市区
	 */
    private function get_code_name($code){

        $DB_regions = M('regions');

        $city_map['code'] = $code;

        $ad['city'] = $DB_regions->where($city_map)->getField('name');


        //根据市code反推省份code
        $province_map['code'] = ($code-($code%100))/100;
        $ad['province'] = $DB_regions->where($province_map)->getField('name');

        return $ad;
    }

	/**
	 * 修改待审核项目
	 */
	public function modify_new_lists(){

		if(!IS_POST){

			$id=I('get.id');
			if(!empty($id)){

				$map['id'] = $id;
			}
			$list=M('fundings')->where($map)->find();

			//将逗号隔开的id串转化为数组
			$list['member_upload_imgs'] = explode(',',$list['member_upload_imgs']);
			// print_r($list); die;
			foreach ($list['member_upload_imgs'] as $key => $value) {
				# code...
				$list['member_upload_imgs'][$key] = M('fundings_image')->where(array('id' => $value))->find();
			}
			/**
	   		  * 获取对应项目已筹到的金额总数
	   		  */
	   		$list['finish_money'] = $this->get_finish_money($map['id']);

	   		 /**
	   		  * 计算目标金额  目标金额=检测金额+样品数量*样品单价
	   		  */
	   		$list['target_money'] = $this->get_target_money($map['id']);

			//获取mid对用的用户名
			$list['username'] = M('members')->where(array('id' => $list['mid']))->getField('username');

			//把数组中的一级分类ID替换为对应的分类名
			$list['first_categoryid']=M('food_classify')->where(array('id' => $list['first_categoryid']))->getFIeld('name');
			
			//把数组中的二级分类ID替换为对应的分类名
			$list['second_categoryid']=M('food_classify')->where(array('id' => $list['second_categoryid']))->getFIeld('name');

			//把数组中的套餐id替换为对应的套餐名称
			$list['packageid']=M('fundings_package')->where(array('id' => $list['packageid']))->getFIeld('name');

			//把套餐中的机构ID替换为对应的机构名称
			$list['institutionid']=M('institution')->where(array('id' => $list['institutionid']))->getFIeld('name');


			//获取主分类
			$classify_map['pid']=0;
			$classify_map['state']=1;
			$classify=M('food_classify')->where($classify_map)->select();


			//获取检测套餐
			$fundings_packageid = M('fundings_package')->select();

			// 获取检测机构列表
			$institutionid = M('institution')->select();
			//通过code获取地址
			// $ad = $this->get_code_name($list['code']);


			$this->assign('classify',$classify);
			$this->assign('fundings_packageid',$fundings_packageid);
			$this->assign('institutionid',$institutionid);
			$this->assign('list',$list);

			$this->display();
		}else{

			$id = I('post.id');
			if(!empty($id)){

				$map['id'] = $id;
			}

			$pjname = I('post.pjname');
			if(!empty($pjname)){

				$data['pjname'] = $pjname;
			}

			$pdname = I('post.pdname');
			if(!empty($pdname)){

				$data['pdname'] = $pdname;
			}

			$content = I('post.content');
			if(!empty($content)){

				$data['content'] = $content;
			}

			$brand = I('post.brand');
			if(!empty($brand)){

				$data['brand'] = $brand;
			}

			$first_categoryid = I('post.first_categoryid');
			if(!empty($first_categoryid)){

				$data['first_categoryid'] = $first_categoryid;
			}

			$second_categoryid = I('post.second_categoryid');
			if(!empty($second_categoryid)){

				$data['second_categoryid'] = $second_categoryid;
			}

			$packageid = I('post.packageid');
			if(!empty($packageid)){

				$data['packageid'] = $packageid;
			}

			$institutionid = I('post.institutionid');
			if(!empty($institutionid)){

				$data['institutionid'] = $institutionid;
			}

			$institutionid = I('post.institutionid');
			if(!empty($institutionid)){

				$data['institutionid'] = $institutionid;
			}

			$emoney=I('post.emoney');
			if(!empty($emoney))
			{
				$data['emoney']=$emoney*100;
			}

			$btime=I('post.btime');
			if(!empty($btime))
			{
				$data['btime']= strtotime($btime);
			}

			$etime=I('post.etime');
			if(!empty($pjname))
			{
				$data['etime']=strtotime($etime);
			}

			$samnum=I('post.samnum');
			if(!empty($samnum))
			{
				$data['samnum']=$samnum;
			}

			$smoney = I('post.smoney');
			if(!empty($smoney)){

				$data['smoney'] = $smoney*100;
			}


			$location=I('post.location');
			if(!empty($location))
			{
				$data['location']=$location;
			}

			$rank=I('post.rank');
			if(!empty($rank))
			{
				$data['rank']=$rank;
			}

			$batch=I('post.batch');
			if(!empty($batch))
			{
				$data['batch']=$batch;
			}

			$data['ctime']=I('server.REQUEST_TIME');

			$Fundings_conn = M('fundings');
			// print_r($data); die;
			$result = $Fundings_conn->where($map)->data($data)->save();

			if(!empty($result)){

				$this->success('修改成功!',U('Admin/Funding/new_lists'),1);
			}else{

				$this->error('修改失败!','',1);
			}
		}
	}



	/**
	 * 根据fid获取该项目已筹到的金额
	 */
		private function get_finish_money($fid){

			if(!empty($fid)){

				$map['fid'] = $fid;
				$map['status'] =1;
			}
			$DB_fundings_sponsor = M('fundings_sponsor');

			$finish_money = $DB_fundings_sponsor->where($map)->sum('money');

			return $finish_money;
		}
        
        /*
        * 判断是否支付了该订单
        */
       private function _chk_founding_payment_status($fdid,$mid) {
           $map['fid']=$fdid;
           $map['mid']=$mid;
           // $map['type'] = 1;
           $map['status']=1;
           $DB = M("fundings_sponsor");
           
           $res = $DB->where($map)->find();
           
           if(!empty($res))
           {
               return "<span style='background-color:#82af6f;'>已支付</span>";
           }
           else
           {
               return "<span style='background-color:#D61A4E;'>未支付</span>";
           }
           
           
       }

       // 自动获取选择的套餐
	private function get_package($str)
	{
		$arr = explode(',',$str);
		$fundings_packageid=M('fundings_package');

		foreach ($arr as $k => $v) {
			$arr[$k] = $fundings_packageid->where(array('id' => $v))->getField('name');
		}
		$packageid = implode(',',$arr);

		return $packageid;
	}


	// 所有的众筹项目
	public function all_lists()
	{
		if(IS_POST){

			$fid = I('post.select_keywords_fid');
			if(!empty($fid)){

				$map['id'] = trim($fid);
			}

			$Fundings_conn = M('fundings');
			$sql = "SELECT fd_fundings.*,fd_members.username AS username FROM fd_fundings LEFT JOIN fd_members ON fd_fundings.mid = fd_members.id where fd_fundings.id=".$map['id'];
			$list = $Fundings_conn->query($sql);

			//获取对应的检测机构
			$institutionid=M('institution');
			foreach ($list as $k => $v) 
			{
				$list[$k]['institutionid'] = $institutionid->where(array('id' => $v['institutionid']))->getField('name');
				$list[$k]['packageid'] = $this->get_package($v['packageid']);
			}


			//循环显示项目状态
			foreach ($list as $k => $v) 
			{
				# code...
				switch ($v['state']) 
				{
					case 0:
						$list[$k]['state']='待审核项目';
						break;
					case 1:
						$list[$k]['state']='发起中项目';
						break;
					case 2:
						$list[$k]['state']='抽检中项目';
						break;
					case 3:
						$list[$k]['state']='已完成项目';
						break;

					default:
						$list[$k]['state']='异常状态';
						break;
				}
			}


			$this->assign('list',$list);
			$this->display();

		}else{

			$User=M('fundings');
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
			// $list = $User->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			$list = $User->query("SELECT fd_fundings.*,fd_members.username AS username FROM fd_fundings LEFT JOIN fd_members ON fd_fundings.mid = fd_members.id ORDER BY id desc LIMIT $Page->firstRow,$Page->listRows");
			/**
			数据分页结束
			**/


			//获取对应的检测机构
			$institutionid=M('institution');
			foreach ($list as $k => $v) 
			{
				$list[$k]['institutionid'] = $institutionid->where(array('id' => $v['institutionid']))->getField('name');
				$list[$k]['packageid'] = $this->get_package($v['packageid']);
			}


			//循环显示项目状态
			foreach ($list as $k => $v) 
			{
				# code...
				switch ($v['state']) 
				{
					case 0:
						$list[$k]['state']='待审核项目';
						break;
					case 1:
						$list[$k]['state']='发起中项目';
						break;
					case 2:
						$list[$k]['state']='抽检中项目';
						break;
					case 3:
						$list[$k]['state']='已完成项目';
						break;

					default:
						$list[$k]['state']='异常状态';
						break;
				}
			}
			$this->assign('list',$list);
			$this->assign('page',$show);
			$this->display('all_lists');
		}
	}

	/**
	 * 所有众筹项目详情
	 */
	public function all_lists_detail(){

		if(IS_GET){

			$id = I('get.id');
			if(!empty($id))
			{
				$map['id'] = $id;
			}

			$list = M('fundings')->where($map)->find();

			$food_classify = M('food_classify');
			$fundings_package = M('fundings_package');
			$institution = M('institution');
			$members = M('members');
			$testreport = M('testreport');
			$fundings_image = M('fundings_image');
			// print_r($list); die;
			//获取主分类的名称
			$list['first_categoryid'] = $food_classify->where(array('id' => $list['first_categoryid']))->getField('name');

			//获取二级分类的名称
			$list['second_categoryid'] = $food_classify->where(array('id' => $list['second_categoryid']))->getField('name');

			//获取套餐项目对应的名称
			$list['packageid'] = $fundings_package->where(array('id' => $list['packageid']))->getField('name');

			//获取对应机构的名称
			$list['institutionid'] = $institution->where(array('id' => $list['institutionid']))->getField('name');
			//项目的状态
			switch ($list['state']) 
			{
				case 0:
					# code...
					$list['state'] = '待审核项目';
					break;
				case 1:
					# code...
					$list['state'] = '发起中项目';
					break;
				case 2:
					# code...
					$list['state'] = '抽检中项目';
					break;
				case 3:
					$list['state'] = '已完成项目';
					break;
				
				default:
					# code...
					$list['state'] = '异常项目';
					break;
			}

			//获取用户id对应的用户表的用户名
			$list['mid'] = $members->where(array('id'=>$list['mid']))->getField('username');
			//获取检测报告对应的下载地址
			$list['testreport'] = $testreport->where(array('id' => $list['testreportid']))->find();
			/**
	   		  * 获取对应项目已筹到的金额总数
	   		  */
	   		$list['finish_money'] = $this->get_finish_money($map['id']);

	   		 /**
	   		  * 计算目标金额  目标金额=检测金额+样品数量*样品单价
	   		  */
	   		$list['target_money'] = $this->get_target_money($map['id']);
			//将储存图片的id的字符串转化为数组
			$list['sampleid'] = explode(',', $list['sampleid']);
			foreach ($list['sampleid'] as $k => $v) 
			{
				# code...
				$list['sampleid'][$k] = $fundings_image->where(array('id' => $v))->getField('img_url'); 
			}
				// print_r($list); die;
			$this->assign('list',$list);

			$this->display();
		}
	}

	/**
	 * 所有项目修改
	 */
	
	public function modify_all_lists(){

		if(IS_POST){

			$id = I('post.id');
			if(!empty($id)){

				$map['id'] = $id;
			}

			$pjname = I('post.pjname');
			if(!empty($pjname)){

				$data['pjname'] = $pjname;
			}

			$pdname = I('post.pdname');
			if(!empty($pdname)){

				$data['pdname'] = $pdname;
			}

			$content = I('post.content');
			if(!empty($content)){

				$data['content'] = $content;
			}

			$brand = I('post.brand');
			if(!empty($brand)){

				$data['brand'] = $brand;
			}

			$first_categoryid = I('post.first_categoryid');
			if(!empty($first_categoryid)){

				$data['first_categoryid'] = $first_categoryid;
			}

			$second_categoryid = I('post.second_categoryid');
			if(!empty($second_categoryid)){

				$data['second_categoryid'] = $second_categoryid;
			}

			$packageid = I('post.packageid');
			if(!empty($packageid)){

				$data['packageid'] = $packageid;
			}

			$institutionid = I('post.institutionid');
			if(!empty($institutionid)){

				$data['institutionid'] = $institutionid;
			}

			$institutionid = I('post.institutionid');
			if(!empty($institutionid)){

				$data['institutionid'] = $institutionid;
			}

			$emoney=I('post.emoney');
			if(!empty($emoney))
			{
				$data['emoney']=$emoney*100;
			}

			$btime=I('post.btime');
			if(!empty($btime))
			{
				$data['btime']= strtotime($btime);
			}

			$etime=I('post.etime');
			if(!empty($pjname))
			{
				$data['etime']=strtotime($etime);
			}

			$samnum=I('post.samnum');
			if(!empty($samnum))
			{
				$data['samnum']=$samnum;
			}

			$smoney = I('post.smoney');
			if(!empty($smoney)){

				$data['smoney'] = $smoney*100;
			}


			$location=I('post.location');
			if(!empty($location))
			{
				$data['location']=$location;
			}

			$rank=I('post.rank');
			if(!empty($rank))
			{
				$data['rank']=$rank;
			}

			$batch=I('post.batch');
			if(!empty($batch))
			{
				$data['batch']=$batch;
			}

			$data['ctime']=I('server.REQUEST_TIME');


			if(!empty($_FILES['report_pdf']['name'])){
				$up_location = 'fundings/report/';
				$testreport['download'] = __ROOT__.'/Uploads/'.$this->upload_file_pdf($_FILES['report_pdf'],$up_location);
			}
			if(!empty($_FILES['report_jpg']['name'])){
				$up_location = 'fundings/report/';
				$testreport['download_jpg'] = __ROOT__.'/Uploads/'.$this->upload_file_jpg($_FILES['report_jpg']);
			}

			$Fundings_conn = M('fundings');
			$Testreport_conn = M('testreport'); 


			if(!empty($testreport)){

				//检测报告录入时间
				$testreport_btime = I('post.testreport_btime');
				if(!empty($testreport_btime)){

					$testreport['btime'] = strtotime($testreport_btime);
				}
				//检测报告发布时间
				$testreport_ctime = I('post.testreport_ctime');
				if(!empty($testreport_ctime)){

					$testreport['ctime'] = strtotime($testreport_ctime);
				}
				//送检编号
				$testreport_code = I('post.testreport_code');
				if(!empty($testreport_code)){

					$testreport['code'] = $testreport_code;
				}
				//报告解析
				$testreport_content = I('post.testreport_content');
				if(!empty($testreport_content)){

					$testreport['content'] = $testreport_content;
				}


				$testreportid = $Testreport_conn->data($testreport)->add();
				
				// echo $testreportid; die;
			}

			if(!empty($testreportid)){

				$data['testreportid'] = $testreportid;

				$result = I('post.result');
				if(!empty($result)){

					$data['result'] = $result;
				}
				$pid = I('post.pid');
				if(!empty($pid)){

					$data['pid'] = $pid;
				}
			}

			$Fundings_conn = M('fundings');
			$re = $Fundings_conn->where($map)->data($data)->save();

			if(!empty($re)){

				$this->success('修改成功!',U('Admin/Funding/all_lists'),1);
			}else{

				$this->error('修改失败!','',1);
			}



		}else{

			$id=I('get.id');
			if(!empty($id)){

				$map['id']=$id;
			}else{

				$this->error('参数出错');
			}

			$list=M('fundings')->where($map)->find();
			/**
			 * 获取用户上传的样品图
			 */
			//将逗号隔开的id串转化为数组
			$list['member_upload_imgs'] = explode(',',$list['member_upload_imgs']);
			foreach ($list['member_upload_imgs'] as $key => $value) {
				# code...
				$list['member_upload_imgs'][$key] = M('fundings_image')->where(array('id' => $value))->find();
			}


			//获取mid对用的用户名
			$list['username'] = M('members')->where(array('id' => $list['mid']))->getField('username');

			//把数组中的一级分类ID替换为对应的分类名
			$list['first_categoryid']=M('food_classify')->where(array('id' => $list['first_categoryid']))->getFIeld('name');
			
			//把数组中的二级分类ID替换为对应的分类名
			$list['second_categoryid']=M('food_classify')->where(array('id' => $list['second_categoryid']))->getFIeld('name');

			//把数组中的套餐id替换为对应的套餐名称
			$list['packageid']=M('fundings_package')->where(array('id' => $list['packageid']))->getFIeld('name');

			//把套餐中的机构ID替换为对应的机构名称
			$list['institutionid']=M('institution')->where(array('id' => $list['institutionid']))->getFIeld('name');

			//检测项目的检测结果证书是否上传
			$list['testreport'] = M('testreport')->where(array('id' => $list['testreportid']))->find();
			//实体化字符编码
			$list['testreport']['content'] = htmlspecialchars_decode($list['testreport']['content']);

			//获取到关联产品
			$list['pid'] = M('product')->where(array('id' => $list['pid']))->getField('name');

			//获取到已完成的金额
			$list['finish_money'] = $this->get_finish_money($map['id']);

		   		 /**
		   		  * 计算目标金额  目标金额=检测金额+样品数量*样品单价
		   		  */
	   		$list['target_money'] = $this->get_target_money($map['id']);
	   		/**
	   		 * 获取到该项目所有的样品图
	   		 */
	   		$Fundings_image_conn = M('fundings_image');
	   		$list['sampleid'] = explode(',',$list['sampleid']);
			//循环数组得到对应的图片链接
			foreach ($list['sampleid'] as $key => $value) {
				# code...
				$list['sampleid'][$key] = $Fundings_image_conn->where(array('id' => $value))->find();
			}
			// print_r($list); die;
			//获取主分类
			$classify_map['pid']=0;
			$classify_map['state']=1;
			$classify=M('food_classify')->where($classify_map)->select();


			//获取检测套餐
			$fundings_packageid = M('fundings_package')->select();

			// 获取检测机构列表
			$institutionid = M('institution')->select();

			$this->assign('classify',$classify);
			$this->assign('fundings_packageid',$fundings_packageid);
			$this->assign('institutionid',$institutionid);
			$this->assign('list',$list);

			$this->display();

		}
	}

	/**
	 * 所有项目修改ajax上传展示图
	 */
	public function ajax_upload_thumb(){

		if(IS_AJAX){

			$map['id'] = I('post.fid');
			if(!empty($_FILES['thumb']['name'])){

				$up_location = 'fundings/show/';
				$thumb =$this->upload_file_jpg($_FILES['thumb'],$up_location);

				$mini_thumb = $this->change_mini_img('./Uploads/'.$thumb);
				$thumb = __ROOT__.'/Uploads/'.$thumb;
				// echo $mini_thumb;die;
				if(!empty($thumb)){
					// echo $img_url; die;
					$User = M('fundings');
					$data['thumb'] = $thumb;
					$data['mini_thumb'] = $mini_thumb;
					$re = $User->where($map)->data($data)->save();

					if(!empty($re)){

						echo json_encode(1);

					}else{

						json_encode(0);
					}
				}
			}
		}
	}

	//所有发起中的众筹项目
	public function spon_lists()
	{
		if(IS_GET){

			$User=M('fundings');
			/**
			数据分页显示开始
			**/
			$count      = $User->where(array('state' => 1))->count();// 查询满足要求的总记录数
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
			// $list = $User->where(array('state' => 1))->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			$list = $User->query("SELECT fd_fundings.*,fd_members.username AS username FROM fd_fundings LEFT JOIN fd_members ON fd_fundings.mid = fd_members.id  WHERE fd_fundings.state = 1 ORDER BY id desc LIMIT $Page->firstRow,$Page->listRows");
			/**
			数据分页结束
			**/

			$institutionid=M('institution');
			foreach ($list as $k => $v) 
			{
				$list[$k]['packageid'] = $this->get_package($v['packageid']);
				$list[$k]['institutionid'] = $institutionid->where(array('id' => $v['institutionid']))->getField('name');
			}


			//循环显示项目状态
			foreach ($list as $k => $v) 
			{

				/**
           		  * 获取对应项目已筹到的金额总数
           		  */
           		 $list[$k]['finish_money'] = $this->get_finish_money($v['id']);

           		 /**
           		  * 计算所需金额  所需金额=检测金额+样品数量*样品单价
           		  */
           		 $list[$k]['target_money'] = $this->get_target_money($v['id']);
			}
			

			$this->assign('list',$list);
			$this->assign('page',$show);
			$this->display('spon_lists');
		}
	}

	/**
	 * 获取众筹中项目详情
	 */
	public function spon_lists_detail(){

		if(IS_GET){

			$id = I('get.id');
			if(!empty($id))
			{
				$map['id'] = $id;
			}

			$list = M('fundings')->where($map)->find();

			$food_classify = M('food_classify');
			$fundings_package = M('fundings_package');
			$institution = M('institution');
			$members = M('members');
			$testreport = M('testreport');
			$fundings_image = M('fundings_image');
			// print_r($list); die;
			//获取主分类的名称
			$list['first_categoryid'] = $food_classify->where(array('id' => $list['first_categoryid']))->getField('name');

			//获取二级分类的名称
			$list['second_categoryid'] = $food_classify->where(array('id' => $list['second_categoryid']))->getField('name');

			//获取套餐项目对应的名称
			$list['packageid'] = $fundings_package->where(array('id' => $list['packageid']))->getField('name');

			//获取对应机构的名称
			$list['institutionid'] = $institution->where(array('id' => $list['institutionid']))->getField('name');
			//项目的状态
			switch ($list['state']) 
			{
				case 0:
					# code...
					$list['state'] = '待审核项目';
					break;
				case 1:
					# code...
					$list['state'] = '发起中项目';
					break;
				case 2:
					# code...
					$list['state'] = '抽检中项目';
					break;
				case 3:
					$list['state'] = '已完成项目';
					break;
				
				default:
					# code...
					$list['state'] = '异常项目';
					break;
			}

			//获取用户id对应的用户表的用户名
			$list['mid'] = $members->where(array('id'=>$list['mid']))->getField('username');
			//获取检测报告对应的下载地址
			$list['testreport'] = $testreport->where(array('id' => $list['testreportid']))->find();
			/**
	   		  * 获取对应项目已筹到的金额总数
	   		  */
	   		$list['finish_money'] = $this->get_finish_money($map['id']);

	   		 /**
	   		  * 计算目标金额  目标金额=检测金额+样品数量*样品单价
	   		  */
	   		$list['target_money'] = $this->get_target_money($map['id']);
			//将储存图片的id的字符串转化为数组
			$list['sampleid'] = explode(',', $list['sampleid']);
			foreach ($list['sampleid'] as $k => $v) 
			{
				# code...
				$list['sampleid'][$k] = $fundings_image->where(array('id' => $v))->getField('img_url'); 
			}
				// print_r($list); die;
			$this->assign('list',$list);
			$this->display();
		}
	}

	/**
	 * 修改众筹项目
	 */
	public function modify_spon_lists(){

		if(!IS_POST){

			$id=I('get.id');
			if(!empty($id)){

				$map['id'] = $id;
			}
			$list=M('fundings')->where($map)->find();

			//将逗号隔开的id串转化为数组
			$list['member_upload_imgs'] = explode(',',$list['member_upload_imgs']);
			foreach ($list['member_upload_imgs'] as $key => $value) {
				# code...
				$list['member_upload_imgs'][$key] = M('fundings_image')->where(array('id' => $value))->find();
			}
			/**
	   		  * 获取对应项目已筹到的金额总数
	   		  */
	   		$list['finish_money'] = $this->get_finish_money($map['id']);

	   		 /**
	   		  * 计算目标金额  目标金额=检测金额+样品数量*样品单价
	   		  */
	   		$list['target_money'] = $this->get_target_money($map['id']);

			//获取mid对用的用户名
			$list['username'] = M('members')->where(array('id' => $list['mid']))->getField('username');

			//把数组中的一级分类ID替换为对应的分类名
			$list['first_categoryid']=M('food_classify')->where(array('id' => $list['first_categoryid']))->getFIeld('name');
			
			//把数组中的二级分类ID替换为对应的分类名
			$list['second_categoryid']=M('food_classify')->where(array('id' => $list['second_categoryid']))->getFIeld('name');

			//把数组中的套餐id替换为对应的套餐名称
			$list['packageid']=M('fundings_package')->where(array('id' => $list['packageid']))->getFIeld('name');

			//把套餐中的机构ID替换为对应的机构名称
			$list['institutionid']=M('institution')->where(array('id' => $list['institutionid']))->getFIeld('name');


			//获取主分类
			$classify_map['pid']=0;
			$classify_map['state']=1;
			$classify=M('food_classify')->where($classify_map)->select();


			//获取检测套餐
			$fundings_packageid = M('fundings_package')->select();

			// 获取检测机构列表
			$institutionid = M('institution')->select();

			//获取来源页面标记
			$this->assign("page_state",I('get.page_state'));

			$this->assign('classify',$classify);
			$this->assign('fundings_packageid',$fundings_packageid);
			$this->assign('institutionid',$institutionid);
			$this->assign('list',$list);

			$this->display();
		}else{

			$id = I('post.id');
			if(!empty($id)){

				$map['id'] = $id;
			}

			$pjname = I('post.pjname');
			if(!empty($pjname)){

				$data['pjname'] = $pjname;
			}

			$pdname = I('post.pdname');
			if(!empty($pdname)){

				$data['pdname'] = $pdname;
			}

			$content = I('post.content');
			if(!empty($content)){

				$data['content'] = $content;
			}

			$brand = I('post.brand');
			if(!empty($brand)){

				$data['brand'] = $brand;
			}

			$first_categoryid = I('post.first_categoryid');
			if(!empty($first_categoryid)){

				$data['first_categoryid'] = $first_categoryid;
			}

			$second_categoryid = I('post.second_categoryid');
			if(!empty($second_categoryid)){

				$data['second_categoryid'] = $second_categoryid;
			}

			$packageid = I('post.packageid');
			if(!empty($packageid)){

				$data['packageid'] = $packageid;
			}

			$institutionid = I('post.institutionid');
			if(!empty($institutionid)){

				$data['institutionid'] = $institutionid;
			}

			$institutionid = I('post.institutionid');
			if(!empty($institutionid)){

				$data['institutionid'] = $institutionid;
			}

			$emoney=I('post.emoney');
			if(!empty($emoney))
			{
				$data['emoney']=$emoney*100;
			}

			$btime=I('post.btime');
			if(!empty($btime))
			{
				$data['btime']= strtotime($btime);
			}

			$etime=I('post.etime');
			if(!empty($pjname))
			{
				$data['etime']=strtotime($etime);
			}

			$samnum=I('post.samnum');
			if(!empty($samnum))
			{
				$data['samnum']=$samnum;
			}

			$smoney = I('post.smoney');
			if(!empty($smoney)){

				$data['smoney'] = $smoney*100;
			}


			$location=I('post.location');
			if(!empty($location))
			{
				$data['location']=$location;
			}

			$rank=I('post.rank');
			if(!empty($rank))
			{
				$data['rank']=$rank;
			}

			$batch=I('post.batch');
			if(!empty($batch))
			{
				$data['batch']=$batch;
			}

			$data['ctime']=I('server.REQUEST_TIME');

			$Fundings_conn = M('fundings');
			// print_r($data); die;
			$result = $Fundings_conn->where($map)->data($data)->save();

			if(!empty($result)){

				$this->success('修改成功!',U('Admin/Funding/spon_lists'),1);
			}else{

				$this->error('修改失败!','',1);
			}
		}
	}

	/**
	 *确认众筹完成进入抽检中
	 */
	public function pass_to_sampling(){

		if(IS_AJAX){

			$id = I('post.id');
			if(!empty($id)){

				$map['id'] = $id;
				$data['state'] = 2;
			}
			$Fundings_conn = M('fundings');
			$re = $Fundings_conn->where($map)->data($data)->save();
			if(!empty($re)){

				echo json_encode(1);
				$this->send_message_to_pass($map['id']);
			}else{

				echo json_encode(0);
			}
		}
	}

	//抽样中的项目
	public function sampling_lists()
	{

		if(IS_POST){


		}else{

			$User=M('fundings');
			/**
			数据分页显示开始
			**/
			$count      = $User->where(array('state' => 2))->count();// 查询满足要求的总记录数
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
			// $list = $User->where(array('state' => 2))->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			$list = $User->query("SELECT fd_fundings.*,fd_members.username AS username FROM fd_fundings LEFT JOIN fd_members ON fd_fundings.mid = fd_members.id  WHERE fd_fundings.state = 2 ORDER BY id desc LIMIT $Page->firstRow,$Page->listRows");
			/**
			数据分页结束
			**/

			//获取对应的套餐类型
			//获取对应的检测机构
			//
			// $fundings_packageid=M('fundings_package');
			$institutionid=M('institution');
			foreach ($list as $k => $v) 
			{
				$list[$k]['packageid'] = $this->get_package($v['packageid']);
				$list[$k]['institutionid'] = $institutionid->where(array('id' => $v['institutionid']))->getField('name');
				$list[$k]['sampleid'] = explode(',', $v['sampleid']);
				/**
				 * 循环得到样品图
				 */
				foreach ($list[$k]['sampleid'] as $key => $value) {
					# code...
					$list[$k]['sampleid'][$key] = M('fundings_image')->where(array('id' =>$value))->getField('img_url');
				}
			}

			// print_r($list); die;
			$this->assign('list',$list);
			$this->assign('page',$show);
			$this->display('sampling_lists');
		}
	}

	/**
	 * 抽检中项目详情
	 */
	public function sampling_lists_detail(){

		if(IS_GET){

			$id = I('get.id');
			if(!empty($id))
			{
				$map['id'] = $id;
			}

			$list = M('fundings')->where($map)->find();

			//分类数据库
			$food_classify = M('food_classify');
			//套餐数据库
			$fundings_package = M('fundings_package');
			//检测机构数据库
			$institution = M('institution');
			//用户数据库
			$members = M('members');
			//检测报告数据库
			$testreport = M('testreport');
			//图片数据表
			$fundings_image = M('fundings_image');
			//获取主分类的名称
			$list['first_categoryid'] = $food_classify->where(array('id' => $list['first_categoryid']))->getField('name');

			//获取二级分类的名称
			$list['second_categoryid'] = $food_classify->where(array('id' => $list['second_categoryid']))->getField('name');

			//获取套餐项目对应的名称
			$list['packageid'] = $fundings_package->where(array('id' => $list['packageid']))->getField('name');

			//获取对应机构的名称
			$list['institutionid'] = $institution->where(array('id' => $list['institutionid']))->getField('name');
				//项目的状态
				switch ($list['state']) 
				{
					case 0:
						# code...
						$list['state'] = '待审核项目';
						break;
					case 1:
						# code...
						$list['state'] = '发起中项目';
						break;
					case 2:
						# code...
						$list['state'] = '抽检中项目';
						break;
					case 3:
						$list['state'] = '已完成项目';
						break;
					
					default:
						# code...
						$list['state'] = '异常项目';
						break;
				}
				//检测结果是否合格
				switch ($list['result']) 
				{
					case 0:
						# code...
						$list['result'] = '检测中..';
						break;
					case 1:
						# code...
						$list['result'] = '合格';
						break;
					
					default:
						# code...
						$list['result'] = '不合格';
						break;
				}
				// echo $list['mid'];die;
				//获取用户id对应的用户表的用户名
				$list['mid'] = $members->where(array('id'=>$list['mid']))->getField('username');
				// echo $list['mid']; die;
				//获取检测报告对应的下载地址
				$list['testreport'] = $testreport->where(array('id' => $list['testreportid']))->find();
				if(!empty($list['testreport']['download'])){

					$list['testreport']['download'] = '<a target="_blank" href="'.$list['testreport']['download'].'">检测证书pdf</a>';
				}else{
					$list['testreport']['download'] = '<a href="#'.'">pdf未上传</a>';
				}

				// $list['testreport']['btime'] = $testreport->where(array('id' => $list['testreportid']))->find()

				if(!empty($list['testreport']['download_jpg'])){

					$list['testreport']['download_jpg'] = '<a target="_blank" href="'.$list['testreport']['download_jpg'].'">检测证书jpg</a>';
				}else{
					$list['testreport']['download_jpg'] = '<a href="#'.'">jpg未上传</a>';
				}

				$list['finish_money'] = $this->get_finish_money($map['id']);

		   		 /**
		   		  * 计算目标金额  目标金额=检测金额+样品数量*样品单价
		   		  */
		   		$list['target_money'] = $this->get_target_money($map['id']);
				//将储存图片的id的字符串转化为数组
				$list['sampleid'] = explode(',', $list['sampleid']);
				foreach ($list['sampleid'] as $k => $v) 
				{
					# code...
					$list['sampleid'][$k] = $fundings_image->where(array('id' => $v))->getField('img_url'); 
				}
				// print_r($list); die;
			$this->assign('list',$list);
			$this->display();
		}
	}

	/**
	 * 抽检中项目修改模板
	 */
	public function modify_sampling_lists(){

		if(IS_POST){

			$id = I('post.id');
			if(!empty($id)){

				$map['id'] = $id;
			}

			if(!empty($_FILES['report_pdf']['name'])){
				$up_location = 'fundings/report/';
				$testreport['download'] = __ROOT__.'/Uploads/'.$this->upload_file_pdf($_FILES['report_pdf'],$up_location);
			}
			if(!empty($_FILES['report_jpg']['name'])){
				$up_location = 'fundings/report/';
				$testreport['download_jpg'] = __ROOT__.'/Uploads/'.$this->upload_file_jpg($_FILES['report_jpg']);
			}

			$Fundings_conn = M('fundings');
			$Testreport_conn = M('testreport'); 


			if(!empty($testreport)){
				//检测时间
				$testreport_btime = I('post.testreport_btime');
				if(!empty($testreport_btime)){

					$testreport['btime'] = strtotime($testreport_btime);
				}
				//检测报告发布时间
				$testreport_ctime = I('post.testreport_ctime');
				if(!empty($testreport_ctime)){

					$testreport['ctime'] = strtotime($testreport_ctime);
				}
				//送检编号
				$testreport_code = I('post.testreport_code');
				if(!empty($testreport_code)){

					$testreport['code'] = $testreport_code;
				}
				//报告解析
				$testreport_content = I('post.testreport_content');
				if(!empty($testreport_content)){

					$testreport['content'] = $testreport_content;
				}
				
				$testreportid = $Testreport_conn->data($testreport)->add();
				// echo $testreportid; die;
			}

			if(!empty($testreportid)){

				$data['testreportid'] = $testreportid;

				$batch = I('post.batch');
				if(!empty($batch)){

					$data['batch'] = $batch;
				}
				$result = I('post.result');
				if(!empty($result)){

					$data['result'] = $result;
				}
				$pid = I('post.pid');
				if(!empty($pid)){

					$data['pid'] = $pid;
				}

				$re = $Fundings_conn->where($map)->data($data)->save();

				if(!empty($re)){

					$this->success('修改成功!',U('Admin/Funding/sampling_lists'),1);
				}else{

					$this->error('修改失败!','',1);
				}
			}else{

				$this->error('请填写完整资料!','',1);
			}

			

		}else{

			$id=I('get.id');
			if(!empty($id)){

				$map['id']=$id;
			}else{

				$this->error('参数出错');
			}

			$list=M('fundings')->where($map)->find();
			/**
			 * 获取用户上传的样品图
			 */
			//将逗号隔开的id串转化为数组
			$list['member_upload_imgs'] = explode(',',$list['member_upload_imgs']);
			foreach ($list['member_upload_imgs'] as $key => $value) {
				# code...
				$list['member_upload_imgs'][$key] = M('fundings_image')->where(array('id' => $value))->find();
			}


			//获取mid对用的用户名
			$list['username'] = M('members')->where(array('id' => $list['mid']))->getField('username');

			//把数组中的一级分类ID替换为对应的分类名
			$list['first_categoryid']=M('food_classify')->where(array('id' => $list['first_categoryid']))->getFIeld('name');
			
			//把数组中的二级分类ID替换为对应的分类名
			$list['second_categoryid']=M('food_classify')->where(array('id' => $list['second_categoryid']))->getFIeld('name');

			//把数组中的套餐id替换为对应的套餐名称
			$list['packageid']=M('fundings_package')->where(array('id' => $list['packageid']))->getFIeld('name');

			//把套餐中的机构ID替换为对应的机构名称
			$list['institutionid']=M('institution')->where(array('id' => $list['institutionid']))->getFIeld('name');

			//检测项目的检测结果证书是否上传
			$list['testreport'] = M('testreport')->where(array('id' => $list['testreportid']))->find();

			$list['finish_money'] = $this->get_finish_money($map['id']);

		   		 /**
		   		  * 计算目标金额  目标金额=检测金额+样品数量*样品单价
		   		  */
	   		$list['target_money'] = $this->get_target_money($map['id']);

			//获取主分类
			$classify_map['pid']=0;
			$classify_map['state']=1;
			$classify=M('food_classify')->where($classify_map)->select();


			//获取检测套餐
			$fundings_packageid = M('fundings_package')->select();

			// 获取检测机构列表
			$institutionid = M('institution')->select();

			//获取来源页面标记
			$this->assign("page_state",I('get.page_state'));

			$this->assign('classify',$classify);
			$this->assign('fundings_packageid',$fundings_packageid);
			$this->assign('institutionid',$institutionid);
			$this->assign('list',$list);

			$this->display();

		}
	}


	/**
	 * 项目完成抽检状态修改为又完成项目
	 */
	
	public function pass_to_finish(){

		if(IS_GET){

			$id = I('get.id');
			if(!empty($id)){

				$map['id'] = $id;
				$data['state'] = 3;
			}

			$Fundings_conn = M('fundings');

			/**
			 * 检查项目是否上传证书.
			 */
			$testreportid = $Fundings_conn->where($map)->getField('testreportid');
			// echo $testreportid; die;
			if(empty($testreportid)){

				$this->error('证书未上传!','',1);
			}
	

			$re = $Fundings_conn->where($map)->data($data)->save();

			if(!empty($re)){
				$this->send_message_to_pass($map['id']);
				$this->success('项目检测已完成!',U('Admin/Funding/sampling_lists'),1);
			}else{

				$this->error('项目状态确认失败!','',1);
			}
		}
	}

	/**
	 * 向用户发送消息系统通知消息
	 */
	private function send_message_to_pass(){

		R('Admin/Sendmessage/sendAndroidmessage_pass_three',array($id));
	}


	//已完成的众筹项目
	public function finish_lists(){

		if(IS_POST){


		}else{

			$User=M('fundings');
			/**
			数据分页显示开始
			**/
			$count      = $User->where(array('state' => 3))->count();// 查询满足要求的总记录数
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
			// $list = $User->where(array('state' => 3))->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			$list = $User->query("SELECT fd_fundings.*,fd_members.username AS username FROM fd_fundings LEFT JOIN fd_members ON fd_fundings.mid = fd_members.id  WHERE fd_fundings.state = 3 ORDER BY id desc LIMIT $Page->firstRow,$Page->listRows");
			/**
			数据分页结束
			**/


			//获取对应的套餐类型
			//获取对应的检测机构
			//
			// $fundings_packageid=M('fundings_package');
			$institutionid=M('institution');
			foreach ($list as $k => $v) 
			{
				$list[$k]['packageid'] = $this->get_package($v['packageid']);
				$list[$k]['institutionid'] = $institutionid->where(array('id' => $v['institutionid']))->getField('name');
			}


			//循环显示项目状态
			foreach ($list as $k => $v) 
			{
				# code...
				switch ($v['result']) 
				{
					case 1:
						$list[$k]['result']='合格';
						break;
					case 2:
						$list[$k]['result']='不合格';
						break;
					default:
						$list[$k]['result']='未知';
						break;
				}
			}
			$this->assign('list',$list);
			$this->assign('page',$show);
			$this->display('finish_lists');
		}
	}

	/**
	 * 抽检中项目详情
	 */
	public function finish_lists_detail(){

		if(IS_GET){

			$id = I('get.id');
			if(!empty($id))
			{
				$map['id'] = $id;
			}

			$list = M('fundings')->where($map)->find();

			//分类数据库
			$food_classify = M('food_classify');
			//套餐数据库
			$fundings_package = M('fundings_package');
			//检测机构数据库
			$institution = M('institution');
			//用户数据库
			$members = M('members');
			//检测报告数据库
			$testreport = M('testreport');
			//图片数据表
			$fundings_image = M('fundings_image');
			//获取主分类的名称
			$list['first_categoryid'] = $food_classify->where(array('id' => $list['first_categoryid']))->getField('name');

			//获取二级分类的名称
			$list['second_categoryid'] = $food_classify->where(array('id' => $list['second_categoryid']))->getField('name');

			//获取套餐项目对应的名称
			$list['packageid'] = $fundings_package->where(array('id' => $list['packageid']))->getField('name');

			//获取对应机构的名称
			$list['institutionid'] = $institution->where(array('id' => $list['institutionid']))->getField('name');
				//项目的状态
				switch ($list['state']) 
				{
					case 0:
						# code...
						$list['state'] = '待审核项目';
						break;
					case 1:
						# code...
						$list['state'] = '发起中项目';
						break;
					case 2:
						# code...
						$list['state'] = '抽检中项目';
						break;
					case 3:
						$list['state'] = '已完成项目';
						break;
					
					default:
						# code...
						$list['state'] = '异常项目';
						break;
				}
				//检测结果是否合格
				switch ($list['result']) 
				{
					case 0:
						# code...
						$list['result'] = '检测中..';
						break;
					case 1:
						# code...
						$list['result'] = '合格';
						break;
					
					default:
						# code...
						$list['result'] = '不合格';
						break;
				}
				// echo $list['mid'];die;
				//获取用户id对应的用户表的用户名
				$list['mid'] = $members->where(array('id'=>$list['mid']))->getField('username');
				// echo $list['mid']; die;
				//获取检测报告对应的下载地址
				$list['testreport'] = $testreport->where(array('id' => $list['testreportid']))->find();
				if(!empty($list['testreport']['download'])){

					$list['testreport']['download'] = '<a target="_blank" href="'.$list['testreport']['download'].'">检测证书pdf</a>';
				}else{
					$list['testreport']['download'] = '<a href="#'.'">pdf未上传</a>';
				}

				if(!empty($list['testreport']['download_jpg'])){

					$list['testreport']['download_jpg'] = '<a target="_blank" href="'.$list['testreport']['download_jpg'].'">检测证书jpg</a>';
				}else{
					$list['testreport']['download_jpg'] = '<a href="#'.'">jpg未上传</a>';
				}

				$list['finish_money'] = $this->get_finish_money($map['id']);

		   		 /**
		   		  * 计算目标金额  目标金额=检测金额+样品数量*样品单价
		   		  */
		   		$list['target_money'] = $this->get_target_money($map['id']);
				//将储存图片的id的字符串转化为数组
				$list['sampleid'] = explode(',', $list['sampleid']);
				foreach ($list['sampleid'] as $k => $v) 
				{
					# code...
					$list['sampleid'][$k] = $fundings_image->where(array('id' => $v))->getField('img_url'); 
				}
				// print_r($list); die;
			$this->assign('list',$list);
			$this->display();
		}
	}


	//异常的众筹项目
	//
	public function abnormal_lists()
	{

		if(IS_POST)
		{


		}
		else
		{

			$User=M('fundings');
			/**
			数据分页显示开始
			**/
			$count      = $User->where(array('state' => 4))->count();// 查询满足要求的总记录数
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
			// $list = $User->where(array('state' => 4))->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			$list = $User->query("SELECT fd_fundings.*,fd_members.username AS username FROM fd_fundings LEFT JOIN fd_members ON fd_fundings.mid = fd_members.id  WHERE fd_fundings.state = 4 ORDER BY id desc LIMIT $Page->firstRow,$Page->listRows");
			/**
			数据分页结束
			**/


			//获取对应的套餐类型
			//获取对应的检测机构
			//
			// $fundings_packageid=M('fundings_package');
			$institutionid=M('institution');
			foreach ($list as $k => $v) 
			{
				$list[$k]['packageid'] = $this->get_package($v['packageid']);
				$list[$k]['institutionid'] = $institutionid->where(array('id' => $v['institutionid']))->getField('name');
			}


			//循环显示项目状态
			foreach ($list as $k => $v) 
			{
				# code...
				switch ($v['state']) 
				{
					case 0:
						$list[$k]['state']='待审核';
						break;
					case 1:
						$list[$k]['state']='发起中';
						break;
					case 2:
						$list[$k]['state']='抽样中';
						break;
					case 3:
						$list[$k]['state']='已完成';
						break;

					default:
						$list[$k]['state']='异常状态';
						break;
				}
			}
			$this->assign('list',$list);
			$this->assign('page',$show);
			$this->display('abnormal_lists');
		}
	}


	//添加项目
	//
	public function add()
	{
		if(IS_POST)
		{

			
			$pjname=I('post.pjname');
			if(!empty($pjname))
			{

				$data['pjname']=$pjname;
			}
			else
			{

				$this->error('项目名称不能为空');
			}


			$rank=I('post.rank');
			if(!empty($rank))
			{

				$data['rank']=$rank;
			}


			$finish=I('post.finish');
			if(!empty($finish))
			{

				$data['finish']=$finish*100;
			}

			$pdname=I('post.pdname');
			if(!empty($pdname))
			{

				$data['pdname']=$pdname;
			}
			else
			{

				$this->error('产品名不能为空');
			}

			$brand=I('post.brand');
			if(!empty($brand))
			{

				$data['brand']=$brand;
			}
			else
			{

				$this->error('品牌名不能为空');
			}

			$content=I('post.content');
			if(!empty($content))
			{

				$data['content']=$content;
			}

			$second_categoryid=I('post.second_categoryid');

			if(!empty($second_categoryid))
			{

				$data['second_categoryid']=$second_categoryid;
			}
			else
			{

				$this->error('请选择二级');
			}

			$first_categoryid=I('post.first_categoryid');

			if(!empty($first_categoryid))
			{

				$data['first_categoryid']=$first_categoryid;
			}
			else
			{

				$this->error('请选择分类');
			}

			$packageid=I('post.packageid');
			if(!empty($packageid))
			{

				$data['packageid']=$packageid;
			}
			else
			{

				$this->error('请选择检测套餐');
			}


			$institutionid=I('post.institutionid');
			if(!empty($institutionid))
			{

				$data['institutionid']=$institutionid;
			}
			else
			{

				$this->error('请选择检测机构');
			}


			$emoney=I('post.emoney');
			if(!empty($emoney))
			{

				$data['emoney']=$emoney*100;
			}
			else
			{

				$this->error('项目额度不能为空');
			}

			$data['state']=0;

			$btime=I('post.btime');
			if(!empty($btime))
			{

				$data['btime']= strtotime($btime);
			}
			else
			{

				$this->error('开始时间不能为空');
			}

			$etime=I('post.etime');
			if(!empty($pjname))
			{

				$data['etime']=strtotime($etime);
			}
			else
			{

				$this->error('结束时间不能为空');
			}


			$mid=I('post.mid');
			if(!empty($mid))
			{

				$data['mid']=$mid;
			}
			else
			{

				$this->error('发起人不能为空');
			}


			$samnum=I('post.samnum');
			if(!empty($samnum))
			{

				$data['samnum']=$samnum;
			}

			$location=I('post.location');
			if(!empty($location))
			{

				$data['location']=$location;
			}

			$smoney = I('post.smoney');
			if(!empty($smoney)){

				$data['smoney'] = $smoney*100;
			}
			print_r($data); die;
			$data['ctime']=I('server.REQUEST_TIME');

			$re=M('fundings')->data($data)->add();

			if(!empty($re))
			{
				$this->success('添加项目成功!',U('Admin/Funding/new_lists'));
			}

		}
		else
		{
			//获取主分类
			$User=M('food_classify');
			$map['pid']=0;
			$map['state']=1;
			$list=$User->where($map)->select();


			//获取检测套餐
			$fundings_packageid = M('fundings_package')->select();

			// 获取检测机构列表
			$institutionid = M('institution')->select();

			$this->assign('list',$list);
			$this->assign('fundings_packageid',$fundings_packageid);
			$this->assign('institutionid',$institutionid);
			$this->display('add');
		}
		
	}



	//列表页直接上传抽样图
	public function uploadify_img()
	{
		if(!IS_POST){

			$id = I('get.id');

			if(!empty($id)){

				$map['id'] = $id;
				$this->assign('id',$id);
			}else{

				$this->error('参数错误!');
			}
			$fundings = M('fundings');
			$fundings_image = M('fundings_image');
			$sampleid = $fundings->where($map)->getField('sampleid');
			//将字符串转化为数组
			$sampleid = explode(',',$sampleid);
			//循环数组得到对应的图片链接
			foreach ($sampleid as $key => $value) {
				# code...
				$sampleid[$key] = $fundings_image->where(array('id' => $value))->find();
			}
			$this->assign('list',$sampleid);
			$this->display();
		}

	}


	//ajax获取对应的二级目录
	public function ready_classify(){

		$map['pid']=I('post.id');
		$User=M('food_classify');
		$list=$User->where($map)->select();
		echo json_encode($list);
	}

	/**
	 * ajax上传项目样品图
	 *  [type] [description]
	 */
	public function upload_sample_image(){
		
		if(IS_AJAX){
			if(!empty($_FILES['sample0']['name'])){
				
				$id = I('post.id');
				if(!empty($id)){

					$map['id'] = $id;
				}
				//对图片进行上传
				$up_location = 'fundings/original/';
				$sample = $this->upload_images($_FILES,$up_location);
				
				$fundings_image = M('fundings_image');
				$fundings = M('fundings');
				//获取数据库已有的样品图片
				$old_sampleid = $fundings->where($map)->getField('sampleid');

				foreach ($sample as $k => $v) {
					
					$sample[$k] = __ROOT__.'/Uploads/'.$v;
					$data['img_url'] = $sample[$k];
					$sampleid[] = $fundings_image->data($data)->add();
				}

				$sampleid = implode(',',$sampleid);
				//拼接新加入的图片id和老图片id
				if(!empty($sampleid)){
					if(!empty($old_sampleid)){
						$sdata['sampleid'] = $old_sampleid.','.$sampleid;
					}else{

						$sdata['sampleid'] = $sampleid;
					}
					// print_r($sdata); die;
					$result = $fundings->where($map)->data($sdata)->save();
					if(!empty($result)){
						
						echo json_encode(1);
					}else{

						echo json_encode(0);
					}
				}

			}else{

				echo json_encode(0);
			}
		}
	}

	/**
	 * 删除项目样品图
	 */
	public function delete_sample(){

		$sid = I('post.sid');

		$id = I('post.id');
		if(!empty($id)){

			$map['id'] = $id;
		}

		$fundings = M('fundings');
		$sampleid = $fundings->where($map)->getField('sampleid');
		//检测该图片是否存在于该项目中 如果存在则删除不存在不进行操作
		$arr_sampleid = explode(',',$sampleid);
		foreach ($arr_sampleid as $k => $v) {
			# code...
			if($arr_sampleid[$k] == $sid){

				unset($arr_sampleid[$k]);
			}
		}

		$data['sampleid'] = implode(',',$arr_sampleid);

		$re = $fundings->where($map)->data($data)->save();
		if(!empty($re)){

			echo json_encode(1);
		}else{

			echo json_encode(0);
		}

	}

//  图片上传类
	private function upload_images($file,$up_location)
	{
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =     3145728 ;// 设置附件上传大小
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath  =      $up_location; // 设置附件上传目录// 上传文件 
		$info   =   $upload->upload($file);

		if(!$info)
		{
		// 上传错误提示错误信息    
			$this->error($upload->getError());
		}
		else
		{
			// 上传成功 获取上传文件信息      
			foreach($info as  $v)
			{        
				 $img_url[]=$v['savepath'].$v['savename'];    
			}
			return $img_url;
		}
		
	}


	// 上传众测项目展示图 发起中
	public function upload_funding_thumb(){

		if(IS_POST){

			$id = I('post.fid');
			// echo $id; die;
			if(!empty($id)){

				$map['id'] = $id;
			}else{

				$this->error('参数错误');
			}
			// echo $map['id']; die;
			if(!empty($_FILES['thumb']['name'])){

				$up_location = 'fundings/show/';
				$thumb =$this->upload_file_jpg($_FILES['thumb'],$up_location);

				$mini_thumb = $this->change_mini_img('./Uploads/'.$thumb);
				$thumb = __ROOT__.'/Uploads/'.$thumb;
				// echo $mini_thumb;die;
				if(!empty($thumb)){
					// echo $img_url; die;
					$User = M('fundings');
					$data['thumb'] = $thumb;
					$data['mini_thumb'] = $mini_thumb;
					$re = $User->where($map)->data($data)->save();

					if(!empty($re)){

						$this->success('上传成功!',U('Admin/Funding/spon_lists'));

					}else{

						$this->error('上传失败!');
					}
				}
			}
		}
	}


	// 上传众测项目展示图 待审核
	public function upload_funding_thumb_newlists(){

		if(IS_POST){

			$id = I('post.fid');
			// echo $id; die;
			if(!empty($id)){

				$map['id'] = $id;
			}else{

				$this->error('参数错误');
			}
			// echo $map['id']; die;
			if(!empty($_FILES['thumb']['name'])){

				$up_location = 'fundings/show/';
				$thumb =$this->upload_file_jpg($_FILES['thumb'],$up_location);

				$mini_thumb = $this->change_mini_img('./Uploads/'.$thumb);
				$thumb = __ROOT__.'/Uploads/'.$thumb;
				// echo $mini_thumb;die;
				if(!empty($thumb)){
					// echo $img_url; die;
					$User = M('fundings');
					$data['thumb'] = $thumb;
					$data['mini_thumb'] = $mini_thumb;
					$re = $User->where($map)->data($data)->save();

					if(!empty($re)){

						$this->success('上传成功!',U('Admin/Funding/new_lists'));

					}else{

						$this->error('上传失败!');
					}
				}
			}
		}
	}


	//图像等比例缩小裁剪
	private function change_mini_img($img)
	{
		$image = new \Think\Image(); 
		$image->open("$img");
		$img_name=$this->img_name().'.jpg';
		$re_small_url=__ROOT__.'/'.'Uploads'.'/'.'fundings'.'/'.'mini_show'.'/'.$img_name;
		$small_url='./Uploads'.'/'.'fundings'.'/'.'mini_show'.'/'.$img_name;
		// echo $small_url; die;
		// 生成一个居中裁剪为30*30的缩略图并保存
		$image->thumb(150, 150,\Think\Image::IMAGE_THUMB_FILLED)->save($small_url);
		return $re_small_url;
	}


	//  文件上传 pdf 上传
	private function upload_file_pdf($file,$up_location){
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =   6291456;// 设置附件上传大小
		$upload->exts      =     array('pdf');// 设置附件上传类型
		$upload->savePath  =      $up_location; // 设置附件上传目录
		// 上传文件 
		$info   =   $upload->uploadOne($file);
		if(!$info){
		// 上传错误提示错误信息    
			$this->error($upload->getError());
		}else{  
			$file_url=$info['savepath'].$info['savename'];    
			return $file_url;
		}
	}


	//  文件上传 图片格式上传
	private function upload_file_jpg($file,$up_location){

		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =     31457280 ;// 设置附件上传大小
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath  =      $up_location; // 设置附件上传目录
		// 上传文件 
		$info   =   $upload->uploadOne($file);
		if(!$info){
		// 上传错误提示错误信息    
			$this->error($upload->getError());
		}else{   
			$file_url=$info['savepath'].$info['savename'];    
			return $file_url;
		}
	}



	//生成随机图片名
	public function img_name(){
		$time=I('server.REQUEST_TIME');
		return $time.str_pad(mt_rand(1,999999),5,'0', STR_PAD_LEFT);
	}





	//ajax删除众筹项目
	public function del(){

		$id=I('post.id');
		$User=M('fundings');
		$re=$User->where(array('id' => $id))->delete();
		if($re){
			echo json_encode(1);
		}
	}

	/**
	 * ajax 删除用户上传的图片
	 */
	
	public function ajax_del_member_upload_imgs(){

		$fid = I('post.fid');
		if(!empty($fid)){

			$map['id'] = $fid;
		}

		$mupid = I('post.mupid');
		if(!empty($mupid)){

			$mupid = $mupid;
		}

		//获取到当前项目的所有用户上传的样品图id并转化为数组
		$member_upload_imgs = M('fundings')->where($map)->getField('member_upload_imgs');
		$member_upload_imgs = explode(',',$member_upload_imgs);
		// print_r($member_upload_imgs);

		/**
		 * 将取得图片id与数组对比 如果包含则删除
		 */
		foreach ($member_upload_imgs as $k => $v) {
			# code...
			if($v == $mupid){

				unset($member_upload_imgs[$k]);
			}
		}

		//将数组再次转化为字符串
		$data['member_upload_imgs'] = implode(',',$member_upload_imgs);

		$result = M('fundings')->where($map)->data($data)->save();

		if(!empty($result)){

			echo json_encode(1);
		}else{

			echo json_encode(0);
		}

	}


	//众筹项目参与用户表
	public function sponsor()
	{
		if(!IS_POST)
		{

			$id = I('get.id');
			if(!empty($id)){

				$map['fid'] = $id;
				$map['status'] = 1;
			}
			$fundings_sponsor = M('fundings_sponsor');
			/**
			数据分页显示开始
			**/
			$count      = $fundings_sponsor->where($map)->count();// 查询满足要求的总记录数
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
			$list = $fundings_sponsor->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
			/**
			数据分页结束
			**/
			// $list = M('fundings_sponsor')->where($map)->select();

			$User = M('members');
			foreach ($list as $k => $v) 
			{
				$list[$k]['username'] = $User->where(array('id' => $v['mid']))->getField('username');
				switch ($v['paytype']) 
				{
					case 0:
						$list[$k]['paytype']='未知';
						break;
					case 1:
						$list[$k]['paytype']='微信';
						break;
					case 2:
						$list[$k]['paytype']='支付宝';
						break;
					default:
						$list[$k]['paytype']='银联';
						break;
				}

				switch ($v['status']) 
				{
					case 0:
						$list[$k]['status']='支付中';
						break;
					case 1:
						$list[$k]['status']='支付成功';
						break;
					default:
						$list[$k]['status']='支付失败';
						break;
				}
			}

			$this->assign('list',$list);
			$this->assign('page',$show);

			$this->display();
		}
	}

	//同意未审核的项目
	public function agree(){

		if(!IS_POST){
			$state = I('get.state');
			$id = I('get.id');

			if(!empty($id))
			{
				$map['id'] = $id;

			}else{
				$this->error('参数出错!');
			}
			$DB_fundings = M('fundings');
			/**
			 * 对项目进行检测查看资料是否填写完整
			 */
			$list = $DB_fundings->where($map)->field('pjname,pdname,brand,content')->find();

			//循环检测资料是否填写完整 如果不完整则不能通过
			foreach ($list as $k => $v) {
				# code...
				if(empty($v)){

					$this->error('资料不完整不能通过!','',1);
					break;
				}
			}

			//查询项目是否已经上传展示图
			$thumb = $DB_fundings->where($map)->getField('thumb');
			//如果已经上传展示图则直接通过 否则则提示上传展示图
			if(!empty($thumb)){

				$data['state'] = $state;
				$re=M('fundings')->where($map)->data($data)->save();

				if($re)
				{
					R('Admin/Sendmessage/sendAndroidmessage_pass_one',array($id));
					$this->success('已通过!',U('Admin/Funding/new_lists'));
				}
			}else{

				$this->error('请先上传项目展示图');
			}	
		}
	}

	//拒绝未审核的项目
	public function refused(){

		if(!IS_POST){

			$id = I('get.id');

			if(!empty($id)){
				$map['id'] = $id;
			}else{
				$this->error('参数出错!');
			}

			$data['state'] = 4;
			$re=M('fundings')->where($map)->data($data)->save();

			if($re){
				$this->success('已拒绝!',U('Admin/Funding/new_lists'));
			}
		}
	}


	//ajax搜索产品
	public function select_product_name(){
		$select_keywords = I('post.select_keywords');

		$User = M('product');
		$map['name'] = array('like',"%$select_keywords%");

		$list = $User->where($map)->select();

		echo json_encode($list);
	}


	//ajax搜索用户手机号
	
	public function select_members_mobile(){

		$select_keywords = trim(I('post.select_keywords'));

		if(!empty($select_keywords)){

			$map['mobile'] = array('like',"%$select_keywords%");
		}
		

		$User = M('members');

		$list = $User->where($map)->select();

		if(!empty($list)){

			echo json_encode($list);
		}

	}



	/**
	ajax删除待审核多个项目或者打个项目
	**/
	public function del_fundings(){

		$id=I('get.id');

		$User = M('fundings');
		if(is_array($id)){

			foreach ($id as $k => $v) {
				$map['id'] = $v;
				$re = $User->where($map)->delete();
			}
		}else{

			$map['id'] = $id;
			$re = $User->where($map)->delete();
		}

		if(!empty($re)){

			echo json_encode(1);
		}

	}



	/**
	 * 修改用户支付的金额
	 */
	
	public function modify_sponsor_pay_money(){

		if(IS_AJAX){

			$fundings = M('fundings');
			$fundings_sponsor = M('fundings_sponsor');

			$sid = I('post.sid');
			if(!empty($sid)){

				$smap['id'] = $sid;
			}

			$pay_money = I('post.pay_money');
			if(!empty($pay_money)){

				$money = $pay_money*100;
			}

			$sponsor_list = $fundings_sponsor->where($smap)->find();
			if(!empty($sponsor_list)){

				$fmoney = $money - $sponsor_list['money'];
				$sdata['money'] = $money;
				$fmap['id'] = $sponsor_list['fid'];
				//获取该项目已完成的金额
				$finish = $fundings->where($fmap)->getField('finish');
				$fdata['finish'] = $finish + $fmoney;

				//更新新的完成金额
				$fresult = $fundings->where($fmap)->data($fdata)->save();

				if($fresult){

					$sresult = $fundings_sponsor->where($smap)->data($sdata)->save();

					if($sresult){

						echo json_encode(1);
					}else{

						echo json_encode(0);
					}
				}
			}
		}
	}


	/**
	 * 删除用户支付
	 */
	public function delete_sponsor_pay_money(){

		if(IS_AJAX){

			$fundings = M('fundings');
			$fundings_sponsor = M('fundings_sponsor');

			$id = I('post.id');
			if(!empty($id)){

				$smap['id'] = $id;
				$smap['status'] = 1;
			}

			$list = $fundings_sponsor->where($smap)->find();

			if(!empty($list)){

				$pay_money = $list['money'];
				$fmap['id'] = $list['fid'];

				$flist = $fundings->where($fmap)->find();

				if(!empty($flist)){

					$fdata['finish'] = $flist['finish'] - $pay_money;
					$fre = $fundings->where($fmap)->data($fdata)->save();

					if($fre){

						$sre = $fundings_sponsor->where($smap)->delete();

						if($sre){

							echo json_encode(1);
						}else{

							echo json_encode(0);
						}
					}
				}
			}

		}
	}
}