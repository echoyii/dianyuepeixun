<?php
namespace M\Controller;
use Think\Controller;
class FoundingController extends CommonController {
    public function index(){
        $site_title = "众测天下";
        $this->assign('site_title', $site_title);
        $this->display();
    }
    
    /*
     * 项目详情页面
     */
    public function detail() {
        /*
         * Date: 2015-12-16
         * 增加一个判断是否是关注用户
         * 
         */
        
//        $member = $this->memberinfo;
//        print_r(session('openid'));
        
        $openid = session('openid');
        
        $DB_openid = M("members_subscribe_records");
        $map_openid['openid']=$openid;
        $map_openid['issubscribe']=1;
        $res_openid = $DB_openid->where($map_openid)->find();
//        print_r($DB_openid->getLastSql());
//        print_r($res_openid);
//        exit();
        if(empty($res_openid))
        {
            $this->assign('issubscribe', 0);
        }
        else
        {
            $this->assign('issubscribe', 1);
        }
//        print_r($res_openid);
//        exit();
        /*
         * Date: 2015-12-15
         * 增加一个判断是否是专题来源
         * 
         */
        $specialid = trim(I("get.specialid"));
        
        if(empty($specialid))
        {
            $specialid=0;
        }
        
        $this->assign("specialid", $specialid);
        /*
         * 增加一个判断是否是专题来源 end
         */
        
        $fdid = trim(I('get.fdid'));
        if(!empty($fdid))
        {
           $DB = M('fundings');
           $res = $DB->find($fdid);
           
           if(!empty($res))
           {
               //获取微信分享资料
               $this->get_weixin_jssdk_config();
               
               $res['membername']=  $this->get_name_by_mid($res['mid']);
               $res['institution'] = $this->get_name_by_institutionid($res['institutionid']);
               $res['packagename']=  $this->get_name_by_packageid($res['packageid']);
               $res['category']=  $this->get_name_by_sortid($res['first_categoryid']);
               $res['finish']=$res['finish']*0.01;
               $res['emoney']=($res['emoney']*0.01);
               
               $res['location'] = mb_substr($res['location'], 0,14,'utf-8')."...";
               
               
               
               
               //获取赞助列表
               $DB_sponsor = M('fundings_sponsor');
               $map['status']=1;
               $map['fid']=$fdid;
               
               $sponsor_res =$DB_sponsor->where($map)->join("LEFT JOIN `fd_members` ON `fd_fundings_sponsor`.mid = `fd_members`.id")->order('paytime DESC')->limit(10)->select();
               $sponsor_res_count =$DB_sponsor->where($map)->count();
//               $sponsor_res_count = count($sponsor_res);
               
//               print_r($DB_sponsor->getLastSql());
               
               
               foreach ($sponsor_res as $key => $value) {
                   $sponsor_res[$key]['username'] = $this->get_name_by_mid($value['mid']);
                   $sponsor_res[$key]['money'] = $value['money']*0.01; 
               }
               
               /*
                * 计算达成率，保留两位小数
                */
               $total_fee = $res['emoney']+$res['smoney']*$res['samnum']*0.01;
//               echo $res['emoney'];
//               echo "<br/>";
//               echo $res['smoney']*$res['samnum']*0.01;
//               echo "<br/>";
//               print_r($total_fee);
//               exit();
               $rate =  round($res['finish']*100/$total_fee,2);
               
               /*
                * 判断项目的状态，已经完成的，需要调出产品的检测报告
                */
               if($res['state']==3)
               {
                   $testreport = $this->_get_founding_report($res['testreportid']);
                   $res['testreport_pdf']=$testreport['download'];
                   $res['testreport_jpg']=$testreport['download_jpg'];
                   
                   $sample = $this->_get_founding_sample($res['sampleid']);
                   $res['sample_url']=$sample['img_url'];
//                   print_r($sample);
               }
               
               /*
                * 判断分享的配图是否存在，不存在用默认的来代替
                */
//               $founding_share_img = "http://".I('server.HTTP_HOST')."/".$res['thumb'];
               
//               
//               echo $res['finish'];
//               echo "<br/>";
//               echo $total_fee;
//               echo "<br/>";
//               print_r($rate);
               
               $this->assign('total_fee', $total_fee);
               $this->assign('rate', $rate);
               $this->assign('fd_info', $res);
               
               $this->assign('sponsors', $sponsor_res);
               $this->assign('sponsors_count', $sponsor_res_count);
               $this->assign('fdid', $fdid);
               $this->display(); 
           }
           else
           {
               redirect(U('M/Index/index'));
           }


            
        }
        else
        {
            redirect(U('M/Index/index'));
            
        }
        
    }
    
    
    public function detail_of_share() {
        /*
         * Date: 2015-12-16
         * 增加一个判断是否是关注用户
         * 
         */
        
        $openid = session('openid');
        
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
        
        /*
         * Date: 2015-12-15
         * 增加一个判断是否是专题来源
         * 
         */
        $specialid = trim(I("get.specialid"));
        
        if(empty($specialid))
        {
            $specialid=0;
        }
        
        $this->assign("specialid", $specialid);
        /*
         * 增加一个判断是否是专题来源 end
         */
        
        $fdid = trim(I('get.fdid'));
        if(!empty($fdid))
        {
           $DB = M('fundings');
           $res = $DB->find($fdid);
           
           if(!empty($res))
           {
               //获取微信分享资料
               $this->get_weixin_jssdk_config();
               
               $res['membername']=  $this->get_name_by_mid($res['mid']);
               $res['institution'] = $this->get_name_by_institutionid($res['institutionid']);
               $res['packagename']=  $this->get_name_by_packageid($res['packageid']);
               $res['category']=  $this->get_name_by_sortid($res['first_categoryid']);
               $res['finish']=$res['finish']*0.01;
               $res['emoney']=($res['emoney']*0.01);
               
               $res['location'] = mb_substr($res['location'], 0,14,'utf-8')."...";
               
               
               //获取赞助列表
               $DB_sponsor = M('fundings_sponsor');
               $map['status']=1;
               $map['fid']=$fdid;
               
               $sponsor_res =$DB_sponsor->where($map)->join("LEFT JOIN `fd_members` ON `fd_fundings_sponsor`.mid = `fd_members`.id")->order('paytime DESC')->limit(10)->select();
               $sponsor_res_count =$DB_sponsor->where($map)->count();
//               $sponsor_res_count = count($sponsor_res);
               
//               print_r($DB_sponsor->getLastSql());
               
               
               foreach ($sponsor_res as $key => $value) {
                   $sponsor_res[$key]['username'] = $this->get_name_by_mid($value['mid']);
                   $sponsor_res[$key]['money'] = $value['money']*0.01; 
               }
               
               /*
                * 计算达成率，保留两位小数
                */
               $total_fee = $res['emoney']+$res['smoney']*$res['samnum']*0.01;
//               echo $res['emoney'];
//               echo "<br/>";
//               echo $res['smoney']*$res['samnum']*0.01;
//               echo "<br/>";
//               print_r($total_fee);
//               exit();
               $rate =  round($res['finish']*100/$total_fee,2);
               
               /*
                * 判断项目的状态，已经完成的，需要调出产品的检测报告
                */
               if($res['state']==3)
               {
                   $testreport = $this->_get_founding_report($res['testreportid']);
                   $res['testreport_pdf']=$testreport['download'];
                   $res['testreport_jpg']=$testreport['download_jpg'];
                   
                   $sample = $this->_get_founding_sample($res['sampleid']);
                   $res['sample_url']=$sample['img_url'];
//                   print_r($sample);
               }
               
               /*
                * 判断分享的配图是否存在，不存在用默认的来代替
                */
//               $founding_share_img = "http://".I('server.HTTP_HOST')."/".$res['thumb'];
               
//               
//               echo $res['finish'];
//               echo "<br/>";
//               echo $total_fee;
//               echo "<br/>";
//               print_r($rate);
               
               $this->assign('total_fee', $total_fee);
               $this->assign('rate', $rate);
               $this->assign('fd_info', $res);
               
               $this->assign('sponsors', $sponsor_res);
               $this->assign('sponsors_count', $sponsor_res_count);
               $this->assign('fdid', $fdid);
               $this->display(); 
           }
           else
           {
               redirect(U('M/Index/index'));
           }


            
        }
        else
        {
            redirect(U('M/Index/index'));
            
        }
        
    }
    
