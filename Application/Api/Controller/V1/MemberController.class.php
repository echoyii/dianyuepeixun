<?php
namespace Api\Controller\V1;
use Think\Controller;

class MemberController extends CommonController {

    //短信发送任务命令
    private $action='send';
    //短信发送用户账号
    private $account='zhongcetianxia';
    //短信发送用户密码
    private $password='153090';

    public function index(){
        header("Content-type:text/html;charset=utf8;");
        echo "众测天下";
    }
    
    /*
     *  先梳理一下登录流程
     * 
     * 1. 接收 提交 过来的 手机号码
     * 2. 判断手机号码是否是老用户
     * 2.1 老用户就获取到个人信息，mid（用户id）记录下来
     * 2.2 不是老用户，则注册为新用户，并且得到 mid
     * 
     * 3. 生成随机code，并将code和mid 写入 fd_verification_code 表；同时调用下发短信，并且记录发送记录到 fd_sms_record；
     * 
     * 4. 输出json结果
     * 
     * 
     */
    
    /*
     * 获取验证码接口
     * GET 请求
     * 
     */
    public function get_verify_code() {
        $mobile = trim(I('get.mobile'));
        
        if(!empty($mobile))
        {
            $member = $this->get_member_by_mobile($mobile);
            
            $chk_mobile = $this->_chk_mobile_in_whitelist($mobile);
            
            if($chk_mobile)
            {
                $random_code = 1234;
            }
            else
            {
                $random_code = $this->create_ramdon_code();
            }
            

            if($member)
            {
                //存在用户，判断是否请求频繁，仅仅下发验证码
                //app 上架期间先取消判定
//                $nowtimestamp = I('server.REQUEST_TIME');
//                if($nowtimestamp>($member['utime']+60))
                if(1==1)
                {
                    $bonding_code_mid = $this->bonding_code_mid($mobile, $member['id'], $random_code);
                    
                    if($chk_mobile)
                    {
//                        exit();
                        $content = "您好，您的验证码是1234请不要泄露给他人。若非本人操作，可不必理会此信息！【众测天下】";
//                        $content = "您好，您的验证码是1234请不要泄露给他人。若非本人操作，可不必理会此信息！【众测天下】";
                    }
                    else
                    {
                        $content = "您好，您的验证码是".$random_code."请不要泄露给他人。若非本人操作，可不必理会此信息！【众测天下】";
                    }
//                    exit();
//                    $content = ("您好，您的验证码是".$random_code."请不要泄露给他人。若非本人操作，可不必理会此信息！【众测天下】");
                    $this->send_sms($mobile, urlencode($content));
                    $this->send_sms_record($content, $mobile, 'login');
                    
                    $this->update_member($member['id']); //更新utime

                    $result['err']=0;
                    $result['msg']="成功获取验证码";
                    $result['code']=$random_code;
                    $result['state']=0;
                }
                else
                {
                    $result['err']=1;
                    $result['msg']="请勿频繁请求验证码";
                }
               


            }
            else
            {
                //不存在的用户就新添加
                $new_data['username']=$mobile;
                $new_data['password']=md5('123456');
                $new_data['mobile']=$mobile;
                $new_data['ctime']=I('server.REQUEST_TIME');
                $new_data['utime']=I('server.REQUEST_TIME');

                $mid = M('members')->add($new_data);

                $bonding_code_mid = $this->bonding_code_mid($mobile, $mid, $random_code);
                
                if($chk_mobile)
                {
                    $content = "您好，您的验证码是1234请不要泄露给他人。若非本人操作，可不必理会此信息！【众测天下】";
                }
                else
                {
                    $content = "您好，您的验证码是".$random_code."请不要泄露给他人。若非本人操作，可不必理会此信息！【众测天下】";
                }
                
                
                $this->send_sms($mobile, urlencode($content));
                $this->send_sms_record($content, $mobile, 'login');

                $result['err']=0;
                $result['msg']="成功获取验证码";
                $result['code']=$random_code;
                $result['state']=0;

            }
        }
        else
        {
            $result['err']=2;
            $result['msg']="手机号码错误";
        }
        
        echo json_encode($result);
    }
    
    
    /*
     * 验证码验证接口
     */
    public function verify_code(){
        $code=I('get.code');
        $mobile=I('get.mobile');
//        $device_token = I('get.device_token');
        $from_type = trim(I('get.from_type'));  //来源，判断是不是app来的
        $device_token = trim(I('get.device_token'));
        
        /*
         * 优化建议
         * 
         * 获取验证码是否是正常的情况， isused = 0
         */
        
        $map['isused']=0;
        $map['mobile']=$mobile;
        $map['code']=$code;
        
        $DB = M('verification_code');
        $res = $DB->where($map)->find();
        
        if(!empty($res))
        {
            $DB->id = $res['id'];
            $DB->isused = 1;
            if($DB->save())
            {
                $member = $this->get_member_by_mobile($mobile);
                $member['new_msg'] = $this->_getMemberNewMsg($member['id']);
                
                $this->update_device_token_mid($device_token, $member['id'],$from_type);
                $member['device_token']=$device_token;
                $member['from_type']=$from_type;
                
                
                $result['err']=0;
                $result['msg']='登录成功';
                $result['content']=$member;
            }
            else
            {
                $result['err']=11;
                $result['msg']='验证码错误或者已经过期，请重新获取';
            }
            
            
        }
        else
        {
            $result['err']=1;
            $result['msg']='验证码错误或者已经过期，请重新获取';
        }
        echo json_encode($result);

    }
    
