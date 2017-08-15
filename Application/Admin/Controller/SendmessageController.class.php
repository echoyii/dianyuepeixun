<?php
namespace Admin\Controller;
use Think\Controller;
class SendmessageController extends CommonController{
	
	protected $pr_appkey = '5629aa63e0f55a5fa90012e4';
	protected $pr_secret = 'pv3bxw8lyzmz3xcj7ahb6axrg7a5lryb';

    protected $ios_appkey = '5629ab1f67e58e0c4e000018';
    protected $ios_secret = '9csatbmwvhevhuncedwma7pv2uu455ta';


    //创建新消息
    public function create()
    {

        if(!IS_POST)
        {

            $this->display();

        }
        else
        {

            $ticker = I('post.ticker');
            if(!empty($ticker))
            {

                $data['ticker'] = $ticker;
            }
            else
            {

                $this->error('消息提示文字不能为空');
            }

            $title = I('post.title');
            if(!empty($title))
            {

                $data['title'] = $title;
            }

            $text = I('post.text');
            if(!empty($text))
            {

                $data['text'] = $text;
            }

            $content = I('post.content');
            if(!empty($content))
            {

                $data['content'] = $content;
            }

            $type = I('post.type');
            // echo $type; die;
            if(!empty($type))
            {

                $data['type'] = $type;
            }

            //默认消息为系统消息
            $data['showtype'] = 0;
            //当消息为系统消失时  项目id默认为0
            $data['fdid'] = 0;

            $data['ctime'] = I('server.REQUEST_TIME');

            $User = M('notification_message');

            $re = $User->data($data)->add();

            if($re)
            {
                $this->success('添加成功!',U('Admin/Sendmessage/create'));
            }
            else
            {

                $this->error('添加失败!');
            }

        }
    }



    //消息列表
    public function lists()
    {

        if(!IS_POST)
        {

            $User = M('notification_message');

            $list = $User->where(array('mtype' => 0))->select();

            foreach ($list as $k => $v) 
            {
                # code...
                $list[$k]['content'] = htmlspecialchars_decode($v['content']);
            }
            $this->assign('list',$list);


            $this->display();
        }
    }


    //修改消息
    public function modify()
    {
        if(!IS_POST)
        {

            $id = I('get.id');
            if(!empty($id))
            {

                $map['id'] = $id;
            }
            else
            {

                $this->error('参数出错');
            }

            $list = M('notification_message')->where($map)->find();

            $list['content'] = htmlspecialchars_decode($list['content']);


            $this->assign('list',$list);
            $this->display();

        }
        else
        {

            $ticker = I('post.ticker');
            if(!empty($ticker))
            {

                $data['ticker'] = $ticker;
            }
            else
            {

                $this->error('消息提示文字不能为空');
            }

            $title = I('post.title');
            if(!empty($title))
            {

                $data['title'] = $title;
            }

            $text = I('post.text');
            if(!empty($text))
            {

                $data['text'] = $text;
            }

            $content = I('post.content');
            if(!empty($content))
            {

                $data['content'] = $content;
            }

            $id = I('post.id');
            if(!empty($id))
            {

                $map['id'] = $id;
            }

            $re = M('notification_message')->where($map)->data($data)->save();

            if($re)
            {

                $this->success('修改成功!',U('Admin/Sendmessage/lists'));
            }
            else
            {

                $this->error('修改失败!');
            }

        }
    }


    //ajax删除消息
    public function delete()
    {

        $id = I('post.id');
        if(!empty($id))
        {

            $map['id'] = $id;
        }

        $re = M('notification_message')->where($map)->delete();

        if($re)
        {

            echo json_encode(1);
        }
        else
        {

            echo json_encode(0);
        }
    }



