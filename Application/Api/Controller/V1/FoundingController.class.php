<?php
namespace Api\Controller\V1;
use Think\Controller;
/**
* @name Lukeyan
* @abstract 申明变量/类/方法
* @access 指明这个变量、类、函数/方法的存取权限
* @author 函数作者的名字和邮箱地址
* @category  组织packages
* @copyright 指明版权信息
* @const 指明常量
* @deprecate 指明不推荐或者是废弃的信息MyEclipse编码设置
* @example 示例
* @exclude 指明当前的注释将不进行分析，不出现在文挡中
* @final 指明这是一个最终的类、方法、属性，禁止派生、修改。
* @global 指明在此函数中引用的全局变量get_share
* @include 指明包含的文件的信息
* @link 定义在线连接
* @module 定义归属的模块信息
* @modulegroup 定义归属的模块组
* @package 定义归属的包的信息
* @param 定义函数或者方法的参数信息
* @return 定义函数或者方法的返回信息
* @see 定义需要参考的函数、变量，并加入相应的超级连接。
* @since 指明该api函数或者方法是从哪个版本开始引入的
* @static 指明变量、类、函数是静态的。
* @throws 指明此函数可能抛出的错误异常,极其发生的情况
* @todo 指明应该改进或没有实现的地方
* @var 定义说明变量/属性。
* @version 定义版本信息
*/

/*
 * 众筹功能页面
 */
class FoundingController extends CommonController {
    public function index(){
        header("Content-type:text/html;charset=utf8;");
        echo "众测天下";
    }
    
    
    /*
     * 众筹项目列表功能
     * 
     * @acccess public
     * @param type 列表的规则（所有、众筹中、抽样中、检验结束）
     * @return 众筹项目列表的数组
     */
    public function get_all() {
        $type = trim(I('get.type'));
        $mid = trim(I('get.mid'));
        $password = trim(I('get.password'));
        $mtype = trim(I('get.mtype'));
        $pagecount = I('get.pagecount');
        $keywords = trim(I('get.keywords'));
        
        /*
         * 过滤掉未支付的订单
         */
        
        
        if(empty($pagecount))
        {
            $pagecount=0;
        }
        
        
        if(!empty($type))
        {
            $map['state']=$type;
        }
        
        if($mtype>0)
        {
//            会员中心的地方使用，0 为默认值，1 是我发起的，2 是我参与的，3 是我关注的，只有在mid 大于0的情况下有用
            
            switch ($mtype) {
                case 1:
                    $map['mid']=$mid;
                    break;
                case 2:
//                    我参与的项目的算法
                    /*
                     * 从投钱成功的表中取得数据
                     * fd_fundings_sponsor
                     * 
                     */
                    $DB_sponsor = M('fundings_sponsor');
                    $map_sponsor['mid']=$mid;
                    $map_sponsor['status']=1;
                    
                    $res_sponsor = $DB_sponsor->where($map_sponsor)->order("state,ctime")->select();
//                    echo $DB_sponsor->getLastSql();
                    if(!empty($res_sponsor))
                    {
                        foreach ($res_sponsor as $k => $v) {
                           $id_arr[]=$v['fid']; 
                        }
                    }
                    $map['id']=array('in',$id_arr);

                    break;
                    
                case 3:
                    $map['mid']=$mid;
                    break;

                default:
                    $map['mid']=$mid;
                    break;
            }
            
        }
        
        if(!empty($keywords))
        {
            $map['pjname']=array("like","$keywords");
        }
        
        $DB = M('fundings');
        $res = $DB->where($map)->order('id DESC')->select();
        if(!empty($res))
        {
            foreach ($res as $key => $value) {
                $res[$key]['packagename']=  $this->get_packagename_by_id($value['packageid']);
                $res[$key]['institution']= $this->get_institutionname_by_id($value['institutionid']);
                $res[$key]['testing_date']= date("Y-m-d",$value['ctime']);
                if(empty($value['thumb']))
                {
                    $res[$key]['thumb']="http://test.mdjkj.com/foodcase/Public/assets/img/bg_fix.png";
                }
            }
        }        
        
        $page_res=array();
        foreach ($page_res as $key => $value) {
            if(($key>=$pagecount*10)&&($key<($pagecount*20+20)))
            {
                array_push($page_res, $value);
            }
        }

        
        $this->json_output($res);
   
    }
    