    /*
     * 获取更多的项目赞助者
     */
    public function get_sponor_by_page() {
        $fdid = trim(I('get.fdid'));
        $pagecount = I('get.pagecount');
        if(empty($pagecount))
        {
            $pagecount=0;
        }
        
        $startcount = $pagecount*10;
//        $DB = M('fundings');
//        $res = $DB->find($fdid);
        
        $DB_sponsor = M('fundings_sponsor');
        $map['status']=1;
        $map['fid']=$fdid;

        $sponsor_res =$DB_sponsor->where($map)->join("LEFT JOIN `fd_members` ON `fd_fundings_sponsor`.mid = `fd_members`.id")->order('paytime DESC')->limit($startcount,10)->select();
//        exit($DB_sponsor->getLastSql());
        
        if(!empty($sponsor_res))
        {
            foreach ($sponsor_res as $key => $value) {
                $sponsor_res[$key]['paytime_format']=date("Y-m-d H:i",$value['paytime']);
                $sponsor_res[$key]['money']=$value['money']/100;
            }
            $result['res']=$sponsor_res;
            $result['err']=0;
            $result['msg']="成功获取数据";
        }
        else
        {
            $result['err']=1;
            $result['msg']="暂无更多数据";
        }
        
        
        echo json_encode($result);
    }
    
    /*
     * 获取项目详情下的检测内容危害
     * 
     */
    public function founding_package_detail() {
        $fdid = I('get.fdid');
        $DB = M("fundings");
        
        $res = $DB->find($fdid);
        
        $package_arr = explode(",", $res['packageid']);
//        print_r($package_arr);
        foreach ($package_arr as $key => $value) {
            $resuslt[$key]=  $this->get_package_by_id($value);
        }
        
        $this->assign("packages", $resuslt);
        $this->display();
    }
    
    /*
     * 获取指定的产品下的项目列表情况
     */
    public function product_foundings() {
        $pid = I('get.pid');
        
        $DB= M('fundings');
        
        $map['pid']=$pid;
        $res = $DB->where($map)->select();
        foreach ($res as $key => $value) {
            $res[$key]['institution'] = $this->get_name_by_institutionid($value['institutionid']);
        }
        $this->assign('fundings',$res);
        $this->display();
        
    }
    
    
    
    /*
     * Date: 2015-10-19
     * Des: 发起众筹
     */
    
