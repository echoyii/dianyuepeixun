<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

use Think\Controller;
use Com\Wechat;

class WxapiController extends Controller{
    /**
     * 微信消息接口入口
     * 所有发送到微信的消息都会推送到该操作
     * 所以，微信公众平台后台填写的api地址则为该操作的访问地址
     */
    public function index(){

        $token = I('get.token'); //微信后台填写的TOKEN
        
        /*
         * 可以在这里判断$token 是否合法
         */
        
        
        /* 加载微信SDK */
        $wechat = new Wechat($token);
        
        
        /* 获取请求信息 */
        $data = $wechat->request();

        if($data && is_array($data)){

            /**
             * 你可以在这里分析数据，决定要返回给用户什么样的信息
             * 接受到的信息类型有9种，分别使用下面九个常量标识
             * Wechat::MSG_TYPE_TEXT       //文本消息
             * Wechat::MSG_TYPE_IMAGE      //图片消息
             * Wechat::MSG_TYPE_VOICE      //音频消息
             * Wechat::MSG_TYPE_VIDEO      //视频消息
             * Wechat::MSG_TYPE_MUSIC      //音乐消息
             * Wechat::MSG_TYPE_NEWS       //图文消息（推送过来的应该不存在这种类型，但是可以给用户回复该类型消息）
             * Wechat::MSG_TYPE_LOCATION   //位置消息
             * Wechat::MSG_TYPE_LINK       //连接消息
             * Wechat::MSG_TYPE_EVENT      //事件消息
             *
             * 事件消息又分为下面五种
             * Wechat::MSG_EVENT_SUBSCRIBE          //订阅
             * Wechat::MSG_EVENT_SCAN               //二维码扫描
             * Wechat::MSG_EVENT_LOCATION           //报告位置
             * Wechat::MSG_EVENT_CLICK              //菜单点击
             * Wechat::MSG_EVENT_MASSSENDJOBFINISH  //群发消息成功
             */
            
            /*
             * 记录收到的内容
             */
            $to_record_data['toUserName']=$data['ToUserName'];
            $to_record_data['fromUserName']=$data['FromUserName'];
            $to_record_data['log'] = json_encode($data);
            $to_record_data['ctime']=I('server.REQUEST_TIME');
            
            $to_record_data_db = M('wx_logs');
            $to_record_data_db->add($to_record_data);
            
            switch ($data['MsgType']) {
                case Wechat::MSG_TYPE_TEXT:
                    if($data['Content']=="shouye"||$data['Content']=="1"||$data['Content']=="首页")
                    {
                        $content = 'mo-鼓掌欢迎来到食品安全大家庭！这里有海量的权威检测报告供查询，你也可以发起食品检测，与热情、互助的小伙伴们一起募集目标检测资金，获得权威机构的检测报告，为您和家人舌尖上的安全尽职尽力！mo-玫瑰mo-玫瑰mo-玫瑰';
                        $wechat->response($content, Wechat::MSG_TYPE_TEXT);
                    }
                    
                    
                    
                    break;

                case Wechat::MSG_TYPE_EVENT:
                    if($data['Event']==Wechat::MSG_EVENT_SUBSCRIBE)
                    {
                        //关注事件处理
                        
                        $this->member_subscribe_action($data['FromUserName'], 1);
                        
                        $content = 'mo-鼓掌欢迎来到食品安全大家庭！这里有海量的权威检测报告供查询，你也可以发起食品检测，与热情、互助的小伙伴们一起募集目标检测资金，获得权威机构的检测报告，为您和家人舌尖上的安全尽职尽力！mo-玫瑰mo-玫瑰mo-玫瑰';
//                        $content= "欢迎您！合作商家可以通过本微信号登录商家后台管理订单和查看提成，如需要账户密码清联系业务员或联系客服，感谢您的支持，谢谢！";
                        $wechat->response($content, Wechat::MSG_TYPE_TEXT);
                    }
                    else if($data['Event']==Wechat::MSG_EVENT_UNSUBSCRIBE)
                    {
                        //取消关注事件处理
                        $this->member_subscribe_action($data['FromUserName'], 0);
                        
                    }
                    else if($data['Event']==Wechat::MSG_EVENT_CLICK)
                    {
//                        点击菜单事件处理
                    }
                    else if($data['Event']==Wechat::MSG_EVENT_SCAN)
                    {
//                        扫描二维码事件处理
                        
                        
                        
                    }
                    else
                    {
                        
                    }
                    
                    break;
                
                default:
                    $content = 'mo-鼓掌欢迎来到食品安全大家庭！这里有海量的权威检测报告供查询，你也可以发起食品检测，与热情、互助的小伙伴们一起募集目标检测资金，获得权威机构的检测报告，为您和家人舌尖上的安全尽职尽力！mo-玫瑰mo-玫瑰mo-玫瑰';
                    $wechat->response($content, Wechat::MSG_TYPE_TEXT);
                    break;
            }
            

            /**
             * 响应当前请求还有以下方法可以只使用
             * 具体参数格式说明请参考文档
             * 
             * $wechat->replyText($text); //回复文本消息
             * $wechat->replyImage($media_id); //回复图片消息
             * $wechat->replyVoice($media_id); //回复音频消息
             * $wechat->replyVideo($media_id, $title, $discription); //回复视频消息
             * $wechat->replyMusic($title, $discription, $musicurl, $hqmusicurl, $thumb_media_id); //回复音乐消息
             * $wechat->replyNews($news, $news1, $news2, $news3); //回复多条图文消息
             * $wechat->replyNewsOnce($title, $discription, $url, $picurl); //回复单条图文消息
             * 
             */
        }
    }
    
    /*
     * 关注的用户的处理
     * @param type ,1 是关注，0 是取消关注
     * 
     */
    public function member_subscribe_action($openid,$type) {
        $DB = M("members_subscribe_records");
        $map['openid']=$openid;
        $res =  $DB->where($map)->find();
        
        
        if(!empty($res))
        {
            $DB->id = $res['id'];
            $DB->issubscribe = $type;
            $DB->save();
            
        }
        else
        {
            $data['openid']=$openid;
            $data['issubscribe']=$type;
            $data['ctime']=I("server.REQUEST_TIME");
            $data['utime']=I("server.REQUEST_TIME");
            
            $DB->asdd($data);
        }
    }
    
    /*
     * 取消关注的用户的处理
     */
    
       
//    空方法的处理
    public function _empty()
    {
        echo "没发现";
    }
}