    public function get_latest() {
        $type = 3;
        $mid = trim(I('get.mid'));
        $password = trim(I('get.password'));
        
        
        if(!empty($type))
        {
            $map['state']=$type;
        }

        
        
        $DB = M('fundings');
//        $map
        $res = $DB->where($map)->order('id DESC')->limit(10)->select();
        if(!empty($res))
        {
            foreach ($res as $key => $value) {
                $res[$key]['packagename']=$this->get_packagename_by_id($value['packageid']);
                $res[$key]['institution']=$this->get_institutionname_by_id($value['institutionid']);
                $res[$key]['emoney']=($value['emoney']+$value['smoney']*$value['samnum']);
                
                if(empty($value['thumb']))
                {
                    $res[$key]['thumb']="http://test.mdjkj.com/foodcase/Public/assets/img/bg_fix.png";
                }
                
            }
        }        
        
        $this->json_output($res);
   
    }
    
    /*
     * 获取单个众筹项目详情
     */
    public function get_one() {
        $id = I('get.id');
        
        $DB = M('fundings');
//        $map
        $res = $DB->find($id);
         
        $res['packagename']=$this->get_packagename_by_id($res['packageid']);
        $res['institution']=$this->get_institutionname_by_id($res['institutionid']);
        if(empty($res['thumb']))
        {
            $res['thumb']="http://test.mdjkj.com/foodcase/Public/assets/img/bg_fix.png";
            
        }
        $res['mid']=  $this->get_username_by_id($res['mid']);
        $res['category']=  $this->_get_name_by_sortid($res['first_categoryid']);
        $res['emoney']=($res['emoney']+$res['smoney']*$res['samnum']);
        
        $testreport = $this->get_testreport_by_id($res['testreportid']);
        if(!empty($res['sampleid']))
        {
            $sampleids = explode(",", $res['sampleid']);
            $sample_result = array();
            foreach ($sampleids as $key => $value) {
                $sample_result[]=  $this->get_sample_img_by_id($value);
            } 
        }
            
        
        
        $res['samples']=$sample_result;
        $res['testreport'] = $testreport['download'];
        $res['testreport_jpg'] = $testreport['download_jpg'];
        
        
        $this->json_output($res);
    }
    
    
    /*
     * 收藏项目
     */
    
    
    
    
    
    /*
     * 获取单个众筹的产品内容
     */
    public function get_one_packages() {
       $id = I('get.id');
        $DB = M('fundings');
        $finfo = $DB->find($id); 
        $packageids = explode(",", $finfo['packageid']);
        foreach ($packageids as $key => $value) {
            $res[]=  $this->get_package_by_id($value);
        }
        $this->json_output($res);
        
    }
    
    /*
     * 获取单个众筹的机构详情
     */
    public function get_one_institution() {
        $id = I('get.id');
        $DB = M('fundings');
//        $map
        $finfo = $DB->find($id);
        // exit("ddd");
        $res = $this->get_institution_by_id($finfo['institutionid']);
        if(!empty($res)){
            $this->json_output($res);
        }
        else
        {
            $no_msg = "";
          $this->json_output($no_msg);      

        }
        
    }
    
    
    /*
     * 获取单个项目的参与者列表
     */
    public function get_one_sponsor() {
        $id = I('get.id');
        
        $DB = M('fundings_sponsor');
        $map['fid']=$id;
        $map['status']=1;
        $res = $DB->where($map)->join("LEFT JOIN `fd_members` ON `fd_members`.id= `fd_fundings_sponsor`.mid")->order('`fd_fundings_sponsor`.paytime DESC')->select();
        
        foreach ($res as $key => $value) {
            $res[$key]['sponsor_name']=  $this->get_username_by_id($value['mid']);
            $res[$key]['mid']=  $this->get_username_by_id($value['mid']);

            
        }

        $this->json_output($res);
        
    }
    