    public function publish() {
        
        if(IS_POST)
        {
            //提交数据
            $pdname = I('post.pdname');
            $brand = I('post.brand');
            $batch = I('post.batch');
            $citycode = I('post.citycode');
            $packageid = I('post.packageid');
            $institutionid = I('post.institutionid');
            $first_categoryid = I('post.first_categoryid');
            $emoney = I('post.emoney');
            $fromtype = 0;
            $content = I('post.content');
            
            $memberinfo = $this->memberinfo;
            
            
            $data['pjname']=$pdname."检测";
            $data['pdname']=$pdname;
            $data['brand']=$brand;
            $data['citycode']=$citycode;
            
             /*
             * Date:2015-11-30
             * 增加的字段
             */
            $location_detail = I('post.location_detail');
            
            $cityname = $this->get_cityname_by_citycode($citycode);
            
            $data['location'] = $cityname.$location_detail;
            $data['first_categoryid']=$first_categoryid;
            $data['packageid']=$packageid;
            $data['institutionid']=$institutionid;
            $data['ctime']=I('server.REQUEST_TIME');
            $data['btime']=I('server.REQUEST_TIME');
            $data['content']=$content;
            $data['emoney']=$emoney*100;
            $data['fromtype']=$fromtype;
            $data['mid']=$memberinfo['id'];
            $data['batch']=$batch;
            $data['rawdata'] = serialize($_POST);
            
            //存入微信接口的图片数据
            $wx_media_ids = I('post.wx_media_ids');
            /*
             * 根据media id 来下载图片到本地数据库
             */
            if(!empty($wx_media_ids))
            {
                $accessToken = $this->accessToken;
                    
                $wx_media_arr = json_decode(htmlspecialchars_decode($wx_media_ids),true);
                $sampleids = "";
                
                foreach ($wx_media_arr as $key => $value) {
                    $filename = date('YmdHis').$this->random_code().'.jpg';
                    $targetName = "./Uploads/fundings/wx_media/".$filename;
                    $ch = curl_init("http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$accessToken&media_id=$value");
                    $fp = fopen($targetName, 'wb');
                    curl_setopt($ch, CURLOPT_FILE, $fp);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_exec($ch);
                    curl_close($ch);
                    fclose($fp);
                    
                    /*
                     * 插入记录
                     * 1. 插入到 image 表 并得到返回的id写入 sampleid
                     */
                   $id[$key] = $this->insert_image(substr($targetName, 1));
                   $sampleids .=$id[$key].",";
                    
                    
                }
            
                //用户前端上传的图片保存位置
                $data['member_upload_imgs'] = substr($sampleids, 0,-1);
                
                $data['wx_media_ids']=serialize(I('post.wx_media_ids'));
            }
            
            
            
           
            
            /*
             * Date: 2011-11-16
             * 临时调整为可以发起就为审核中
             */
//            $data['state']=1;

            
            $DB = M('fundings');
            $id = $DB->add($data);

            if($id)
            {
                $this->success('添加成功');
                //跳转到支付页面
                redirect(U('Founding/publish_pay_money',array('fdid'=>$id)));
            }
            else
            {
//                print_r($DB->getLastSql());
                $this->error('添加失败');
            }
            
            
        }
        else
        {
            /*
             * 加载选择的套餐信息
             */
            $from = I('get.from');
            if(empty($from))
            {
                $from =0;
                
            }
            $this->assign('from', $from);
            
            $packages = M('fundings_package')->select();
            foreach ($packages as $key => $value) {
                $packages[$key]['price'] = $value['price']*0.01;
            }
            $this->assign('packages', $packages);
            /*
             * 加载检测机构信息
             */
            $institutions = M('institution')->select();
            $this->assign('institutions', $institutions);
            
            
            $this->display();
        }
    }
    
    /*
     * 插入图片到fd_fundings_image表
     */
    private function insert_image($img_url)
    {
        $DB = M("fundings_image");
        $data['img_url']=$img_url;
        
        $id = $DB->add($data);
        
        return $id;
        
    }


    /*
     * 检测项目选择
     */
    public function publish_package_list() {
        /*
         * 过滤项目对应的产品的分类
         * 前期木有，全场通用
         */
        $DB = M('fundings_package');
//        $map
        $res = $DB->where($map)->select();

        foreach ($res as $key => $value) {
            //增加项目的名字
            $res[$key]['price']=$value['price']*0.01;
        }
        
        
        
       
        
        $this->assign('result', $res);
//        print_r($res);
        
        $this->display();
    }
    
    /*
     * publish_package_detail
     */
    public function publish_package_detail() {
        $id = I('get.id');
        $DB = M('fundings_package');
        
        $res = $DB->find($id);
        
        $this->assign('package', $res);
        $this->display();
        
    }
    
    /*
     * 检测机构选择
     */
    public function publish_institution_list() {
         /*
         * 过滤项目对应的产品的分类
         * 前期木有，全场通用
         */
        $DB = M('institution');
//        $map
        $res = $DB->where($map)->select();
        
        $this->assign('result', $res);
        
        $this->display();
    }
    
    
    /*
     * 产品分类选择
     */
    public function publish_sorts_list() {
        $DB = M("food_classify");
        $map['pid']=0;  //获取大类
        
        $res = $DB->where($map)->select();
        /*
         * 获取该分类下的管理检测内容
         */
        foreach ($res as $key => $value) {
            $res[$key]['packageids']= $this->_get_package_relationship_by_sortid($value['id']);
            $res[$key]['packagename']=$this->get_name_by_packageid($res[$key]['packageids']);
            $res[$key]['totalprice']=  $this->get_total_price_by_packageid($res[$key]['packageids']);
        }
        
        $this->assign('sorts', $res);
        
//        print_r($res);
//        exit();
        $this->display();
    }
    
