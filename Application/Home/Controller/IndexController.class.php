<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	public function index(){

        $this->display();
	}
        
        public function download() {
            header('Location:http://a.app.qq.com/o/simple.jsp?pkgname=com.zctx.zhongcetianxia');
        }
        
        public function phpinfo() {
            echo phpinfo();
        }
        
        /*
         * 临时链接，跳转到 参与众筹 页面
         */
        public function join() {
            header('Location:http://www.zhongcetianxia.com/index.php/M/Founding/lists/state/1.html');
            
        }




        /*
         * Date:2015-10-19
         * 中文转为拼音
         */
        public function topy() {
            $words = I('get.words');
            import("ORG.Util.Pinyin");
            $py = new \PinYin();
            echo $py->getAllPY("$words",',');
        }
        
        /*
         * Date:2015-10-19
         * 将数据库里边的所有 product_name 都转化为拼音
         * 
         */
        public function translate_all_cn() {
            import("ORG.Util.Pinyin");
            $py = new \PinYin();
            $DB = M('product_thirdparty');
            $map['qualified']=0;
            $res = $DB->where($map)->getField('id,product_name');
            print_r($DB->getLastSql());
            foreach ($res as $key => $value) {
                $DB->id = $key;
                $DB->product_name_pinyin = $py->getAllPY($value,',');
                $DB->save();
                echo $key.'-';
                
            }
        }
        
        /*
         * 获取关注用户列表
         * 
         */
        public function get_subcribe() {
            $jssdk = new \Com\JSSDK(C('appid'),C('appsecret'));
            $accesstoken = $jssdk->returnAccessToken();
        
            $wechat = new \Com\WechatAuth(C('appid'),C('appsecret'),$accesstoken);
            $users = $wechat->userGet();
//            print_r($users['data']['openid']);
            foreach ($users['data']['openid'] as $key => $value) {
                $datalist[]=array('openid'=>$value,'issubscribe'=>1,'ctime'=>I('server.REQUEST_TIME'),'utime'=>I('server.REQUEST_TIME'));
            }
            $DB = M("members_subscribe_records");
            $DB->addAll($datalist);
        }
        
}
?>