    //向选择的用户发送消息
    public function send()
    {

        if(!IS_POST)
        {

            $id = I('get.id');
            if(!empty($id))
            {

                $map['id'] = $id;
            }

            //获取要发送的消息内容
            $list = M('notification_message')->where($map)->find();
            //获取所有的分组
            $list['members_group'] = M('members_group')->select();

            $this->assign('list',$list);
            $this->display();
        }
        else
        {

            $gid = I('post.gid');
            if(!empty($gid))
            {

                $map['gid'] = $gid;
            }

            $id = I('post.id');
            if(!empty($id))
            {

                $not['id'] = $id;
            }
            //获取到消息的内容
            $notification_message = M('notification_message')->where($not)->find();

            //获取到该分组下的所有的用户mid
            $members_group_user = M('members_group_user')->where($map)->getField('mid',true);

            //记录要发送的所用用户的id
            $mid = $members_group_user;
            //记录发送的消息id
            $message_id = $not['id'];

            //消息发送记录时间
            $ctime = I('server.REQUEST_TIME');


            //获取分组内包含用户的所有device_token
            $members = M('members');
            foreach ($members_group_user as $k => $v) 
            {
                // 获取所有安卓设备的device_token;
                $android[$k] = $members->where(array('id' => $v,'from_type' => 1))->getField('device_token');
                if($android[$k]==null){
                    unset($android[$k]);
                }
                //获取所有ios用户的device_token
                $ios[$k] = $members->where(array('id' => $v,'from_type' => 2))->getField('device_token');
                if($ios[$k]==null){
                    unset($ios[$k]);
                }
            }
           
            $ticker = $notification_message['ticker'];
            $title = $notification_message['title'];
            $text = $notification_message['text'];
            $type = $notification_message['type'];
            

            //判断消息的类型
            switch ($type) 
            {
                case 'go_app':
                    $url = '';
                    $custom = '';
                    $activity = '';
                    break;
                case 'go_url':
                    $url = htmlspecialchars_decode($notification_message['content']);
                    $url = ltrim($url,"<p>");
                    $url = rtrim($url,"</p>");
                    $url = trim($url);
                    $custom = '';
                    $activity = '';
                    break;
                
                default:
                    $url = '';
                    $custom = htmlspecialchars_decode($notification_message['content']);
                    $custom = ltrim($custom,"<p>");
                    $custom = rtrim($custom,"</p>");
                    $custom = trim($custom);
                    $activity = 'ProjectInfoActivity';
                    break;
            }

            //引入消息发送文件
            import("Org.Util.Sendmessage");

            // 将数组转化为用逗号分开的字符串
            if(!empty($android)){
                $device_token_android = implode(',',$android);
                // echo $device_token_android; die;
                $Sendmessage = new \Sendmessage($this->pr_appkey,$this->pr_secret,$device_token_android,$ticker,$title,$text,$type,$url,$custom,$activity);
                $result = $Sendmessage->sendAndroidListcast();
            }
            if(!empty($ios)){
                // print_r($ios); die;
                $device_token_ios = implode(',',$ios);
                // echo $device_token_ios; die;
                $Sendmessage = new \Sendmessage($this->ios_appkey,$this->ios_secret,$device_token_ios,$ticker,$title,$text,$type,$url,$custom,$activity);
                $result = $Sendmessage->sendIOSListcast();   
            }
                
            // die;
            //向数据库写入发送消息记录
            
            $re = $this->add_message_log($mid,$gid,$message_id,$ctime);

            if($re)
            {

                $this->success('发送成功!',U('Admin/Sendmessage/lists'));
            }
            else
            {

                $this->error('发送消息失败!');
            }

            
        }
    }
        
    