    public function publish_pay_money() {
         $fdid=I('get.fdid');
         
         $this->assign('fdid', $fdid);
         $this->display();
    }
    
    
    public function publish_money_confirm() {
        $fdid=I('post.fdid');
        $donate = I('post.donate_money');
        
        /*
         * 找出当前用户的openid
         */
        $member = session('member');
        $openid = $member['wxopenid'];
        
        
        /*
         * 找出项目的资料
         */
        $fd_info = M("fundings")->find($fdid);
        
        /*
        * 生成临时支付的订单
        */
       $DB_sponsor = M('fundings_sponsor');
       $data_sponsor['mid']=$member['id'];
       $data_sponsor['paytype']=1;
       $data_sponsor['paytime']=I('server.REQUEST_TIME');
       $data_sponsor['money']=$donate*100;
       $data_sponsor['fid']=$fdid;
       $data_sponsor['status']=0;
       $data_sponsor['type']=0;

       $sponsor_id = $DB_sponsor->add($data_sponsor);
        
        
        //微信支付初始化
//        $wechat = new \Com\WechatAuth(C('appid'),C('appsecret'));
//        $test = new \Org\Net\Http();
        $jsApi = new \Com\WxPayPubHelper\JsApi_pub();
        $WxPayConf_pub = new \Com\WxPayPubHelper\WxPayConf_pub();
//        if (!isset($_GET['code']))
//	{
//		//触发微信返回code码
//		$url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL);
//		Header("Location: $url"); 
//	}else
//	{
//		//获取code码，以获取openid
//	    $code = $_GET['code'];
//		$jsApi->setCode($code);
//		$openid = $jsApi->getOpenId();
//	}
        
        
        //=========步骤2：使用统一支付接口，获取prepay_id============
	//使用统一支付接口
//        echo "3";
	$unifiedOrder = new \Com\WxPayPubHelper\UnifiedOrder_pub();
//	echo $order['ordernum'];
        //设置统一支付接口参数
	//设置必填参数
	//appid已填,商户无需重复填写
	//mch_id已填,商户无需重复填写
	//noncestr已填,商户无需重复填写
	//spbill_create_ip已填,商户无需重复填写
	//sign已填,商户无需重复填写
	$unifiedOrder->setParameter("openid","$openid");//商品描述
	$unifiedOrder->setParameter("body",$fd_info['pjname']);//商品描述
	//自定义订单号，此处仅作举例
//	$timeStamp = I('server.REQUEST_TIME');
//	$out_trade_no = WxPayConf_pub::APPID."$timeStamp";
	$unifiedOrder->setParameter("out_trade_no",$sponsor_id."|".I('server.REQUEST_TIME'));//商户订单号 
        //判断环境
        if(!C('debug'))
        {
//            $unifiedOrder->setParameter("total_fee",1);//总金额
            $unifiedOrder->setParameter("total_fee",($donate)*100);//总金额
        }
        else
        {
            $unifiedOrder->setParameter("total_fee",1);//总金额
        }
	
//        $unifiedOrder->setParameter("total_fee",1);//总金额
	$unifiedOrder->setParameter("notify_url",$WxPayConf_pub::NOTIFY_URL);//通知地址 
	$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
	//非必填参数，商户可根据实际情况选填
	//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号  
	//$unifiedOrder->setParameter("device_info","XXXX");//设备号 
	//$unifiedOrder->setParameter("attach","XXXX");//附加数据 
	//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
	//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间 
	$unifiedOrder->setParameter("goods_tag",I('server.REQUEST_TIME'));//商品标记 
	//$unifiedOrder->setParameter("openid","XXXX");//用户标识
	$unifiedOrder->setParameter("product_id",$fd_info['id']);//商品ID

	$prepay_id = $unifiedOrder->getPrepayId();
	//=========步骤3：使用jsapi调起支付============
	$jsApi->setPrepayId($prepay_id);

	$jsApiParameters = $jsApi->getParameters();
	//echo $jsApiParameters;
        
        $this->assign('jsApiParameters', $jsApiParameters);
        $this->assign('fd_info', $fd_info);
        $this->assign('donatemoney', $donate);
        $this->display();
    }
    
    
    /*
     * 给号钱之后要分享
     */
    public function publish_ok_to_share() {
        $fdid = I('get.fdid');
        if(empty($fdid))
        {
            redirect(U('M/Index/index'));
        }
        /*
         * 找出项目的资料
         */
        $fd_info = M("fundings")->find($fdid);
        if(empty($fd_info))
        {
            redirect(U('M/Index/index'));
        }
        
        
        $this->assign('fd_info', $fd_info);
        $this->display();
    }
    
    
    
    
    /*
     * 列举项目列表
     */
    public function lists() {
        
        //获取项目的列表信息
        $state = I('get.state');
        switch ($state) {
            case 1:
                $this->assign("sub_title",'众筹检测项目' );

                break;
            case 2:
                $this->assign("sub_title",'抽样中的项目');

                break;

            default:
                $this->assign("sub_title",'最新报告');
                break;
        }
        $DB = M('fundings');
        
        $map['state']=$state;
        
        $res = $DB->where($map)->select();
        foreach ($res as $key => $value) {
            $res[$key]['institution'] = $this->get_name_by_institutionid($value['institutionid']);
            $res[$key]['packagename']=  $this->get_name_by_packageid($value['packageid']);
            if(mb_strlen($res[$key]['packagename'],"utf-8")>10)
            {
                $res[$key]['packagename'] = mb_substr($res[$key]['packagename'], 0,8,'utf-8')."...";
                
            }
            $res[$key]['rate'] = round($value['finish']*100/($value['emoney']+$value['smoney']*$value['samnum']),0);
            
            $res[$key]['show_mark_icon']=  $this->_get_fouding_list_mark(0,$value['result'],$value['state']);
        }
//        print_r($res);
//        exit();
        $this->assign('fundings', $res);
        $this->assign("state", $state);
        $this->display();
    }
    
    
    /*
     * 项目确认支付页面
     */
    public function donate_money_confirm() {
        /*
         * Date: 2015-12-15
         * 增加一个判断是否是专题来源
         * 
         */
//        print_r($_POST);
//        exit();
        $specialid = trim(I("post.specialid"));
        
        if(empty($specialid))
        {
            $specialid=0;
        }
        
        $this->assign("specialid", $specialid);
        /*
         * 增加一个判断是否是专题来源 end
         */
        
        $fdid=I('post.fdid');
        $donate = I('post.donate_money');
        
        $mid = $this->memberinfo['id']; 
        
        /*
        * 生成临时支付的订单
        */
       $DB_sponsor = M('fundings_sponsor');
       $data_sponsor['mid']=$mid;
       $data_sponsor['paytype']=1; //1 代表微信支付， 2支付宝，3 银联
       $data_sponsor['paytime']=I('server.REQUEST_TIME');
       $data_sponsor['money']=$donate*100;
       $data_sponsor['fid']=$fdid;
       $data_sponsor['status']=0;
       $data_sponsor['type']=0;

       $sponsor_id = $DB_sponsor->add($data_sponsor);
        
        /*
         * 找出当前用户的openid
         */
        $member = session('member');
        $openid = $member['wxopenid'];
        
        
        /*
         * 找出项目的资料
         */
        $fd_info = M("fundings")->find($fdid);
        
        
        //微信支付初始化
//        $wechat = new \Com\WechatAuth(C('appid'),C('appsecret'));
//        $test = new \Org\Net\Http();
        $jsApi = new \Com\WxPayPubHelper\JsApi_pub();
        $WxPayConf_pub = new \Com\WxPayPubHelper\WxPayConf_pub();
//        if (!isset($_GET['code']))
//	{
//		//触发微信返回code码
//		$url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL);
//		Header("Location: $url"); 
//	}else
//	{
//		//获取code码，以获取openid
//	    $code = $_GET['code'];
//		$jsApi->setCode($code);
//		$openid = $jsApi->getOpenId();
//	}
        
        
        //=========步骤2：使用统一支付接口，获取prepay_id============
	//使用统一支付接口
//        echo "3";
	$unifiedOrder = new \Com\WxPayPubHelper\UnifiedOrder_pub();
//	echo $order['ordernum'];
        //设置统一支付接口参数
	//设置必填参数
	//appid已填,商户无需重复填写
	//mch_id已填,商户无需重复填写
	//noncestr已填,商户无需重复填写
	//spbill_create_ip已填,商户无需重复填写
	//sign已填,商户无需重复填写
	$unifiedOrder->setParameter("openid","$openid");//商品描述
	$unifiedOrder->setParameter("body",$fd_info['pjname']);//商品描述
	//自定义订单号，此处仅作举例
//	$timeStamp = I('server.REQUEST_TIME');
//	$out_trade_no = WxPayConf_pub::APPID."$timeStamp";
	$unifiedOrder->setParameter("out_trade_no",$sponsor_id."|".I('server.REQUEST_TIME'));//商户订单号 
//	$unifiedOrder->setParameter("total_fee",($donate)*100);//总金额
//        $unifiedOrder->setParameter("total_fee",1);//总金额
        //判断环境
        if(!C('debug'))
        {
            if($openid=="ozaQJv7aFKV0yIy4jybu9FqEo6XY")
            {
                $unifiedOrder->setParameter("total_fee",1);//总金额
            }
            else
            {
//                $unifiedOrder->setParameter("total_fee",1);//总金额
                $unifiedOrder->setParameter("total_fee",($donate)*100);//总金额
            }
            
        }
        else
        {
            $unifiedOrder->setParameter("total_fee",1);//总金额
        }
        
        
	$unifiedOrder->setParameter("notify_url",$WxPayConf_pub::NOTIFY_URL);//通知地址 
	$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
	//非必填参数，商户可根据实际情况选填
	//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号  
	//$unifiedOrder->setParameter("device_info","XXXX");//设备号 
	$unifiedOrder->setParameter("attach","$sponsor_id");//附加数据 
	//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
	//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间 
	$unifiedOrder->setParameter("goods_tag",I('server.REQUEST_TIME'));//商品标记 
	//$unifiedOrder->setParameter("openid","XXXX");//用户标识
	$unifiedOrder->setParameter("product_id",$fd_info['id']);//商品ID

	$prepay_id = $unifiedOrder->getPrepayId();
	//=========步骤3：使用jsapi调起支付============
	$jsApi->setPrepayId($prepay_id);

	$jsApiParameters = $jsApi->getParameters();
	//echo $jsApiParameters;
        
        $this->assign('jsApiParameters', $jsApiParameters);
        $this->assign('fd_info', $fd_info);
        $this->assign('donatemoney', $donate);
        if($specialid>0)
        {
            $this->display("donate_money_confirm_for_special");
        }
        else
        {
            $this->display();
        }
        
    }
    