    /*
     * 通过用户名和密码获取用户信息
     */
    public function get_member_info() {
        $mid = I('get.mid');
        $password = I('get.password');
        $data['id']=$mid;
        $data['password']=$password;
//        $device_token = I('get.device_token');
        $from_type = trim(I('get.from_type'));  //来源，判断是不是app来的
        $device_token = trim(I('get.device_token'));
        
        
        $member = M('members')->where($data)->find();
        if(!empty($member))
        {
            $this->update_device_token_mid($device_token, $member['id'],$from_type);
            $member['new_msg'] = $this->_getMemberNewMsg($member['id']);
            $member['device_token']=$device_token;
            
            $res['err']=0;
            $res['msg']="获取成功";
            $member['expires_in']=I('server.REQUEST_TIME')+86400*30;
            $res['content']=$member;
            
        }
        else
        {
            $res['err']=1;
            $res['msg']="账号或密码错误";
        }
        echo json_encode($res);
        
    }
    
    /*
     * 通过微信 的openid unionid 获取用户资料
     */
    public function get_member_info_by_unionid() {
        $unionid = I('get.unionid');
        $openid = I('get.openid');
        $from_type = trim(I('get.from_type'));  //来源，判断是不是app来的
        $device_token = trim(I('get.device_token'));
        
        /*
         * 获取一堆微信授权得到的数据
         * 
         */
        $nickname = I("get.nickname");
        
        
        
        
        $data['unionid']= $unionid;
        
        if(!empty($data['unionid']))
        {
            $member = M('members')->where($data)->find();
            if(!empty($member))
            {
                $this->update_device_token_mid($device_token, $member['id'],$from_type);
                $member['device_token']=$device_token;
                
                $res['err']=0;
                $res['msg']="获取成功";
                $member['expires_in']=I('server.REQUEST_TIME')+86400*30;
                $res['content']=$member;
            }
            else
            {
//                注册为新用户  
                if($from_type>0)
                {
                    $map['openid']=$openid;
                    $map['unionid']=$unionid;
//                    $map['from_type']=1;
                    
                    $pre_member = M('pre_members')->where($map)->find();
                    if(empty($pre_member))
                    {
                        $data['openid']=$openid;
                        $data['access_token']="";
                        $data['expires_in']="";
                        $data['refresh_token']="";
                        $data['scope']="";
                        $data['unionid']=$data['unionid'];
                        $data['from_type']=1;
                        $data['ctime']=I('server.REQUEST_TIME'); 
                        $pid = M('pre_members')->add($data);
                        //注册新用户
                        if($pid)
                        {
                            
                            $new_data['username']="用户".$pid;
                            $new_data['password']=md5('123');
                            $new_data['appopenid']=$openid;
                            $new_data['unionid']=$unionid;
                            $new_data['ctime']=I('server.REQUEST_TIME');
                            $new_data['utime']=I('server.REQUEST_TIME');
                            $mid=M('members')->add($new_data);
                            
                            $this->update_device_token_mid($device_token, $mid,$from_type);
                            
                            $member =M('members')->find($mid);                           
                            $res['err']=0;
                            $res['msg']="获取成功";
                            $member['expires_in']=I('server.REQUEST_TIME')+86400*30;
                            $res['content']=$member;
                        }


                    }
                    else
                    {
                        $new_data['username']="用户".$pre_member['id'];
                        $new_data['password']=md5('123');
                        $new_data['appopenid']=$openid;
                        $new_data['unionid']=$unionid;
                        $new_data['ctime']=I('server.REQUEST_TIME');
                        $new_data['utime']=I('server.REQUEST_TIME');
                        $mid=M('members')->add($new_data);

                        $this->update_device_token_mid($device_token, $mid,$from_type);

                        $member =M('members')->find($mid);                           
                        $res['err']=0;
                        $res['msg']="获取成功";
                        $member['expires_in']=I('server.REQUEST_TIME')+86400*30;
                        $res['content']=$member;

                    }
                }
                else
                {
                    $map['openid']=$openid;
                    $pre_member = M('pre_members')->where($map)->find();
                    if(empty($pre_member))
                    {
                        $data['openid']=$openid;
                        $data['access_token']="";
                        $data['expires_in']="";
                        $data['refresh_token']="";
                        $data['scope']="";
                        $data['unionid']=$data['unionid'];
                        $data['ctime']=I('server.REQUEST_TIME'); 

                        //注册新用户
                        if(M('pre_members')->add($data))
                        {
                            $new_data['username']="用户";
                            $new_data['password']=md5('123');
                            $new_data['appopenid']=$openid;
                            $new_data['unionid']=$unionid;
                            $new_data['ctime']=I('server.REQUEST_TIME');
                            $mid=M('members')->add($new_data);

                            $member =M('members')->find($mid);


                            $res['err']=0;
                            $res['msg']="获取成功";
                            $member['expires_in']=I('server.REQUEST_TIME')+86400*30;
                            $res['content']=$member;
                        }


                    }
                    else
                    {


                    }
                }
                
                    
            }
            
        }
        else
        {
            $res['err']=1;
            $res['msg']="Unionid不能为空";
        }
        
        echo json_encode($res);
    }
    
