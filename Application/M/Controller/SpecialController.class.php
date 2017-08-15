<?php
namespace M\Controller;
use Think\Controller;
class SpecialController extends CommonController {

	//资讯详情
	public function special(){
            
            /*
            * Date: 2015-12-16
            * 增加一个判断是否是关注用户
            * 
            */

//           $member = $this->memberinfo;
           $openid = session("openid");

           $DB_openid = M("members_subscribe_records");
           $map_openid['openid']=$openid;
           $map_openid['issubscribe']=1;
           $res_openid = $DB_openid->where($map_openid)->find();

           if(empty($res_openid))
           {
               $this->assign('issubscribe', 0);
           }
           else
           {
               $this->assign('issubscribe', 1);
           }

		$id = I('get.id');
		if(!empty($id)){

			$map['id'] = $id;
		}
		$User = M('special');
		$list = $User->where($map)->find();
		$list['content'] = htmlspecialchars_decode($list['content']);
		// print_r($get_new); die;
                
		$this->assign('list',$list);
		$this->display(); 
	}
}
?>