    /*
     * 项目跟投选择金额
     */
    public function donate_pay_money() {
        /*
         * Date: 2015-12-15
         * 增加一个判断是否是专题来源
         * 
         */
        $specialid = trim(I("get.specialid"));
        
        if(empty($specialid))
        {
            $specialid=0;
        }
        
        $this->assign("specialid", $specialid);
        /*
         * 增加一个判断是否是专题来源 end
         */
        
        $fdid=I('get.fdid');
        $this->assign("fdid", $fdid);
        
        $this->display();
    }
    
    /*
     * 给好钱之后要分享
     */
    public function donate_ok_to_share() {
        $fdid = I('get.fdid');
        if(empty($fdid))
        {
            redirect(U('M/Index/index'));
        }
        /*
         * 找出项目的资料
         */
        $fd_info = M("fundings")->find($fdid);
        if(empty($fd_info))
        {
            redirect(U('M/Index/index'));
        }
        
        
        $this->assign('fd_info', $fd_info);
        $this->display();
    }
    
    /*
     * 专题的给钱后分享页面
     */
    public function donate_ok_to_share_for_special() {
        /*
         * Date: 2015-12-15
         * 增加一个判断是否是专题来源
         * 
         */
        $specialid = trim(I("get.specialid"));
        
        if(empty($specialid))
        {
            $specialid=0;
        }
        
        $this->assign("specialid", $specialid);
        /*
         * 增加一个判断是否是专题来源 end
         */
        
        $fdid = I('get.fdid');
        if(empty($fdid))
        {
            redirect(U('M/Index/index'));
        }
        /*
         * 找出项目的资料
         */
        $fd_info = M("fundings")->find($fdid);
        if(empty($fd_info))
        {
            redirect(U('M/Index/index'));
        }
        
        
        $this->assign('fd_info', $fd_info);
        $this->display();
    }
    
    
    /*
     * 支付确认页面
     */
    
    public function wx_app_pay_notify() {
        
       $notify = new \Com\WxPay\Notify_pub();
       //使用通用通知接口
//	$notify = new Notify_pub();

	//存储微信的回调
	$xml = $GLOBALS['HTTP_RAW_POST_DATA'];	
	$notify->saveData($xml);
        
        //存入本地
        $data['ordernum']=$notify->data['out_trade_no'];
        $data['return_code'] = $notify->data["return_code"];
        $data['content']=json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA));
        $data['ctime']= I('server.REQUEST_TIME');
        M('wx_pay_log')->add($data);
	
	//验证签名，并回应微信。
	//对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
	//微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
	//尽可能提高通知的成功率，但微信不保证通知最终能成功。
	if($notify->checkSign() == FALSE){
		$notify->setReturnParameter("return_code","FAIL");//返回状态码
		$notify->setReturnParameter("return_msg","签名失败");//返回信息
	}else{
		$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
	}
	$returnXml = $notify->returnXml();
	echo $returnXml;
	
	//==商户根据实际情况设置相应的处理流程，此处仅作举例=======
	
