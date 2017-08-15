<?php

//搜索页面

namespace M\Controller;
use Think\Controller;

class MemberController extends CommonController {
    public function index() {
//        用户中心首页
        $this->assign("memberinfo", $this->memberinfo);
//        print_r($this->memberinfo);
        
        
        $this->display();
    }
    
    /*
     * Date: 2015-10-19
     * Des: 通知中心
     */
    public function notifition_center() {
        
    }
    


    /**
    订单列表
    **/
    public function my_orders(){
        $mid = $this->memberinfo['id'];   

        //我参与的
//        $my_takepart = $this->get_my_takepart($mid);
        $my_takepart = $this->get_my_orders($mid);
        

        //我发起的
        $my_setup = $this->get_my_setup($mid);
        
        foreach ($my_takepart as $key => $value) {
            if(mb_strlen($value['packagename'],"utf-8")>8)
            {
//                echo $value['pjname'];
                $my_takepart[$key]['packagename'] = mb_substr($value['packagename'], 0,8,'utf-8')."...";
            }
        }
        
            

        $this->assign('my_setup',$my_setup);
        $this->assign('my_takepart',$my_takepart);
        $this->assign("specialid", 1);
        $this->display();
    }

    //我参与的项目
    private function get_my_takepart($mid){

        $DB_sponsor = M('fundings_sponsor');
        $map_sponsor['mid']=$mid;
        $map_sponsor['status']=1;
        
        $res_sponsor = $DB_sponsor->where($map_sponsor)->group('fid')->order("paytime DESC")->getField('fid',true);
        // print_r($res_sponsor); die;
        if(!empty($res_sponsor))
        {
            $User = M('fundings');
            foreach ($res_sponsor as $k => $v) {
               $my_takepart[] = $User->where(array('id' => $v))->find(); 
            }
        }

        if(!empty($my_takepart)){
            foreach ($my_takepart as $key => $value) {
                if($this->_chk_founding_payment_status($value['id']))
                {
                    $my_takepart[$key]['packagename']=  $this->get_name_by_packageid($value['packageid']);
                    $my_takepart[$key]['show_mark_icon']=  $this->_get_fouding_list_mark(0,$value['result'],$value['state']);
                }
                else
                {
                    unset($my_takepart[$key]);
                }
                
            }
            return $my_takepart;
        }
    }
    
    /*
     * 获取我的项目（包括我发起的和我参与的）
     * 1.先按状态排 审核中-众筹中-抽检中-检测完成-已取消
     * 2.每个状态中，按发起时间排序，时间最近的排在后面
     * 
     * 列表个数：首次加载10个；拖拽到底部，每次加载10个
     */
    public function get_my_orders($mid) {
//        $mid = 200;
        
        $DB_sponsor = M('fundings_sponsor');
        
        
//        $map_sponsor['mid']=$mid;
//        $map_sponsor['status']=1;
//        
//        $res_sponsor = $DB_sponsor->where($map_sponsor)->group('fid')->order("paytime DESC")->getField('fid',true);
        
        $my_takepart = $DB_sponsor->query("SELECT `fd_fundings_sponsor`.`id` as id,`fd_fundings_sponsor`.`fid` as fid, `fd_fundings`.`ctime` as ctime,`fd_fundings`.`batch` as batch, `fd_fundings`.`packageid` as packageid,`fd_fundings`.`pjname` as pjname,`fd_fundings`.`state` as state,`fd_fundings`.`location` as location,`fd_fundings`.`result` as result,`fd_fundings`.`thumb` as thumb FROM `fd_fundings_sponsor`  LEFT JOIN `fd_fundings` ON `fd_fundings_sponsor`.fid = `fd_fundings`.id WHERE `fd_fundings_sponsor`.`mid`=".$mid." and `fd_fundings_sponsor`.`status`=1  GROUP BY fid ORDER BY state,ctime");
//        print_r($my_takepart);
//        echo $DB_sponsor->getLastSql();
//        exit();
        // print_r($res_sponsor); die;
//        if(!empty($res_sponsor))
//        {
//            $User = M('fundings');
//            foreach ($res_sponsor as $k => $v) {
//               $my_takepart[] = $User->where(array('id' => $v))->find(); 
//            }
//        }

        if(!empty($my_takepart)){
            $DB_f = M('fundings');
            foreach ($my_takepart as $key => $value) {
                
                $res_tmp =  $DB_f->find($value['fid']);
                
                if(!empty($res_tmp))
                {
                    $my_takepart2[$key]=$my_takepart[$key];
                    $my_takepart2[$key]['packagename']=  $this->get_name_by_packageid($value['packageid']);
                    $my_takepart2[$key]['show_mark_icon']=  $this->_get_fouding_list_mark(0,$value['result'],$value['state']);
                }
                
//                if($this->_chk_founding_payment_status($value['id']))
//                {
//                    $my_takepart[$key]['packagename']=  $this->get_name_by_packageid($value['packageid']);
//                    $my_takepart[$key]['show_mark_icon']=  $this->_get_fouding_list_mark(0,$value['result'],$value['state']);
//                }
//                else
//                {
//                    unset($my_takepart[$key]);
//                }
                
                
                
            }
//            print_r($my_takepart);
            return $my_takepart2;
            
        }
    }
    
    
    /*
        * 判断是否支付了该订单
        */
       private function _chk_founding_payment_status($fdid) {
           $map['fid']=$fdid;
           $map['status']=1;
           $DB = M("fundings_sponsor");
           
           $res = $DB->where($map)->find();
           
           if(!empty($res))
           {
               return TRUE;
           }
           else
           {
               return FALSE;
           }
           
           
       }
    
    
    