    //众测项目审核通过后向发起人发送app通知
    public function sendAndroidmessage_pass_one($id)
    {
        //获取传过来的项目id
        $id = $id;
        // $id = I('get.id');
        if(!empty($id))
        {

            $map['id'] = $id;
        }

        $fundings = M('fundings');
        //获取发起者的id
        $mid = $fundings->where($map)->getField('mid');
        //获取该项目的项目名
        $pjname = $fundings->where($map)->getField('pjname');
        // echo $mid; die;
        if(!empty($mid))
        {

            $members = M('members');

            $device_token = $members->where(array('id' => $mid))->getField('device_token');

            $from_type = $members->where(array('id' => $mid))->getField('from_type');
            // echo $device_token; die;
            //向指定的用户发送通知消息
            import("Org.Util.Sendmessage");

            $ticker = '您发起的项目【'.$pjname.'】已通过审核，正在进行众筹!';
            $title = '您发起的项目【'.$pjname.'】已通过审核，正在进行众筹!';
            $text = '您发起的项目【'.$pjname.'】已通过审核，正在进行众筹!';
            $type = 'go_activity';
            $url = '';
            $custom = $id;
            $activity = "ProjectInfoActivity";

            $stype = 1;
            //拼接发送消息
            $content  = $this->project_notification($fid,$stype);

            if(!empty($content))
            {

                $not_data['ctime'] = I('server.REQUEST_TIME');
                $not_data['mtype'] = 1;
                $not_data['ticker'] = $ticker;
                $not_data['title'] = $title;
                $not_data['text'] = $text;
                $not_data['content'] = $content;
                $not_data['fdid'] = $map['id'];
                //消息为项目消息
                $not_data['showtype'] = 1;

                $message_id = M('notification_message')->data($not_data)->add();

                if(!empty($message_id))
                {

                    $log_data['message_id'] = $message_id;
                    $log_data['mid'] = $mid;
                    $log_data['ctime'] = I('server.REQUEST_TIME');

                    $re = M('notification_message_log')->data($log_data)->add();

                    if(!empty($re))
                    {
                        if($from_type==1)
                        {
                            $Sendmessage = new \Sendmessage($this->pr_appkey,$this->pr_secret,$device_token,$ticker,$title,$text,$type,$url,$custom,$activity);
                            $Sendmessage->sendAndroidUnicast();
                        }elseif($from_type==2)
                        {
                            $Sendmessage = new \Sendmessage($this->ios_appkey,$this->ios_secret,$device_token,$ticker,$title,$text,$type,$url,$custom,$activity);
                            $Sendmessage->sendIOSUnicast();
                        }else{

                            echo 'success';
                        }                       
                    }
                }
            }
            
        }
    }