    /*
     * 手机微信授权获取用户资料提交
     * 
     */
    
    public function get_member_info_by_unionid_new() {
        
        
        
        
        /*
         * 获取一堆微信授权得到的数据
         * 
         */
        $openid = I('post.openid');
        $nickname = I("post.nickname");
        $sex = I("post.sex");
        $province = I("post.province");
        $city = I("post.city");
//        $country = I("post.country");
        $headimgurl = I("post.headimgurl");
//        $privilege = I("post.privilege");
        $unionid = I('post.unionid');
        
        /*
         * 另外单独的数据
         */
        $from_type = trim(I('post.from_type'));  //来源，判断是不是app来的
        $device_token = trim(I('post.device_token'));
        
        
        
        $data['unionid']= $unionid;
        
        if(!empty($data['unionid']))
        {
            $member = M('members')->where($data)->find();
            if(!empty($member))
            {
                $this->update_device_token_mid($device_token, $member['id'],$from_type);
                $member['device_token']=$device_token;
                
                $res['err']=0;
                $res['msg']="获取成功";
                $member['expires_in']=I('server.REQUEST_TIME')+86400*30;
                $res['content']=$member;
            }
            else
            {
//                注册为新用户  
                if($from_type>0)
                {
                    $map['openid']=$openid;
                    $map['unionid']=$unionid;
//                    $map['from_type']=1;
                    
                    $pre_member = M('pre_members')->where($map)->find();
                    if(empty($pre_member))
                    {
                        $data['openid']=$openid;
                        $data['access_token']="";
                        $data['expires_in']="";
                        $data['refresh_token']="";
                        $data['scope']="";
                        $data['unionid']=$data['unionid'];
                        $data['from_type']=1;
                        $data['ctime']=I('server.REQUEST_TIME'); 
                        $pid = M('pre_members')->add($data);
                        //注册新用户
                        if($pid)
                        {
                            
                            $new_data['username']=$nickname;
                            $new_data['password']=md5('123');
                            $new_data['appopenid']=$openid;
                            $new_data['unionid']=$unionid;
                            $new_data['ctime']=I('server.REQUEST_TIME');
                            $new_data['utime']=I('server.REQUEST_TIME');
                            
                            $new_data['gender']=$sex;
                            $new_data['province']=$province;
                            $new_data['city']=$city;
                            $new_data['avatar']=$headimgurl;

                            $mid=M('members')->add($new_data);
                            
                            $this->update_device_token_mid($device_token, $mid,$from_type);
                            
                            $member =M('members')->find($mid);                           
                            $res['err']=0;
                            $res['msg']="获取成功";
                            $member['expires_in']=I('server.REQUEST_TIME')+86400*30;
                            $res['content']=$member;
                        }


                    }
                    else
                    {
                        $new_data['username']=$nickname;
                        $new_data['password']=md5('123');
                        $new_data['appopenid']=$openid;
                        $new_data['unionid']=$unionid;
                        $new_data['ctime']=I('server.REQUEST_TIME');
                        $new_data['utime']=I('server.REQUEST_TIME');
                        
                        $new_data['gender']=$sex;
                        $new_data['province']=$province;
                        $new_data['city']=$city;
                        $new_data['avatar']=$headimgurl;
                        
                        $mid=M('members')->add($new_data);

                        $this->update_device_token_mid($device_token, $mid,$from_type);

                        $member =M('members')->find($mid);                           
                        $res['err']=0;
                        $res['msg']="获取成功";
                        $member['expires_in']=I('server.REQUEST_TIME')+86400*30;
                        $res['content']=$member;

                    }
                }
                else
                {
                    $map['openid']=$openid;
                    $pre_member = M('pre_members')->where($map)->find();
                    if(empty($pre_member))
                    {
                        $data['openid']=$openid;
                        $data['access_token']="";
                        $data['expires_in']="";
                        $data['refresh_token']="";
                        $data['scope']="";
                        $data['unionid']=$data['unionid'];
                        $data['ctime']=I('server.REQUEST_TIME'); 

                        //注册新用户
                        if(M('pre_members')->add($data))
                        {
                            $new_data['username']=$nickname;
                            $new_data['password']=md5('123');
                            $new_data['appopenid']=$openid;
                            $new_data['unionid']=$unionid;
                            $new_data['ctime']=I('server.REQUEST_TIME');
                            $new_data['utime']=I('server.REQUEST_TIME');
                            
                            $new_data['gender']=$sex;
                            $new_data['province']=$province;
                            $new_data['city']=$city;
                            $new_data['avatar']=$headimgurl;
                            
                            $mid=M('members')->add($new_data);

                            $member =M('members')->find($mid);


                            $res['err']=0;
                            $res['msg']="获取成功";
                            $member['expires_in']=I('server.REQUEST_TIME')+86400*30;
                            $res['content']=$member;
                        }


                    }
                    else
                    {


                    }
                }
                
                    
            }
            
        }
        else
        {
            $res['err']=1;
            $res['msg']="Unionid不能为空";
        }
        
        echo json_encode($res);
    }
    
    