    /*
     * 获取单个项目的评论列表
     */
    public function get_one_comment() {
        $fid = I('get.fid');
    }
    
    /*
     * 获取单个项目的检验结果
     */
    public function get_one_report() {
        $fid = I('get.fid');
    }
    
    /*
     * 获取指定的众测的样品信息
     */
    public function get_one_samples() {
        $fdid = I("get.id");
        $finfo = M("fundings")->find($fdid);
        
        $sampleids = explode(",", $finfo['sampleid']);
        
        $sample_result = array();
        foreach ($sampleids as $key => $value) {
            $sample_result[]=  $this->get_sample_img_by_id($value);
        }
        
        $this->json_output($sample_result);
    }
    
    /*
     * 众筹项目的发起功能
     * POST
     * 
     */
    public function publish() {
        //检查提交的数据
//        $pjname=I('post.pjname');
        $pjname=I('post.pdname')."检测";
        if(!empty($pjname)){
                $data['pjname']=$pjname;
        }else{
                $res['err']=1;
                $res['msg']="数据不能为空1";
                $res['content']=  $_POST;
                echo json_encode($res);
                exit();
        }


        $pdname=I('post.pdname');
        if(!empty($pdname)){
                $data['pdname']=$pdname;
        }else{
            $res['err']=1;
                $res['msg']="数据不能为空2";
                $res['content']=  $_POST;
                echo json_encode($res);
                exit();
        }

        $brand=I('post.brand');
        if(!empty($brand)){
                $data['brand']=$brand;
        }else{
//            $res['err']=1;
//                $res['msg']="数据不能为空3";
//                $res['content']=  $_POST;
//                echo json_encode($res);
//                exit();
        }
        
        $batch=I('post.batch');
        if(!empty($batch)){
                $data['batch']=$batch;
        }else{

        }
        
        $member_upload_imgs = I("post.sampleid");
        if(!empty($member_upload_imgs)){
                $data['member_upload_imgs']=$member_upload_imgs;
        }else{

        }
        
        $category=I('post.categoryid');
        if(!empty($category)){
                $data['first_categoryid']=$category;
        }else{
            $res['err']=1;
                $res['msg']="错误8，产品分类数据不能为空";
                $res['content']=  $_POST;
                echo json_encode($res);
                exit();
        }

        $package=I('post.packageid');
        if(!empty($package)){
                $data['packageid']=$package;
        }else{
            $res['err']=1;
                $res['msg']="数据不能为空4";
                $res['content']=  $_POST;
                echo json_encode($res);
                exit();
        }


        $institution=I('post.institutionid');
        if(!empty($institution)){
                $data['institutionid']=$institution;
        }else{
            $res['err']=1;
                $res['msg']="数据不能为空5";
                $res['content']=  $_POST;
                echo json_encode($res);
                exit();
        }


        $emoney=I('post.emoney');
        if(!empty($emoney)){
                $data['emoney']=$emoney;
        }else{
            $res['err']=1;
                $res['msg']="数据不能为空6";
                $res['content']=  $_POST;
                echo json_encode($res);
                exit();
        }
        
        /*
         * 备注内容
         */
        $content = I('post.content');
        $data['content']=$content;
        

        $mid=I('post.mid');
        if(!empty($mid)){
                $data['mid']=$mid;
        }else{
            $res['err']=1;
                $res['msg']="数据不能为空7";
                $res['content']=  $_POST;
                echo json_encode($res);
                exit();
        }
            
        $citycode = I('post.citycode');
        //获取citycode 对应的城市地址
        $data['citycode']=$citycode;
        
        $cityname = $this->get_cityname_by_citycode($citycode);
        $data['location'] = $cityname.I("post.address");


//        $data['sampleid']=I('post.sampleid');
        $data['ctime']=I('server.REQUEST_TIME');
        $data['btime']=I('server.REQUEST_TIME');
        $data['rawdata'] = serialize($_POST);
        
        $data['member_upload_imgs']=I('post.sampleid');
        
        $DB = M('fundings');
        
        $id = $DB->add($data);
        if($id)
        {
            $this->json_output($id);
        }
        else
        {
            $this->json_output(0);
        }
        
        
    }
    
    
    /*
     * 提交图片
     */
    public function publish_image_upload() {
        if(IS_POST)
        {
            $img_url='/Uploads'.'/'.$this->upload($_FILES['image']);
            
            $DB = M("fundings_image");
            $data['img_url']=$img_url;
            
            $id = $DB->add($data);
//            echo $id;
            
            
            $this->json_output($id);
        }
        else
        {
            $this->display();
//            $this->json_output($id);
        }
        
    }
    