    //众筹金额完成进行抽样向所有参与的用户发起通知
    public function sendAndroidmessage_pass_two($id)
    {

        $fid = $id;
        // echo $fid; die;
        // $fid = I('get.id'); 
        if(!empty($fid))
        {

            $map['id'] = $fid;
        }

        //获取到项目发起人的id
        $mid_user = M('fundings')->where($map)->getField('mid');
        //获取该项目的项目名
        $pjname = M('fundings')->where($map)->getField('pjname');
        //获取到所有参与项目用户的id
        $fundings_sponsor = M('fundings_sponsor');

        $mid = $fundings_sponsor->where(array('fid' => $fid))->getField('mid',true);
        //将发起者id和参与者id合并在一个数组里
        $mid[] = $mid_user;

        // print_r($mid); die;
        //获取到所有用户的device_token
        if(!empty($mid))
        {

            $members = M('members');
            foreach ($mid as $k => $v) 
            {
                //获取所有安卓用户的device_token
                $device_token_android[$k] = $members->where(array('id' => $v,'from_type' => 1))->getField('device_token');
                if($device_token_android[$k]==NULL)
                {
                    unset($device_token_android[$k]);
                }
                //获取所有ios用户的device_token
                $device_token_ios[$k] = $members->where(array('id' => $v,'from_type' => 2))->getField('device_token');
                if($device_token_ios[$k]==NULL)
                {
                    unset($device_token_ios[$k]);
                }
            }
            // print_r($device_token_android); die;
            //向指定的用户发送通知消息
            import("Org.Util.Sendmessage");

            $ticker = '您参与的项目【'.$pjname.'】众筹已完成，正在抽检!';
            $title = '您参与的项目【'.$pjname.'】众筹已完成，正在抽检!';
            $text = '您参与的项目【'.$pjname.'】众筹已完成，正在抽检!';
            $type = 'go_activity';
            $url = '';
            $custom = $fid;
            $activity = "ProjectInfoActivity";

            $stype = 2;
            //拼接发送消息
            $content  = $this->project_notification($fid,$stype);            

            if(!empty($content))
            {

                $not_data['ctime'] = I('server.REQUEST_TIME');
                $not_data['mtype'] = 1;
                $not_data['ticker'] = $ticker;
                $not_data['title'] = $title;
                $not_data['text'] = $text;
                $not_data['content'] = $content;
                $not_data['fdid'] = $map['id'];
                //消息为项目消息
                $not_data['showtype'] = 1;

                $message_id = M('notification_message')->data($not_data)->add();

                if(!empty($message_id))
                {

                    $ctime = I('server.REQUEST_TIME');
                    foreach ($mid as $k => $v) 
                    {
                        # code...
                        $log_data[] = array('message_id' => $message_id,'mid' => $v,'ctime' => $ctime);
                    }
                    
                    $re = M('notification_message_log')->addAll($log_data);
                    // echo $device_token_android; die;
                    if(!empty($re))
                    {
                        if(!empty($device_token_android))
                        {
                            $device_token = implode(',',$device_token_android);
                            // echo $device_token; die;
                             $Sendmessage = new \Sendmessage($this->pr_appkey,$this->pr_secret,$device_token,$ticker,$title,$text,$type,$url,$custom,$activity);
                             $result = $Sendmessage->sendAndroidListcast();
                        }elseif(!empty($device_token_ios))
                        {
                            $device_token = implode(',',$device_token_ios);
                            // echo $device_token; die;
                            $Sendmessage = new \Sendmessage($this->ios_appkey,$this->ios_secret,$device_token,$ticker,$title,$text,$type,$url,$custom,$activity);
                            $result = $Sendmessage->sendIOSListcast();
                        }else{
                            echo 'success';
                        }
                       
                    }
                }
            }
            // echo $result;   
        }

    }