    /*
     * 获取分享接口
     */
    public function get_my_share() {
        $mid = I('get.mid');
        $password=I('get.password');
                
        $share['title']="众测天下";
        $share['desc']="众筹食品检测，集大众的力量，共同守护食品安全";
        $share['link']="http://www.zhongcetianxia.com/";
        $share['imgUrl']="http://www.zhongcetianxia.com/Public/assets/img/logo.png";

        $this->json_output($share, '成功', '失败');
        
        
    }
    
    /*
     * 获取我发起的众筹
     */
    public function get_my_start_founding() {
        $mid = I('get.mid');
        
        $DB = M('fundings');
        $map['mid']=$mid;
        $res = $DB->where($map)->select();
        $this->json_output($res);
    }
    
    /*
     * 获取我参与的项目（包含我发起的）
     */
    
    public function orders() {
        $mid = I('get.mid');
        $password = I('get.password');
        
        $chk_user = $this->chk_user($mid, $password);
        
        if($chk_user)
        {
             //我参与的
            $my_takepart = $this->get_my_takepart($mid);
            
            $this->json_output($my_takepart);
        }
        else
        {
            $res['err']=1;
            $res['msg']="用户登录账户或密码有误".ACTION_NAME;
            echo json_encode($res);
        }
        
        
//        foreach ($my_takepart as $key => $value) {
//            if(mb_strlen($value['packagename'],"utf-8")>8)
//            {
////                echo $value['pjname'];
//                $my_takepart[$key]['packagename'] = mb_substr($value['packagename'], 0,8,'utf-8')."...";
//            }
//        }
        
        
        
    }
    
    /*
     * 我的消息的接口
     */
    public function message() {
        $mid = I('get.mid');
        $password=I('get.password');
        
        
        
        $chk_user = $this->chk_user($mid, $password);
        
        if($chk_user)
        {
            /*
             * 1. 获取该用户
             */
           $DB_notification_message_log = M("notification_message_log");
           
           $res = $DB_notification_message_log->query("SELECT `fd_notification_message_log`.*,`fd_notification_message`.`title` as title,`fd_notification_message`.`fdid` as fdid,`fd_notification_message`.`showtype` as showtype,`fd_notification_message`.`text` as desc FROM `fd_notification_message_log`  LEFT JOIN `fd_notification_message` ON `fd_notification_message_log`.message_id = `fd_notification_message`.id WHERE `fd_notification_message_log`.`isread`=0 AND  `fd_notification_message_log`.`mid`=".$mid." GROUP BY `fd_notification_message`.id");
           
           $this->json_output($res);
           
           
        }
        else
        {
            $res['err']=1;
            $res['msg']="用户登录账户或密码有误".ACTION_NAME;
            echo json_encode($res);
        }
        
    }
    