    public function publish_image_upload_result() {
        $id = trim(I('get.id'));
        
        $res = M("fundings_image")->find($id);
        
        $this->show("<img src='"."http://".I('server.HTTP_HOST')."/".$res['img_url']."' />");
    }
    
    
    
    /*
     * 众筹项目获取套餐功能
     * Date：2015-12-07 
     * 新增： 通过产品分类id来过滤
     */
    public function get_packages() {
        /*
         * 过滤项目对应的产品的分类
         * 前期木有，全场通用
         */
        $DB_sorts = M("classify_package_relationship");
        $sortid = I("get.sortid");
        if(!empty($sortid))
        {
            $map_sort['classify_id'] = $sortid;
            
        }
        
        $res_sort = $DB_sorts->where($map_sort)->select();
        
        $tmp_sortid = array();
        foreach ($res_sort as $k => $v) {
            $tmp_sortid[]=$v['package_id'];
        }
//        print_r($tmp_sortid);
        
        
        
        $DB = M('fundings_package');
//        $map
        $res = $DB->where($map)->select();
        
        
        
        
        
        foreach ($res as $key => $value) {
            //增加项目的名字
            $res[$key]['items'] = explode(',', $value['items']);
            $res[$key]['content'] = htmlspecialchars_decode($value['content']);
            $res[$key]['information'] = htmlspecialchars_decode($value['information']);
            /*
             * 判断是否是预选值
             */
            if(in_array($value['id'], $tmp_sortid))
            {
                $res[$key]['ischecked']=1;
                $test[$key]=1;
//                echo $value['id']."ok<br/>";
            }
            else
            {
               $res[$key]['ischecked']=0; 
               $test[$key]=0;
//               echo $value['id']."<br/>";
            }
            
        }
        arsort($test,SORT_NUMERIC);
        
        foreach ($test as $key => $value) {
            $new_res[]=$res[$key];
        }
        
        
        
        
        $test_items = M('test_items');
        foreach ($new_res as $k => $v) {
            foreach ($v['items'] as $key => $value) {
                $new_res[$k]['items'][$key] = $test_items->where(array('id' => $value))->getField('name');
            }
        }
        
        //将数组内容转化为字符串
        foreach ($new_res as $k => $v) {
            $new_res[$k]['itemsname']=implode('，',$v['items']);
        }
        
        $this->json_output($new_res);
    }
    
    /*
     * 众筹项目获取检测机构功能
     */
    public function get_labs() {
        /*
         * 过滤项目对应的产品的分类
         * 前期木有，全场通用
         */
        $DB = M('institution');
//        $map
        $res = $DB->where($map)->select();
        $this->json_output($res);
    }
    
    /*
     * 获取产品分类
     */
    public function get_sorts() {
        $DB = M("food_classify");
        $map['pid']=0;  //获取大类
        
        $res = $DB->where($map)->select();
//        $this->assign('sorts', $res);
        
        $this->json_output($res);
    }
    
      
    