    //众筹项目完成后向所有参与的用户发起通知
    public function sendAndroidmessage_pass_three($id)
    {
        // $fid = I('get.id');
        $fid = $id;
        // echo $fid;die;
        if(!empty($fid))
        {

            $map['id'] = $fid;
        }
        //获取到项目发起人的id
        $mid_user = M('fundings')->where($map)->getField('mid');
        //获取该项目的项目名
        $pjname = M('fundings')->where($map)->getField('pjname');
        //获取检测结果
        $result_fundings = M('fundings')->where($map)->getField('result');

        if($result_fundings == 1){

            $result_fundings = '合格';
        }else{

            $result_fundings = '不合格';
        }

        //获取到所有参与项目用户的id
        $fundings_sponsor = M('fundings_sponsor');

        $mid = $fundings_sponsor->where($map)->getField('mid',true);
        //将发起者id和参与者id合并在一个数组里
        $mid[] = $mid_user;
        // print_r($mid); die;
        if(!empty($mid))
        {

            $members = M('members');
            foreach ($mid as $k => $v) 
            {
                //获取所有安卓用户的device_token
                $device_token_android[$k] = $members->where(array('id' => $v,'from_type' => 1))->getField('device_token');
                if($device_token_android[$k]==NULL)
                {
                    unset($device_token_android[$k]);
                }
                //获取所有ios用户的device_token
                $device_token_ios[$k] = $members->where(array('id' => $v,'from_type' => 2))->getField('device_token');
                if($device_token_ios[$k]==NULL)
                {
                    unset($device_token_ios[$k]);
                }
            }
            
            // echo $device_token; die;
            //向指定的用户发送通知消息
            import("Org.Util.Sendmessage");

            $ticker = '您发起的项目【'.$pjname.'】检测已完成，结果为:【'.$result_fundings.'】';
            $title = '您发起的项目【'.$pjname.'】检测已完成，结果为:【'.$result_fundings.'】';
            $text = '您发起的项目【'.$pjname.'】检测已完成，结果为:【'.$result_fundings.'】';
            $type = 'go_activity';
            $url = '';
            $custom = $fid;
            $activity = "ProjectInfoActivity";

            $stype = 3;
            //拼接发送消息
            $content  = $this->project_notification($fid,$stype);            
            // echo $content; die;
            if(!empty($content))
            {

                $not_data['ctime'] = I('server.REQUEST_TIME');
                $not_data['mtype'] = 1;
                $not_data['ticker'] = $ticker;
                $not_data['title'] = $title;
                $not_data['text'] = $text;
                $not_data['content'] = $content;
                $not_data['fdid'] = $map['id'];
                //消息为项目消息
                $not_data['showtype'] = 1;

                $message_id = M('notification_message')->data($not_data)->add();
                // echo $message_id; die;
                if(!empty($message_id))
                {
                    $ctime = I('server.REQUEST_TIME');
                    // print_r($mid); die;
                    foreach ($mid as $k => $v) 
                    {
                        # code...
                        $log_data[] = array('message_id' => $message_id,'mid' => $v,'ctime' => $ctime);
                    }
                    // print_r($log_data);
                    $re = M('notification_message_log')->addAll($log_data);
                    // echo $re; die;
                    if(!empty($re))
                    {
                        if(!empty($device_token_android))
                        {
                            // echo 123; die;
                            $device_token = implode(',',$device_token_android);
                            // echo $device_token; die;
                            $Sendmessage = new \Sendmessage($this->pr_appkey,$this->pr_secret,$device_token,$ticker,$title,$text,$type,$url,$custom,$activity);
                            $result = $Sendmessage->sendAndroidListcast();
                        }else if(!empty($device_token_ios))
                        {
                            // echo 456; die;
                            $device_token = implode(',',$device_token_ios);
                            $Sendmessage = new \Sendmessage($this->ios_appkey,$this->ios_secret,$device_token,$ticker,$title,$text,$type,$url,$custom,$activity);
                            $result = $Sendmessage->sendIOSListcast();
                        }else{
                            echo 'success';
                        }
                    }
                }
            }
        }

    }



    //自动拼接消息
    public function project_notification($fid,$stype)
    {

        $list = M('fundings')->where(array('id' => $fid))->find();
        switch ($stype) 
        {
            case 1:
                $content = "尊敬的用户，您好！您发起的项目已经通过审核<br/>项目名称:".$list['pjname']."<br/>产品名:".$list['pdname']."<br/>检测机构:".$list['institutionid']."<br/>需求资金:".$list['emoney']."<br/>发起时间:".$list['btime']."<br/>结束时间:".$list['etime'];
                break;
             case 1:
                $content = "尊敬的用户，您好！您参与的项目已完成众筹<br/>项目名称:".$list['pjname']."<br/>产品名:".$list['pdname']."<br/>检测机构:".$list['institutionid']."<br/>需求资金:".$list['emoney']."<br/>发起时间:".$list['btime']."<br/>结束时间:".$list['etime'];
                break;
            default:
                $content = "尊敬的用户，您好！您参与的项目已完成检测<br/>项目名称:".$list['pjname']."<br/>产品名:".$list['pdname']."<br/>检测机构:".$list['institutionid']."<br/>需求资金:".$list['emoney']."<br/>发起时间:".$list['btime']."<br/>结束时间:".$list['etime'];
                break;
        }

        return $content;
    }



    //向数据表写入消息记录
    public function add_message_log($mid,$gid,$message_id,$ctime){

        $notification_message_log = M('notification_message_log');
            
        foreach ($mid as $k => $v) 
        {
            # code...
            $datalist[] = array('mid'=>$v,'gid'=>$gid,'message_id'=>$message_id,'ctime'=>$ctime);
        }

        $re = $notification_message_log->addAll($datalist);

        if(!empty($re))
        {

            return true;
        }
        else
        {
            return false;
        }
    }
    

    
}