    private function get_my_takepart($mid){

        $DB_sponsor = M('fundings_sponsor');
        
        

        $my_takepart = $DB_sponsor->query("SELECT `fd_fundings_sponsor`.`id` as sponsorid,`fd_fundings_sponsor`.`fid` as id, `fd_fundings`.`ctime` as ctime,`fd_fundings`.`batch` as batch, `fd_fundings`.`packageid` as packageid,`fd_fundings`.`pjname` as pjname,`fd_fundings`.`state` as state,`fd_fundings`.`location` as location,`fd_fundings`.`result` as result,`fd_fundings`.`thumb` as thumb,`fd_fundings`.`finish` as finish,(`fd_fundings`.`emoney`+`fd_fundings`.`smoney`*`fd_fundings`.`samnum`) as emoney FROM `fd_fundings_sponsor`  LEFT JOIN `fd_fundings` ON `fd_fundings_sponsor`.fid = `fd_fundings`.id WHERE `fd_fundings_sponsor`.`mid`=".$mid." and `fd_fundings_sponsor`.`status`=1  GROUP BY fid ORDER BY state,ctime");

//        print_r($my_takepart);
        
        if(!empty($my_takepart)){
            $DB_f = M('fundings');
            foreach ($my_takepart as $key => $value) {
                
                $res_tmp =  $DB_f->find($value['id']);
                
                if(!empty($res_tmp))
                {
                    $my_takepart2[$key]=$my_takepart[$key];
                    $my_takepart2[$key]['packagename']=  $this->get_name_by_packageid($value['packageid']);
                }
                
                
                
                
            }
            $my_takepart3 = (array_values($my_takepart2));
//            print_r($my_takepart2);
            return $my_takepart3;
            
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
     * 获取我赞助的众筹
     */
    public function get_my_sponsor_founding() {
        
    }
    
    /*
     * 获取我关注的
     */
    public function get_my_focus_founding() {
        print_r("33");
    }
    
    /*
     * 获取我的通知
     * 通知类型有系统消息和项目通知
     * 系统消息看内容
     * 项目通知跳转到项目详情页去
     * 
     */
    public function get_my_notice() {
        $mid = I('get.mid');
        $password=I('get.password');
        
//        先模拟一个数据
        $data[] = array('id'=>2,'title'=>'系统消息','text'=>'众测天下上线啦','content'=>'http://www.zhongcetianxia.com/index.php/Home/Essay/details/id/32.html','showtype'=>0,'fdid'=>0,'fd_state'=>0,'ctime'=>1446782820);
        $data[] = array('id'=>3,'title'=>'天虹检测审核通过通知','text'=>'天虹检测审核通过通知','content'=>'http://www.zhongcetianxia.com/index.php/m','showtype'=>1,'fdid'=>92,'fd_state'=>1,'ctime'=>1446782820);
        $data[] = array('id'=>4,'title'=>'伊利检测审核通过通知','text'=>'伊利检测审核通过通知','content'=>'http://www.zhongcetianxia.com/index.php/m','showtype'=>1,'fdid'=>93,'fd_state'=>2,'ctime'=>1446782820);
        $data[] = array('id'=>5,'title'=>'大豆油检测审核通过通知','text'=>'大豆油检测审核通过通知','content'=>'http://www.zhongcetianxia.com/index.php/m','showtype'=>1,'fdid'=>88,'fd_state'=>3,'ctime'=>1446782820);
        
        $this->json_output($data);
        
    }
    
    
    /*
     * 根据手机号码判断是否是老用户
     */
    private function get_member_by_mobile($mobile) {
        $DB = M("members");
        $map['mobile']=$mobile;
        
        $res = $DB->where($map)->find();
        
        if(empty($res))
        {
            return FALSE;
        }
        else
        {
            return $res;
        }
        
    }
    
    /*
     * 更新友盟 device_token 和 mid 的绑定关系
     */
    private function update_device_token_mid($device_token,$mid,$from_type=0) {
        $map['device_token']=$device_token;
        $DB = M('members');
        
        $res = $DB->where($map)->find();
        if(!empty($res))
        {
            if($res['id']==$mid)
            {
                if($device_token!=$res['device_token'])
                {
                    $DB->id = $mid;
                    $DB->device_token = $device_token;
                    $DB->from_type = $from_type;
                    $DB->save();
                }
                
            }
            else
            {
                //不一致则删除之前的；然后更新最新的
                $DB->id = $res['id'];
                $DB->device_token = "";
                $DB->save();
                $DB2 = M('members');
                $DB2->id = $mid;
                $DB2->device_token = $device_token;
                $DB2->from_type = $from_type;
                $DB2->save();
            }
        }
        else
        {
            $DB->id = $mid;
            $DB->device_token = $device_token;
            $DB->from_type = $from_type;
            $DB->save();
        }
        
        
    }
    
    
    /*
     * 生成随机验证码
     */
    //生成6位短信验证码
    private function create_ramdon_code(){
        mt_srand((double) microtime() * 1000000);
        return str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    }
    
    /*
     * 记录验证码和用户的关联关系
     */
    private function bonding_code_mid($mobile,$mid,$code) {
        $data['memberid']=$mid;
        $data['mobile']=$mobile;
        $data['code']=$code;
        $data['ctime']=I('server.REQUEST_TIME');
        $data['expires_in']=I('server.REQUEST_TIME')+600;
        $id = M('verification_code')->add($data);
        if($id)
        {
            return $id;
        }
        else
        {
            return FALSE;
        }
    }
    
    /*
     * 更新用户的获取验证码更新时间
     */
    private function update_member($mid) {
        $DB = M('members');
        $DB->id = $mid;
        $DB->utime = I('server.REQUEST_TIME')+60;
        $DB->save();
        
    }



    
    /*
     * 记录下发短信记录
     */
    private function send_sms_record($content,$mobile,$action) {

        //下发短信记录
        $sms_data['content'] = $content;
        $sms_data['mobile']=$mobile;
        $sms_data['ctime']=I('server.REQUEST_TIME');
        $sms_data['action']=$action;
        M('sms_record')->add($sms_data);

    }

    /**
     date: 2015-10-19
     des: 修改短信认证接口
     */

    //发送短信
    public function Post($data, $target) {

        $url_info = parse_url($target);
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader .= "Host:" . $url_info['host'] . "\r\n";
        $httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader .= "Content-Length:" . strlen($data) . "\r\n";
        $httpheader .= "Connection:close\r\n\r\n";
        //$httpheader .= "Connection:Keep-Alive\r\n\r\n";
        $httpheader .= $data;

        $fd = fsockopen($url_info['host'], 80);
        fwrite($fd, $httpheader);
        $gets = "";
        while(!feof($fd)) {
            $gets .= fread($fd, 128);
        }
        fclose($fd);
        return $gets;

    }

        // ("您好，您的验证码是1233请不要泄露给他人。若非本人操作，可不必理会此信息！【众测天下】");
//    public function send_sms($mobile,$content){
//
//        if($this->chk_in_blacklist($mobile))
//        {
//            $target = "http://sms.chanzor.com:8001/sms.aspx";
//            $post_data = "action=".$this->send."&userid=&account=".$this->account."&password=".$this->password."&mobile=".$mobile."&sendTime=&content=".rawurlencode($content);
//            //$binarydata = pack("A", $post_data);
//            $gets = $this->Post($post_data, $target);
//    //        $start=strpos($gets,"<?xml");
//    //        $data=substr($gets,$start);
//    //        $xml=simplexml_load_string($data);
//    //        var_dump(json_decode(json_encode($xml),TRUE));
//        }
//        
//    }
    
    public function send_sms($mobile,$content) {
        
        if($this->chk_in_blacklist($mobile))
        {
            //判断是否在测试白名单里边
            $chk_mobile = $this->_chk_mobile_in_whitelist($mobile);
            if(!$chk_mobile)
            {
                
                //           $content = urlencode("您好，您的验证码是".$code."请不要泄露给他人。若非本人操作，可不必理会此信息！【美到家】");
                $url = "http://sms.chanzor.com:8001/sms.aspx?action=send&account=zhongcetianxia&password=153090&mobile=".$mobile."&content=".$content;
                $html = file_get_contents($url); 
            }

        }
        else
        {
        }
        
    }
    
    //判断是否在黑名单
    private function chk_in_blacklist($mobile) {
        $map['mobile']=$mobile;
        $res = M('sms_mobile_blacklist')->where($map)->find();
        if(empty($res))
        {
            return TRUE;
        }
        else
        {
//            插入log
            $data['log']=$mobile;
            $data['ctime']=I('server.REQUEST_TIME');
            M('sms_mobile_blacklist_log')->add($data);
            
            return FALSE;
        }
    }
    
   /*
    * 检查是否有新消息
    */
   private function _getMemberNewMsg($mid) {
       
       return 1;
   }



   /**
    * 我的项目 获取用户参与发起的项目
    */
//   public function orders(){
//
//        $mid = I('get.mid');
//        if(!empty($mid)){
//
//            $map['mid'] = $mid;
//        }else{
//
//            $res['err'] = 1;
//            $res['msg'] = 'mid出错!';
//            $res['content'] = '';
//            echo json_encode($res);
//            exit();
//        }
//        $map['status'] = 1;
//        $DB_fundings_sponsor = M('fundings_sponsor ');
//
//        $list = $DB_fundings_sponsor->where($map)->field('fid,type')->select();
//        //根据fid获取对应的项目
//        foreach ($list as $k => $v) {
//            # code...
//            $list[$k] = M('fundings')->where(array('id' => $v['fid']))->field('pjname,packageid,location,result,etime,thumb')->find();
//        }
//
//        //获取项目的检测内容
//        foreach ($list as $k => $v) {
//            # code...
//            $list[$k]['packageid'] = $this->get_name_by_packageid($v['funding']['packageid']);
//        }
//
//        $this->json_output($list);
//   }



   /*
     * 获取套餐资料
     */
   protected function get_name_by_packageid($id) {
        /*
         * 判断id 是单个还是多少
         */
        $id_arr = explode(',', $id);
        $DB = M('fundings_package');
        $name="";
        foreach ($id_arr as $key => $value) {
            $res[$key] = $DB->find($value);
            $name .=",".$res[$key]['name'];
        }
        return substr($name, 1);
    }




    /**
     * 修改用户名
     * 
     */
    
    public function modify_username(){

        $id = I('get.mid');
        if(!empty($id)){

            $map['id'] = $id;
        }else{
            $res['err'] = 1;
            $res['msg'] = 'mid出错!';
            $res['content'] = '';
            echo json_encode($res);
            exit();
        }

        $username = I('get.username');
        if(!empty($username)){
            $map_name['username'] = $username;
            $result = M('members')->where($map_name)->find();
            if(!empty($result)){

                $res['err'] = 1;
                $res['msg'] = '用户名重复';
                $res['content'] = '';
                echo json_encode($res);
                exit();
            }else{
                $data['username'] = $username;
            }
            
        }else{

            $res['err'] = 1;
            $res['msg'] = 'username出错!';
            $res['content'] = '';
            echo json_encode($res);
            exit();
        }

        $DB_members = M('members');

        $list = $DB_members->where($map)->data($data)->save();

        if(!empty($list)){

            $this->json_output(1);
        }else{

            $this->json_output(0);
        }

    }
    
    

    //修改手机号码
    public function modify_mobile(){

        $id = I('get.mid');
        if(!empty($id)){

            $map['id'] = $id;
        }else{

            $res['err'] = 1;
            $res['msg'] = 'mid出错!';
            $res['content'] = '';
            echo json_encode($res);
            exit();
        }

        $mobile = I('get.mobile');
        if(!empty($mobile)){

            $data['mobile'] = $mobile;
        }else{

            $res['err'] = 1;
            $res['msg'] = 'mobile出错!';
            $res['content'] = '';
            echo json_encode($res);
            exit();
        }

        $DB_members = M('members');

        $list = $DB_members->where($map)->data($data)->save();

        if(!empty($list)){

            $this->json_output(1);
        }else{

            $this->json_output(0);
        }
    }


    //修改性别
    public function modify_gender(){

        $id = I('get.mid');
        if(!empty($id)){

            $map['id'] = $id;
        }else{

            $res['err'] = 1;
            $res['msg'] = 'mid出错!';
            $res['content'] = '';
            echo json_encode($res);
            exit();
        }

        $gender = I('get.gender');
        if(isset($gender)){

            $data['gender'] = $gender;
        }else{

            $res['err'] = 1;
            $res['msg'] = 'gender出错!';
            $res['content'] = '';
            echo json_encode($res);
            exit();
        }

        $DB_members = M('members');

        $list = $DB_members->where($map)->data($data)->save();

        if(!empty($list)){

            $this->json_output(1);
        }else{

            $this->json_output(0);
        }
    }


    //修改地址
    public function modify_address(){

        $id = I('get.mid');
        if(!empty($id)){

            $map['id'] = $id;
        }else{

            $res['err'] = 1;
            $res['msg'] = 'mid出错!';
            $res['content'] = '';
            echo json_encode($res);
            exit();
        }

        $address = I('get.address');
        if(!empty($address)){

            $data['address'] = $address;
        }else{

            $res['err'] = 1;
            $res['msg'] = 'gender出错!';
            $res['content'] = '';
            echo json_encode($res);
            exit();
        }

        $code = I('get.code');
        if(!empty($code)){
            // $data['code'] = $code;
            $ad = $this->get_code_name($code);
            $data['province'] = $ad['province'];
            $data['city'] = $ad['city'];
        }else{
            $res['err'] = 1;
            $res['msg'] = 'code出错!';
            $res['content'] = '';
            echo json_encode($res);
            exit();
        }

        $DB_members = M('members');

        $list = $DB_members->where($map)->data($data)->save();

        if(!empty($list)){

            $this->json_output(1);
        }else{

            $this->json_output(0);
        }
    }


    //通过地区code获取对应的省份 城市
    private function get_code_name($code){

        $DB_regions = M('regions');

        $city_map['code'] = $code;

        $ad['city'] = $DB_regions->where($city_map)->getField('name');


        //根据市code反推省份code
        $province_map['code'] = ($code-($code%100))/100;
        $ad['province'] = $DB_regions->where($province_map)->getField('name');

        return $ad;
    }


    //修改生日
    public function modify_birthday(){

        $id = I('get.mid');
        if(!empty($id)){

            $map['id'] = $id;
        }else{

            $res['err'] = 1;
            $res['msg'] = 'mid出错!';
            $res['content'] = '';
            echo json_encode($res);
            exit();
        }

        $birthday = I('get.birthday');
        if(isset($birthday)){

            $data['birthday'] = $birthday;
        }else{

            $res['err'] = 1;
            $res['msg'] = 'birthday出错!';
            $res['content'] = '';
            echo json_encode($res);
            exit();
        }

        $DB_members = M('members');

        $list = $DB_members->where($map)->data($data)->save();

        if(!empty($list)){

            $this->json_output(1);
        }else{

            $this->json_output(0);
        }
    }
    
    
    /*
     * 修改头像
     */
    /*
     * 提交图片
     */
    public function modify_avatar() {
        if(IS_POST)
        {
            $mid = I('post.mid');
            $password = I("post.password");
            
            $chk_usr = $this->chk_user($mid, $password);
            
            if($chk_usr)
            {
                $img_url='Uploads'.'/'.$this->upload($_FILES['avatar']);
            
                $DB = M("fundings_image");
                $data['img_url']=$img_url;

                $id = $DB->add($data);
    //            echo $id;
                /*
                 * 保存到对应的用户头像去
                 */
                if($id)
                {
                   $DB_member = M("members");
                   $DB_member->id = $mid;
                   $DB_member->avatar = "http://".I('server.HTTP_HOST')."/".$img_url;
                   $DB_member->save();
                   $this->json_output($id);
                }
                else
                {
                    $this->json_output(0);
                }


                
            }
            else
            {
                $res['err']=1;
                $res['msg']="用户登录账户或密码有误".ACTION_NAME;
                echo json_encode($res);
            }
            
        }
        else
        {
            $this->display();
//            $this->json_output($id);
        }
        
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


    /**
     * 用户反馈接口
     */
    
    public function sub_feedback(){

        $mid = I('get.mid');
        if(!empty($mid)){

            $data['mid'] = $mid;
        }else{

            $res['err'] = 1;
            $res['msg'] = 'mid出错!';
            $res['content'] = '';
            echo json_encode($res);
            exit();
        }

        $contact = I('get.contact');
        if(!empty($contact)){

            $data['contact'] = $contact;
        }else{

            $res['err'] = 1;
            $res['msg'] = 'contact出错!';
            $res['content'] = '';
            echo json_encode($res);
            exit();
        }

        $content = I('get.content');
        if(!empty($content)){

            $data['content'] = $content;
        }else{

            $res['err'] = 1;
            $res['msg'] = 'content出错!';
            $res['content'] = '';
            echo json_encode($res);
            exit();
        }

        $data['ctime'] = I('server.REQUEST_TIME');

        $result = M('feedback')->data($data)->add();

        if(!empty($result)){

            $this->json_output(1);
        }else{

            $this->json_output(0);
        }
    }
    
    /*
     * 白名单概念
     */
    private function _chk_mobile_in_whitelist($mobile)
    {
        $DB = M("sms_mobile_whitelist");
        $map['mobile']=$mobile;
        
        $res = $DB->where($map)->find();
        if(empty($res))
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
        
    }
}