    /*
     * 获取套餐资料
     */
    private function get_name_by_packageid($id) {
        /*
         * 判断id 是单个还是多少
         */
        $id_arr = explode(',', $id);
        $DB = M('fundings_package');
        $name="";
        foreach ($id_arr as $key => $value) {
            $res[$key] = $DB->find($value);
            $name .=",".$res[$key]['name'];
//            echo $name;
        }
        
        
        
        return substr($name, 1);
    }
    
     /*
     * 计算项目要显示的图标
     */
     protected function _get_fouding_list_mark($type=0,$isqualified,$state) {
        switch ($state) {
            case 0:

//                return "img/verifying_mark.png";
                return "img/fouding_mark.png";
                break;
            case 1:
                return "img/fouding_mark.png";
                break;
            case 2:
                return "img/testing_mark.png";

                break;
            case 3:
                if($isqualified==1)
                {
                    return "img/qualified_mark.png";
                }
                else
                {
                    return "img/unqualified_mark.png";
                }
                break;
            case 4:
                return "img/cancel_mark.png";

                break;

            default:
                return "img/testing_mark.png";
                break;
        }
        
    }

    //我发起的项目
    private function get_my_setup($mid){

        $map['mid'] = $mid;
        $User = M('fundings');

        $my_setup = $User->where($map)->select();

        if(!empty($my_setup)){

            return $my_setup;
        }
    }


    public function feedback(){
        if(IS_GET){

            $this->display();
        }
    }

    public function put_feedback(){

        if(IS_AJAX){
            $mid = $this->memberinfo['id'];
            $contact = I('post.contact');
            $content = I('post.content');

            if(!empty($contact)){

                $data['contact'] = $contact;
            }
            if(!empty($content)){

                $data['content'] = $content;
            }

            $data['ctime'] = I('server.REQUEST_TIME');
            $data['mid'] = $mid;
            $User = M('feedback');
            $result = $User->data($data)->add();

            if(!empty($result)){

                echo json_encode(1);
            }else{

                echo json_encode(0);
            }
        }
    }


    //个人资料修改
    //
    public function my_profile(){

        if(IS_GET){
            $map['id'] = $this->memberinfo['id'];

            $User = M('members');

            $list = $User->where($map)->find();
            
            $this->assign('list',$list);            
            $this->display();
        }
    }


    //修改手机号码
    public function modify_mobile(){

        if(IS_GET){
            $this->assign("memberinfo", $this->memberinfo);
            $this->display();
        }else if(IS_AJAX){

            $mobile = I('post.mobile');
            // echo  json_encode(2); die;
            if(!empty($mobile)){

                $data['mobile'] = $mobile;
            }
            $map['id'] = $this->memberinfo['id'];

            $User = M('members');

            $result = $User->where($map)->data($data)->save();
            if(!empty($result)){

                echo json_encode(1);
            }else{

                echo json_encode(0);
            }
        }
    }

    //修改性别
    public function modify_sex(){

        if(IS_GET){

            $this->display();
        }else if(IS_AJAX){

            $sex = I('post.sex');
            // echo  json_encode(2); die;
            if(isset($sex)){

                $data['gender'] = $sex;
            }
            $map['id'] = $this->memberinfo['id'];

            $User = M('members');

            $result = $User->where($map)->data($data)->save();
            if(!empty($result)){

                echo json_encode(1);
            }else{

                echo json_encode(0);
            }
        }
    }



    //修改生日信息
    public function modify_birthday(){

         if(IS_GET){
            $member = $this->memberinfo;
            $member['birthday_s'] = $this->sub_str_birthday($member['birthday']);
            $this->assign("memberinfo", $member);
            $this->display();
        }else if(IS_AJAX){

            $birthday = I('post.birthday');
            // echo $birthday;die;
            if(!empty($birthday)){

                $data['birthday'] = $birthday;
            }

            $map['id'] = $this->memberinfo['id'];

            $User = M('members');

            $result = $User->where($map)->data($data)->save();
            if(!empty($result)){

                echo json_encode(1);
            }else{

                echo json_encode(0);
            }
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


    //修改用户的详细地址
    public function modify_location(){

        if(IS_GET){
            $this->assign("memberinfo", $this->memberinfo);
            $this->display();
        }else if(IS_AJAX){

            $province = I('post.province');
            // echo $province; die;
            if(!empty($province)){

                $data['province'] = M('regions')->where(array('code' => $province))->getField('name');
            }

            $city = I('post.city');
            if(!empty($city)){

                $data['city']  = M('regions')->where(array('code' => $city))->getField('name');
                // $data['code'] = $city;
            }

            $address = I('post.address');
            if(!empty($address)){

                $data['address'] = $address;
            }

            // print_r($data); die;
            $map['id'] = $this->memberinfo['id'];

            $User = M('members');
            $result = $User->where($map)->data($data)->save();
            // var_dump($result); die;
            if(!empty($result)){

                echo json_encode(1);
            }else{

                echo json_encode(0);
            }
        }
    }



    //修改用户名
    public function modify_name(){

         if(IS_GET){
            $this->assign("memberinfo", $this->memberinfo);
            $this->display();
        }else if(IS_AJAX){

            $username = I('post.username');
            
            if(!empty($username)){

                $data['username'] = $username;
            }
            $map['id'] = $this->memberinfo['id'];

            $User = M('members');

            $result = $User->where($map)->data($data)->save();
            if(!empty($result)){

                echo json_encode(1);
            }else{

                echo json_encode(0);
            }
        }
    }
}