    /*
     * 众筹项目的支付功能
     * @param fdid 众筹的id，amount 要支付的金额，分为单位，mid 支付的用户id，;
     * 
     */
    public function payit() {
        $mid = I('get.mid');
        $password=I('get.password');
        $fdid = I('get.fdid');
        $type=I('get.type');
        $donatemoney = I('get.donate');
        $chk_usr = $this->chk_user($mid, $password);
        
        if($chk_usr)
        {
            if(!empty($type))
            {
                $founding_info = M('fundings')->find($fdid);
                
                /*
                 * 生成临时支付的订单
                 */
                $DB_sponsor = M('fundings_sponsor');
                $data_sponsor['mid']=$mid;
                $data_sponsor['paytype']=$type;
                $data_sponsor['paytime']=I('server.REQUEST_TIME');
                $data_sponsor['money']=$donatemoney;
                $data_sponsor['fid']=$fdid;
                $data_sponsor['status']=0;
                $data_sponsor['type']=0;
                
                $sponsor_id = $DB_sponsor->add($data_sponsor);
                
                /*
                * 判断订单是否过期
                */
//                $nowTimeStamp = I('server.REQUEST_TIME');
//                if($nowTimeStamp>$order_info['btime'])
//                {
//                    $res['err']=1;
//                    $res['msg']="订单已过期，请重新下订单";
//                    echo json_encode($res);
//                    exit();
//                }
            
                switch ($type) {
                    case 1:
                    //微信支付初始化
                        $app_Api = new \Com\WxPay\JsApi_pub();
                        $WxPayConf_pub = new \Com\WxPay\WxPayConf_pub();
                        //=========步骤2：使用统一支付接口，获取prepay_id============
                        //使用统一支付接口
                //        echo "3";
                        $unifiedOrder = new \Com\WxPay\UnifiedOrder_pub();
                //	echo $order['ordernum'];
                        //设置统一支付接口参数
                        //设置必填参数
                        //appid已填,商户无需重复填写
                        //mch_id已填,商户无需重复填写
                        //noncestr已填,商户无需重复填写
                        //spbill_create_ip已填,商户无需重复填写
                        //sign已填,商户无需重复填写
//                        $unifiedOrder->setParameter("openid","$openid");//商品描述
                        $body = $founding_info['pjname']."支付";
                        $unifiedOrder->setParameter("body",$body);//商品描述
                        //自定义订单号，此处仅作举例
                //	$timeStamp = I('server.REQUEST_TIME');
                //	$out_trade_no = WxPayConf_pub::APPID."$timeStamp";
                        $unifiedOrder->setParameter("out_trade_no",$sponsor_id."|".I('server.REQUEST_TIME'));//商户订单号 
//                        $unifiedOrder->setParameter("total_fee",$donatemoney);//总金额
//                        $unifiedOrder->setParameter("total_fee",$donatemoney);//总金额
                        if(!C('debug'))
                        {
//                            $unifiedOrder->setParameter("total_fee",1);//总金额
                            $unifiedOrder->setParameter("total_fee",($donatemoney));//总金额
                        }
                        else
                        {
                            $unifiedOrder->setParameter("total_fee",1);//总金额
                        }
//                        $unifiedOrder->setParameter("total_fee",1);//总金额,测试1分钱
                        $unifiedOrder->setParameter("notify_url",$WxPayConf_pub::NOTIFY_URL_APP);//通知地址 
                        $unifiedOrder->setParameter("trade_type","APP");//交易类型
                        //非必填参数，商户可根据实际情况选填
                        //$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号  
                        //$unifiedOrder->setParameter("device_info","XXXX");//设备号 
                        //$unifiedOrder->setParameter("attach","XXXX");//附加数据 
                        //$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
                        //$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间 
                        $unifiedOrder->setParameter("goods_tag",I('server.REQUEST_TIME'));//商品标记 
                        //$unifiedOrder->setParameter("openid","XXXX");//用户标识
                        $unifiedOrder->setParameter("product_id",$founding_info['id']);//商品ID
//                        print_r($unifiedOrder->createXml());
                        $prepay_id = $unifiedOrder->getPrepayId();
                        $data = $unifiedOrder->getParameters($prepay_id);
//                        echo json_encode($data);
                       
                        $this->json_output($data, "OK", "err");
//                        exit($prepay_id);
                        
                        //=========步骤3：使用jsapi调起支付============
//                        $app_Api->setPrepayId($prepay_id);
//
//                        $jsApiParameters = $app_Api->getParameters();
                        //echo $jsApiParameters;
//                        $this->json_output($jsApiParameters, 'OK', 'err');
//                        $this->assign('jsApiParameters', $jsApiParameters);

                        break;

                    default:
                        break;
                }
            }
            else
            {
                $res['err']=1;
                $res['msg']="订单支付方式有误";
                echo json_encode($res);
            }
   
        }
        else
        {
            $res['err']=1;
            $res['msg']="用户登录账户或密码有误".ACTION_NAME;
            echo json_encode($res);
        } 
    }
    

