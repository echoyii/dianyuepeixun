<?php
namespace M\Controller;
use Think\Controller;

/*
 * 共用的一些功能，如：
 * 1. 微信登陆
 * 2. 
 * 
 */
class CommonController extends Controller {
    private  $site_url;
    private  $current_url;
    public $memberinfo;
    public $accessToken;


    public function _initialize() {
        header("Content-Type:text/html; charset=utf-8");
        $this->site_url = "http://".I('server.HTTP_HOST')."/";
        $this->current_url = $this->get_url();

        $this->assign('site_url', $this->site_url);
        
        $site_title = "众测天下";
        $this->assign('site_title', $site_title);
        
        //资源的路径
        $resource_basic = __ROOT__."/Public/".C('TPL');
        $this->assign('resource_basic', $resource_basic);
        
        $exclude = array('show_thirdparty_data_of_app','show_thirdparty_data','detail','wx_app_pay_notify','wxpay_notify','details_for_app','logout','agreement');
        if(in_array(ACTION_NAME,$exclude))
        {
            
        }
        else
        {
            /*
            * 获取微信用户的openid并写入session
            */
//            exit($this->current_url);
           if(I('get.debug')==1)
           {
               session('wx_login_token', 'o-hJTuOcNtBcPdBepqjIpeSlu1lA');
               session('openid', 'o-hJTuOcNtBcPdBepqjIpeSlu1lA');
           }
           else
           {
                $this->wx_oauth_response($this->current_url);
           }
           
//
           /*
            * 获取用户的个人资料
            */
           $openid = session('openid');
           $member = M('members')->where(array('wxopenid'=>$openid))->find();
                      
           
           
           $this->memberinfo = $member;
           
           if(!session('?member'))
           {
               session('member', $member);
           }
           $this->assign('member', $member);
//           echo C('appid');
//            echo C('appsecret');
//            print_r(session('openid'));
//            exit();
           
        }
        
//        加载微信JSSDK资料
        $this->get_weixin_jssdk_config();
        
        //获取基本的分享资料
        $this->get_basic_share_info("众测天下", "健康比什么都重要，吃好喝好选好，我觉得这个平台挺管用……","www.zhongcetianxia.com/index.php/M", "http://zctx.1mdj.com/logo.png", "众测天下--健康比什么都重要，吃好喝好选好，我觉得这个平台挺管用……", "众测天下--健康比什么都重要，吃好喝好选好，我觉得这个平台挺管用……", "www.zhongcetianxia.com/index.php/M", "http://zctx.1mdj.com/logo.png");
        
        $this->accessToken = $this->get_AccessToken();
        
    }
    
    public function wx_oauth_response($jurl) {
        $wx_login_token = session('?wx_login_token');
        if(empty($wx_login_token))
        {
            $code = I('get.code');
            if(empty($code))
            {
                //获取微信认证的code
                
                $wechat = new \Com\WechatAuth(C('appid'),C('appsecret'));
                $code = $this->random_code();
                $getRequestCodeURL = $wechat->getRequestCodeURL($jurl, "$code",'snsapi_userinfo');
                redirect($getRequestCodeURL);
                
            }
            else
            {
//                exit($jurl);
                //以code去换access_token
                $wechat = new \Com\WechatAuth(C('appid'),C('appsecret'));
                $token  = $wechat->getAccessToken('code', $code);
                
                $userinfo = $wechat->getUserInfo($token);
                
                                //写入pre_members 记录
                $map['openid']=$token['openid'];
                $pre_member = M('pre_members')->where($map)->find();
                if(empty($pre_member))
                {
                    $data['openid']=$token['openid'];
                    $data['access_token']=$token['access_token'];
                    $data['expires_in']=$token['expires_in'];
                    $data['refresh_token']=$token['refresh_token'];
                    $data['scope']=$token['scope'];
                    $data['unionid']=$token['unionid'];
                    $data['ctime']=I('server.REQUEST_TIME'); 
                    
                    //注册新用户
                    if(M('pre_members')->add($data))
                    {
                        $new_data['username']=$userinfo['nickname'];
                        $new_data['password']=md5('123');
                        $new_data['avatar']=$userinfo['headimgurl'];
                        $new_data['wxopenid']=$token['openid'];
                        $new_data['unionid']=$token['unionid'];
                        $new_data['ctime']=I('server.REQUEST_TIME');
                        $new_data['utime']=I('server.REQUEST_TIME');
                        $new_data['state'] = 1;
                        $mid=M('members')->add($new_data);
                        
                        
                    }
                    
                    
                }
                else
                {
                    /*
                     * 检查是否存在会员，不存在需要新建用户
                     */
                    $member = M('members')->where(array('wxopenid'=>$token['openid']))->find();
                    if(empty($member))
                    {
                        $new_data['username']=$userinfo['nickname'];
                        $new_data['password']=md5('123');
                        $new_data['avatar']=$userinfo['headimgurl'];
                        $new_data['wxopenid']=$token['openid'];
                        $new_data['unionid']=$token['unionid'];
                        $new_data['ctime']=I('server.REQUEST_TIME');
                        $new_data['utime']=I('server.REQUEST_TIME');
                        $new_data['state'] = 1;
                        $mid=M('members')->add($new_data);
   
                    }
                    else
                    {
                        //更新数据
                        $DB = M('pre_members');
                        $id = $pre_member['id'];
                        $DB->id = $id;
                        $DB->access_token = $token['access_token'];
                        $DB->expires_in = $token['expires_in'];
                        $DB->refresh_token = $token['refresh_token'];
                        $DB->scope = $token['scope'];
                        $DB->unionid = $token['unionid'];
                        $DB->ctime = I('server.REQUEST_TIME');
                        $DB->save();
                        
                        //判断 unionid是否增加，没有的话更新
                        if(empty($member['unionid']))
                        {
                            $DB_Member = M('members');
                            $DB_Member->id = $member['id'];
                            $DB_Member->unionid=$token['unionid'];
                            $DB_Member->save();
                            
                        }
                    }
                    
                }
                
                session('wx_login_token', $token['access_token']);
                session('openid', $token['openid']);
                
            }
        }
        else
        {
            
        }
    }
    
