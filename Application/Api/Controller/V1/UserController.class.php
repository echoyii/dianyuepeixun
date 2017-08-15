<?php
namespace Api\Controller\V1;
use Think\Controller;

class UserController extends Controller {
    public function index(){
        header("Content-type:text/html;charset=utf8;");
        echo "众测天下";
    }
    //验证手机号 
    //发送短信通知
    public function get_verify_mobile(){

        //获取用户手机号
    	$mobile=trim(I('get.mobile'));
        //正则检测手机号码格式是否正确
    	if(preg_match("/1[34578]{1}\d{9}$/",$mobile)){
    	}else{
    		$result['err']=1;
    		$result['msg']='无法获取手机号码';
    		echo json_encode($result);
    		exit();
    	}

        //获取用户操作状态
    	$action=I('get.action');
    	if($action=='login'){
    		$map['mobile']=$mobile;
            //判断新老用户
    		$re_userinfo=M('fd_members')->where($map)->find();
            // print_r($re_userinfo);die;
    		if(empty($re_userinfo)){
                    
    			$new_data['username']=$mobile;
    			$new_data['password']=md5('123456');
    			$new_data['mobile']=$mobile;
    			$new_data['ctime']=I('server.REQUEST_TIME');
                //发送验证码，并获取返回的验证码信息
    			$re_msg=$this->set_short_msg($mobile); //send_sms_msg()
    			if(!empty($re_msg)){
                            
                    $record_data['mobile']=$mobile;
                    $record_data['action']=$action;
                    $record_data['content']=$re_msg['content'];
                    //把用户的验证信息写入fd_sms_record表
                    M('fd_sms_record')->data($record_data)->add();
                    //将新用户信息写入到用户表
    				M('fd_members')->data($new_data)->add();
                    //获取新用户的id
	    			$id=M('fd_members')->where(array('mobile'=>$mobile))->getField('id');
                    $ver_data['mobile']=$mobile;
                    $ver_data['code']=$re_msg['code'];
                    $ver_data['isused']=0;
                    $ver_data['ctime']=I('server.REQUEST_TIME');
                    $ver_data['expires_in']=$ver_data['ctime']+60;
                    $ver_data['memberid']=$id;
                    //写入短信验证信息
                    M('fd_verification_code')->data($ver_data)->add();
                    //获取用户唯一识别码
	    			$coding=$this->get_coding();
	    			$cod_data['coding']=$coding;
	    			$cod_data['memberid']=$id;
	    			M('fd_members_coding')->data($cod_data)->add();
                    
                    $result['err']=0;
                    $result['msg']="成功获取验证码";
                    $result['code']=$re_msg['code'];
                    $result['state']=0;
    			}
    		}else{
                $id=$re_userinfo['id'];
                $expires_in=M('fd_verification_code')->where(array('memberid'=>$id,'isused'=>0))->getField('expires_in');
                $now_time=I('server.REQUEST_TIME');
                if($now_time>$expires_in){
                    $re_msg=$this->set_short_msg($mobile);
                    
                    $result['err']=0;
                    $result['msg']="成功获取验证码";
                    $result['code']=$re_msg['code'];
                    $result['state']=1;
                }else{
                    $result['err']=1;
                    $result['msg']="请勿频繁请求验证码";
                    $result['state']=1;
                }
            }

    	}else{
    		$result['err']=2;
    		$result['msg']='Action出错';
    	}    	
    	echo json_encode($result);
    }


    //生成用户唯一识别编码
    public function get_coding(){
    	$num=time().str_pad(mt_rand(1,99999),5,'0',STR_PAD_LEFT);
    	// echo $num;
    	return $num;
    }

    //生成6位短信验证码
    public function get_mobile_coding(){
    	return str_pad(mt_rand(100000,999999),6,'0',STR_PAD_LEFT); 
    }

    //短信验证码发送
    public function set_short_msg($mobile){
    	$mobile=$mobile;
        //生成短信验证码
    	$code=$this->get_mobile_coding();
    	$content = urlencode("您好，您的验证码是".$code."请不要泄露给他人。若非本人操作，可不必理会此信息！【美到家】");
        $url = "http://sms.chanzor.com:8001/sms.aspx?action=send&account=meidaojia&password=130852&mobile=".$mobile."&content=".$content;
        $html = file_get_contents($url);
        $re_msg['html']=$html;
        $re_msg['code']=$code;
        $re_msg['content']=urldecode($content);
        return $re_msg;
    }


    //验证码验证接口
    public function get_verify_code(){
        $code=I('get.code');
        $mobile=I('get.mobile');
        /*
         * 优化建议
         * 
         * 获取验证码是否是正常的情况， isused = 0
         */
        
        $map['isused']=0;
        $map['mobile']=$mobile;
        $map['code']=$code;
        
        $DB = M('fd_verification_code');
        $res = $DB->where($map)->find();
        
        if(!empty($res))
        {
            $DB->id = $res['id'];
            $DB->isused = 1;
            $DB->save();
            $result['err']=0;
            $result['msg']='验证码匹配成功';
            $result['content']=$re_userinfo;
        }
        {
            $result['err']=1;
            $result['msg']='验证码错误或者已经过去，请重新获取';
        }
        echo json_encode($result);
        
        
        
        
//        $re_code=M('fd_verification_code')->where(array('mobile'=>$mobile))->getField('code');
//        if($re_code==$code){
//            $data['state']=1;
//            //更新用户状态为激活状态
//            M('fd_members')->where(array('mobile'=>$mobile))->data($data)->save();
//            //获取用户的信息
//            $re_userinfo=M('fd_members')->where(array('mobile'=>$mobile))->find();
//            $result['err']=0;
//            $result['msg']='验证码匹配成功';
//            $result['content']=$re_userinfo;
//            
//        }else{
//            $result['err']=1;
//            $result['msg']='验证码匹配失败';
//        }
//        echo json_encode($result);
    }
}