//	//以log文件形式记录回调信息
//	$log_ = new Log_();
//	$log_name="./notify_url.log";//log文件路径
//	$log_->log_result($log_name,"【接收到的notify通知】:\n".$xml."\n");

	if($notify->checkSign() == TRUE)
	{
		if ($notify->data["return_code"] == "FAIL") {
			//此处应该更新一下订单状态，商户自行增删操作
//			$log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
		}
		elseif($notify->data["result_code"] == "FAIL"){
			//此处应该更新一下订单状态，商户自行增删操作
//			$log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
		}
		else{
                    //此处应该更新一下订单状态，商户自行增删操作
                    
//                        $ordernum = $notify->data['out_trade_no'];
//                        $ordernum = substr($ordernum, 0,19);
                        $DB = M('fundings_sponsor');
                        $orderid = $notify->data['out_trade_no'];
                        $orderid_array = explode('|', $orderid);
                        array_pop($orderid_array); //去掉最后的时间戳
                        $map_order['id']=array('in',$orderid_array);
                        $results = $DB->where($map_order)->select();
                        if(!empty($results))
                        {
                            foreach ($results as $key => $value) {
                                /*
                                 * 判断该订单的状态，如果是已经为1的就不用处理了
                                 * 
                                 */
//                                $order = $DB->find($value['id']);
                                if($value['status']!=1)
                                {
//                                    $id = $value['id'];
                                    $DB->id = $value['id'];
                                    $DB->status=1;
                                    $DB->paytype = 1; //支付方式，0 未知（包含当面支付），1是微信，2 支付宝，3银联
                                    $DB->save();
                                    
                                    
                                    /*
                                     * 更新这个记录到众筹的项目的总金额去
                                     */
                                    $this->_founding_finish_money_increase($value['fid'], $value['money']);
                                    
                                    /*
                                     * 判断founding 是否满标，满的话，就改状态
                                     */
                                    $this->_chk_if_donate_ok($value['fid']);
 
                                }
                                
                                
                                
                            }
                        }

	}
    }
    }
    
    /*
     * 微信支付确认页面
     */
    public function wxpay_notify() {
        
       $notify = new \Com\WxPayPubHelper\Notify_pub();
       //使用通用通知接口
//	$notify = new Notify_pub();

	//存储微信的回调
	$xml = $GLOBALS['HTTP_RAW_POST_DATA'];	
	$notify->saveData($xml);
        
        //存入本地
        $data['ordernum']=$notify->data['out_trade_no'];
        $data['return_code'] = $notify->data["return_code"];
        $data['content']=json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA));
        $data['ctime']= I('server.REQUEST_TIME');
        M('wx_pay_log')->add($data);
	
	//验证签名，并回应微信。
	//对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
	//微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
	//尽可能提高通知的成功率，但微信不保证通知最终能成功。
	if($notify->checkSign() == FALSE){
		$notify->setReturnParameter("return_code","FAIL");//返回状态码
		$notify->setReturnParameter("return_msg","签名失败");//返回信息
	}else{
		$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
	}
	$returnXml = $notify->returnXml();
	echo $returnXml;
	
	//==商户根据实际情况设置相应的处理流程，此处仅作举例=======
	