    /*
     * 众筹项目的跟投赞助功能
     */
    public function sponsorit() {
        
    }
    
    /*
     * 获取分享链接
     */
    public function get_share() {
        $mid = I('get.mid');
        $fdid=I('get.fdid');
        
        $fd_info = M('fundings')->find($fdid);
        
        //判断环境
        if(!C('debug'))
        {
            $site_domain = C('product_domain');
        }
        else
        {
            $site_domain = C('development_domain');
        }
        
        
        $share['title']=$fd_info['pjname']."俺不想千毒不侵，集合爱吃的货货，凑份子钱测有没毒，快支持我一下吧";
        $share['desc']=$fd_info['pjname']."俺不想千毒不侵，集合爱吃的货货，凑份子钱测有没毒，快支持我一下吧";
        $share['link']=$site_domain.U('M/Founding/detail_of_share',array('fdid'=>$fdid));
        $share['imgUrl']=$site_domain."/Public/assets/img/logo.png";

        $this->json_output($share, '成功', '失败');
    }
    
    /*
     * 通过id获取获取机构的名称
     */
    private function get_institutionname_by_id($id) {
        $res = M('institution')->find($id);
        return $res['name'];
    }
    
    /*
     * 获取样品的绑定图片
     */
    private function get_sample_img_by_id($sampleid) {
        $DB = M("fundings_image");
        $res = $DB->find($sampleid);
        
        return $res;
    }


    /*
     * 通过id获取套餐信息
     */
    private function get_packagename_by_id($id) {
//        $res = M('fundings_package')->find($id);
//        return $res['name'];
        
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
     * 通过id获取用户资料
     * 
     */
    private function get_username_by_id($mid) {
        $res = M('members')->find($mid);
        if(preg_match("/1[3458]{1}\d{9}$/",$res['username']))
        {
            $username = substr($res['username'], 0, -4)."****";
        }
        else
        {
            $username=$res['username'];
        }
        return $username;
    }

    
    /**
     date:   2015-10-10
     des:    检测套餐项目介绍相关知识接口
     */
    
    public function get_packages_information()
    {

        $id = I('get.id');
        $map['id'] = $id;

        $res = M('fundings_package')->field('name,content,information')->where($map)->find();

        if(!empty($res))
        {
            $res['err'] = 0;
            $res['msg'] = 'OK';
        }
        else
        {
            $res['err'] = 1;
            $res['msg'] = '获取数据失败';
        }
        echo json_encode($res);
    }
    
    /*
     * 获取检测报告
     */
    public function get_testreport_by_id($id) {
        $DB = M('testreport');
        $res = $DB->find($id);
        
        return $res;
    }
    
    //文件上传累
    private function upload($file)
    {
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     5242880 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->savePath  =      'fundings/show/'; // 设置附件上传目录
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
    
    
    
    
}