    public function logout() {
        session(null);
        $this->success('',U('M/Index/index'));
    }
    
    
    /**
    * 生成随机验证码
    * @return  string
    */
   protected function random_code() {
        /* 选择一个随机的方案 */
        mt_srand((double) microtime() * 1000000);
        return str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }
    
    /**
    * 获取当前页面完整URL地址
    */
   private function get_url() {
       $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
       $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
       $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
       $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
       return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
    }
    
    /*
     * 获取微信接口JSSDK信息
     */
    public function get_weixin_jssdk_config() {
        $jssdk = new \Com\JSSDK(C('appid'),C('appsecret'));
        $signPackage = $jssdk->GetSignPackage();
        $this->assign('signPackage', $signPackage);
    }
    
    /*
     * 定义基本的分享资料
     */
    private function get_basic_share_info($title,$desc,$link,$thumb,$title_all,$desc_all,$link_all,$thumb_all)
    {
        /*
         * 分享到朋友圈
         */
        $share_all['title']=$title_all;
        $share_all['desc']=$desc_all;
        $share_all['link']=$link_all;
        $share_all['thumb']=$thumb_all;
        
        
        /*
         * 分享给朋友
         */
        
        $share['title']=$title;
        $share['desc']=$desc;
        $share['link']=$link;
        $share['thumb']=$thumb;
        
        $data['share_all']=$share_all;
        $data['share']=$share;
        
        $this->assign('sharedata', $data);
    }


    /*
     * 获取微信接口的returnAccessToken
     */
    public function get_AccessToken() {
        $jssdk = new \Com\JSSDK(C('appid'),C('appsecret'));
        $accesstoken = $jssdk->returnAccessToken();
        return $accesstoken;
    }
    
     /*
     * 获取机构的名称
     */
    protected function get_institutionname_by_id($id) {
        $res = M('institution')->find($id);
        return $res['name'];
    }
    
    protected function get_packagename_by_id($id) {
        $res = M('fundings_package')->find($id);
        return $res['name'];
    }
    
    public function getinfo() {
        session(null);
        
        redirect(U('M/index/index'));
//        print_r($this->memberinfo);
    }
    
    /*
     * 获取产品在第三方库里边的记录
     */
    protected function get_third_party_info_by_id($id)
    {
        $DB = M("product_thirdparty");
        $res = $DB->find($id);
        return $res;
    }
    
    /*
     * 计算项目要显示的图标
     */
    protected function _get_fouding_list_mark($type=0,$isqualified,$state) {
        switch ($state) {
            case 0:

                return "img/verifying_mark.png";
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
    
    /*
     * 获取检测项目的报告
     */
    protected function _get_founding_report($testreportid) {
        $DB = M("testreport");
        $res  = $DB->find($testreportid);
//        print_r($res);
        return $res;
    }
    
    /*
     * 获取检测项目的样品信息
     */
    protected function _get_founding_sample($sampleid) {
        $DB = M("fundings_image");
        $res  = $DB->find($sampleid);
//        print_r($res);
        return $res;
    }
    
    
}