//	//以log文件形式记录回调信息
//	$log_ = new Log_();
//	$log_name="./notify_url.log";//log文件路径
//	$log_->log_result($log_name,"【接收到的notify通知】:\n".$xml."\n");

	if($notify->checkSign() == TRUE)
	{
		if ($notify->data["return_code"] == "FAIL") {
			//此处应该更新一下订单状态，商户自行增删操作
//			$log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
		}
		elseif($notify->data["result_code"] == "FAIL"){
			//此处应该更新一下订单状态，商户自行增删操作
//			$log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
		}
		else{
            //此处应该更新一下订单状态，商户自行增删操作
            
    //                        $ordernum = $notify->data['out_trade_no'];
    //                        $ordernum = substr($ordernum, 0,19);
                $DB = M('fundings_sponsor');
                $orderid = $notify->data['out_trade_no'];
                $orderid_array = explode('|', $orderid);
                array_pop($orderid_array); //去掉最后的时间戳
                $map_order['id']=array('in',$orderid_array);
                $results = $DB->where($map_order)->select();
                if(!empty($results))
                {
                    foreach ($results as $key => $value) {
                        /*
                         * 判断该订单的状态，如果是已经为1的就不用处理了
                         * 
                         */
    //                                $order = $DB->find($value['id']);
                        if($value['status']!=1)
                        {
    //                                    $id = $value['id'];
                            $DB->id = $value['id'];
                            $DB->status=1;
                            $DB->paytype = 1; //支付方式，0 未知（包含当面支付），1是微信，2 支付宝，3银联
                            $DB->save();
                            
                            /*
                             * 更新这个记录到众筹的项目的总金额去
                             */
                            $this->_founding_finish_money_increase($value['fid'], $value['money']);
                            
                            /*
                             * 判断founding 是否满标，满的话，就改状态
                             */
                            $this->_chk_if_donate_ok($value['fid']);

                        }
                        
                    }
                }
                        

	}
    }
    }
    
    
    /*
     * 检测机构查看
     */
    public function publish_institution_detail() {
        $id = I('get.id');
        $res  = M("institution")->find($id);
        
        $this->assign("result", $res);
        $this->display();
    }
    
    
    /*
     * 不安全的食品列表
     */
    public function unsafe() {
        
//        $DB_search_tags = M('search_tags');
//        $maps['type'] = 1;
//        $tag_list = $DB_search_tags->where($maps)->order("rank DESC")->select();
//        $this->assign('tag_list',$tag_list);

        $DB = M('fundings');
        $map['state']=3;
        $map['result']=2;
        
        $res = $DB->where($map)->order("state DESC,result DESC")->select();
        
//        print_r($res);
//        exit();
        foreach ($res as $key => $value) {
            $res[$key]['name']= $value['pjname'];
            $res[$key]['isurl']= 0;
            $res[$key]['url']= "";
            $res[$key]['qualified']= 0;
            $res[$key]['show_mark_icon']=  $this->_get_fouding_list_mark(0,$value['result'],$value['state']);
            $res[$key]['location']= $value['location'];
            $res[$key]['testing_date']= date("Y-m-d",$value['ctime']);
            $res[$key]['batch_number']= $value['batch'];
            
        }
        
        $count_res = count($res);
        
        //找出关键字相关的记录
            $res_thirdparty = $this->get_thirdparty_product_by_name_for_unsafe_index(0,10);
            
            if(!empty($res_thirdparty))
            {
                foreach ($res_thirdparty as $key => $value) {
                    
                    $res[$count_res+$key]['pinfo']=  $this->get_third_party_info_by_id($value['id']);
                    
                    $res[$count_res+$key]['name'] = $res[$count_res+$key]['pinfo']['product_name'];
                    $res[$count_res+$key]['id'] = 0;
                    $res[$count_res+$key]['isurl']= 1;
                    $res[$count_res+$key]['url']= C('product_domain').U('M/Founding/show_thirdparty_data',array('id'=>$res[$count_res+$key]['pinfo']['id']));
                    $res[$count_res+$key]['qualified']= (int)$res[$count_res+$key]['pinfo']['qualified'];
                    $res[$count_res+$key]['show_mark_icon']=  $this->_get_fouding_list_mark(1,$res[$count_res+$key]['pinfo']['qualified'],3);
                    $res[$count_res+$key]['location']= $res[$count_res+$key]['pinfo']['province'];
                    $res[$count_res+$key]['testing_date']= $res[$count_res+$key]['pinfo']['notification_number'];
                    $res[$count_res+$key]['batch_number']= $res[$count_res+$key]['pinfo']['cdate'];
                }
            }
            $this->assign('results', $res);
            
            
        $this->display();   
    }
    
    /*
     * 曝光台产品
     */
    public function unsafe_list() {
        $keywords = I('get.keywords');
        
        $DB = M('fundings');
        $map['pdname']=array('like',"%$keywords%");
        $map['state']=3;
        $map['result']=2;
        
        $res = $DB->where($map)->order("state DESC,result DESC")->select();
        
//        print_r($res);
//        exit();
        foreach ($res as $key => $value) {
            $res[$key]['name']= $value['pjname'];
            $res[$key]['isurl']= 0;
            $res[$key]['url']= "";
            $res[$key]['qualified']= 0;
            $res[$key]['show_mark_icon']=  $this->_get_fouding_list_mark(0,$value['result'],$value['state']);
            $res[$key]['location']= $value['location'];
            $res[$key]['testing_date']= date("Y-m-d",$value['ctime']);
            $res[$key]['batch_number']= $value['batch'];
            
        }
        
        $count_res = count($res);
        
        //找出关键字相关的记录
            $res_thirdparty = $this->get_thirdparty_product_by_name($keywords,0,10);
            
            if(!empty($res_thirdparty))
            {
                foreach ($res_thirdparty as $key => $value) {
                    
                    $res[$count_res+$key]['pinfo']=  $this->get_third_party_info_by_id($value['id']);
                    
                    $res[$count_res+$key]['name'] = $res[$count_res+$key]['pinfo']['product_name'];
                    $res[$count_res+$key]['id'] = 0;
                    $res[$count_res+$key]['isurl']= 1;
                    $res[$count_res+$key]['url']= C('product_domain').U('M/Founding/show_thirdparty_data',array('id'=>$res[$count_res+$key]['pinfo']['id']));
                    $res[$count_res+$key]['qualified']= (int)$res[$count_res+$key]['pinfo']['qualified'];
                    $res[$count_res+$key]['show_mark_icon']=  $this->_get_fouding_list_mark(1,$res[$count_res+$key]['pinfo']['qualified'],3);
                    $res[$count_res+$key]['location']= $res[$count_res+$key]['pinfo']['province'];
                    $res[$count_res+$key]['testing_date']= $res[$count_res+$key]['pinfo']['notification_number'];
                    $res[$count_res+$key]['batch_number']= $res[$count_res+$key]['pinfo']['cdate'];
                }
            }
//            print_r($res_thirdparty);
            $this->assign('results', $res);
            $this->assign("keywords", $keywords);
//            print_r($res);
            
            $this->display();
            
    }
    
    /*
     * 曝光台产品分页
     */
    
    public function unsafe_list_by_page() {
        $keywords = I('get.keywords');
        $pagecount = I('get.pagecount');
        
        $startposition = ($pagecount)*10;
        
        $res = array();
        
        $count_res = count($res);
        
        //找出关键字相关的记录
            $res_thirdparty = $this->get_thirdparty_product_by_name($keywords,$startposition,20);
            
            if(!empty($res_thirdparty))
            {
                foreach ($res_thirdparty as $key => $value) {
                    
                    $res[$count_res+$key]['pinfo']=  $this->get_third_party_info_by_id($value['id']);
                    
                    $res[$count_res+$key]['name'] = $res[$count_res+$key]['pinfo']['product_name'];
                    $res[$count_res+$key]['id'] = 0;
                    $res[$count_res+$key]['isurl']= 1;
                    $res[$count_res+$key]['url']= C('product_domain').U('M/Founding/show_thirdparty_data',array('id'=>$res[$count_res+$key]['pinfo']['id']));
                    $res[$count_res+$key]['qualified']= (int)$res[$count_res+$key]['pinfo']['qualified'];
                    $res[$count_res+$key]['show_mark_icon']=  $this->_get_fouding_list_mark(1,$res[$count_res+$key]['pinfo']['qualified'],3);
                    $res[$count_res+$key]['location']= $res[$count_res+$key]['pinfo']['province'];
                    $res[$count_res+$key]['testing_date']= $res[$count_res+$key]['pinfo']['notification_number'];
                    $res[$count_res+$key]['batch_number']= $res[$count_res+$key]['pinfo']['cdate'];
                }
            }
//            print_r($res_thirdparty);
//            $this->assign('results', $res);
            
        if(!empty($res))
        {

            $result['res']=$res;
            $result['err']=0;
            $result['msg']="成功获取数据";

        }
        else
        {
            $result['err']=1;
            $result['msg']="暂无更多数据";
            
        }
        echo json_encode($result);
//            print_r($res);

            
    }
    /*
     * 曝光台的首页列表数据
     */
    
     public function unsafe_list_by_page_for_index() {
        $pagecount = I('get.pagecount');
        
        $startposition = ($pagecount)*10;
        
        $res = array();
        
        $count_res = count($res);
        
        //找出关键字相关的记录
            $res_thirdparty = $this->get_thirdparty_product_by_name_for_unsafe_index($startposition,20);
            
            if(!empty($res_thirdparty))
            {
                foreach ($res_thirdparty as $key => $value) {
                    
                    $res[$count_res+$key]['pinfo']=  $this->get_third_party_info_by_id($value['id']);
                    
                    $res[$count_res+$key]['name'] = $res[$count_res+$key]['pinfo']['product_name'];
                    $res[$count_res+$key]['id'] = 0;
                    $res[$count_res+$key]['isurl']= 1;
                    $res[$count_res+$key]['url']= C('product_domain').U('M/Founding/show_thirdparty_data',array('id'=>$res[$count_res+$key]['pinfo']['id']));
                    $res[$count_res+$key]['qualified']= (int)$res[$count_res+$key]['pinfo']['qualified'];
                    $res[$count_res+$key]['show_mark_icon']=  $this->_get_fouding_list_mark(1,$res[$count_res+$key]['pinfo']['qualified'],3);
                    $res[$count_res+$key]['location']= $res[$count_res+$key]['pinfo']['province'];
                    $res[$count_res+$key]['testing_date']= $res[$count_res+$key]['pinfo']['notification_number'];
                    $res[$count_res+$key]['batch_number']= $res[$count_res+$key]['pinfo']['cdate'];
                }
            }
//            print_r($res_thirdparty);
//            $this->assign('results', $res);
            
        if(!empty($res))
        {

            $result['res']=$res;
            $result['err']=0;
            $result['msg']="成功获取数据";

        }
        else
        {
            $result['err']=1;
            $result['msg']="暂无更多数据";
            
        }
        echo json_encode($result);
//            print_r($res);

            
    }
    
    private function get_thirdparty_product_by_name($keywords,$offset,$number) {
        //获取第三方数据库不合格商品
        
        import("ORG.Util.Pinyin");
        $py = new \PinYin();
        
        $result = $py->getAllPY("$keywords",',');
        
        $keywords_pys = str_replace(',', ' +', $result);
        $keywords_pys ='+'.$keywords_pys;
        
        $Model = new \Think\Model();
        $res = $Model->query("select * from fd_product_thirdparty where product_name LIKE '%".$keywords."%' AND qualified=0 AND match(product_name_pinyin) against('".$keywords_pys."' IN BOOLEAN MODE) limit $offset,$number");
//        print_r($res);
//        print_r($Model->getLastSql());
//        exit();
        return $res;
    }
    
    /*
     * 不安全的食品列表
     */
    private function get_thirdparty_product_by_name_for_unsafe_index($offset,$number) {
        //获取第三方数据库不合格商品


        $Model = new \Think\Model();
        $res = $Model->query("select * from fd_product_thirdparty where qualified=0 limit $offset,$number");
        return $res;
    }
    
    /*
     * 更新跟投的钱到项目金额去
     */
    private function _founding_finish_money_increase($fid,$money) {
        $DB = M('fundings');
        
        $DB->where("id=$fid")->setInc('finish',$money);
        
    }
    
    /*
    * 判断founding 是否满标，满的话，就改状态
    */
   private function _chk_if_donate_ok($fid) {
       $DB = M('fundings');
       $res = $DB->find($fid);
       /*
        * 判断项目是否是审核中的项目
        */
       if($res['state']==1)
       {
            if($res['finish']>=($res['emoney']+$res['smoney']))
            {
                $DB->id = $fid;
                $DB->state = 2;
                $DB->etime = I("server.REQUEST_TIME");
                $DB->save();
            }
       }
       
   }
   
   /*
    * 获取指定分类下的分类id
    */
   private function _get_package_relationship_by_sortid($sortid) {
       $DB = M("classify_package_relationship");
       $map['classify_id']=$sortid;
       $res = $DB->where($map)->select();
       
       if(!empty($res))
       {
           $packageids ="";
           foreach ($res as $key => $value) {
               $packageids .=$value['package_id'].",";
           }
           $result = substr($packageids,0,strlen($packageids)-1); 
           
           return $result;
       }
       else
       {
           return "";
       }
       
   }




    /*
     * Date: 2015-10-15
     * 
     * 第三方数据展示
     */
    public function show_thirdparty_data() {
        $id = I('get.id');
        $res =  M('product_thirdparty')->find($id);
        
        $this->assign('product', $res);
        $this->display();
        
    }
    
    public function show_thirdparty_data_of_app() {
        $id = I('get.id');
        $res =  M('product_thirdparty')->find($id);
        
        $this->assign('product', $res);
        $this->display();
        
    }
    
    /*
     * 更加mid 获取membername
     * 
     */
    private function get_name_by_mid($mid) {
        $DB = M('members');
        $res = $DB->find($mid);
//        判断用户名资料
        $username = $res['username'];
        if(preg_match("/1[3458]{1}\d{9}$/",$username))
        {
            $username = substr($username, 0, -4)."****";
        }
        return $username;
    }
    
    /*
     * 根据lab id 得到 lab name
     */
    private function get_name_by_institutionid($id) {
        $DB = M('institution');
        $res = $DB->find($id);
        return $res['name'];
    }
    
    /*
     * 根据sortid获取分类名称
     */
    private function get_name_by_sortid($id) {
        $DB = M('food_classify');
        $res = $DB->find($id);
        return $res['name'];
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
     * 获取套餐的总价格
     */
    public function get_total_price_by_packageid($id) {
        /*
         * 判断id 是单个还是多少
         */
        $id_arr = explode(',', $id);
        $DB = M('fundings_package');
        foreach ($id_arr as $key => $value) {
            $res[$key] = $DB->find($value);
            $totalprice +=$res[$key]['price'];
//            echo $name;
        }
        return $totalprice/100;
    }
    
    
    /*
     * 获取套餐资料
     */
    private function get_package_by_id($id) {
        $DB = M('fundings_package');
        $res = $DB->find($id);
        return $res;
    }




    /*
     * 根据城市的code返回cityname
     */
    protected function get_cityname_by_citycode($citycode) {
        
        $DB = M('regions');
        
        $map['code']=$citycode;
        
        $res = $DB->where($map)->find();
        
        return $res['name'];
    }
    
    public function uploadimg() {
        $this->display();
    }
}