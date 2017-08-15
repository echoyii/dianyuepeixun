<?php
namespace M\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function index(){

        
//        广告内容
        $DB = M('adv');
        $map['state']=1;
        $map['ttype']=1;
        
        $advs = $DB->where($map)->order("rank DESC")->select();
        $this->assign('advertisements', $advs);
        $this->assign("adv_counts", count($advs));
        
        //获取热点资讯
        $News_user = M('news');
        $News_map['active'] = 1;
        $news = $News_user->where($News_map)->order("rank DESC")->limit(10)->select();
        
        //获取标签信息
        $DB_tag = M("product_tags");
        $map_tag['active']=1;
        $res_tag = $DB_tag->where($map_tag)->order("rank DESC")->select();
        
        $this->assign("tags", $res_tag);
        
        // print_r($news); die;
        $this->assign('news',$news);
        $this->display();
    }
    /*
    * 临时链接，跳转到 参与众筹 页面
    */
    public function join() {
            header('Location:http://zctx.1mdj.com/index.php/M/Founding/lists/state/1.html');
            
        }
        
    public function news_one_comment() {
//        $res = R("Widget/comment_add");
        $res = R("M/Widget/comment_add",array('pid'=>0,'mid'=>200,'content'=>"3333",'ctype'=>1,'for_id'=>10));
//        $res = $this->comment_add(0, 200, "333", 1, 10);
        if($res)
        {
            $result['err']=0;
        }
        else
        {
            $result['err']=1;
        }
        echo json_encode($result);
    }
    
    /*
     * 获取更多新闻资讯
     */
    public function get_news_by_page() {
        $pagecount = I('get.pagecount');
        if(empty($pagecount))
        {
            $pagecount=0;
        }
        $startcount = $pagecount*10;
        
        $News_user = M('news');
        $News_map['active'] = 1;
        $news = $News_user->where($News_map)->limit($startcount,10)->order("rank DESC")->select();
        
        if(!empty($news))
        {
            $result['res']=$news;
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
     * 广告链接
     */
    public function adv() {
        
        $id = I('get.id');
        // echo $id; die;
        if(!empty($id)){

            $map['id'] = $id;
        }

        $User = M('adv');

        $list = $User->where($map)->find();
        $list['content'] = htmlspecialchars_decode($list['content']);


        $this->assign('list',$list);
        $this->display();
    }
    
    /*
     * 关于我们
     */
    public function aboutus() {
        redirect(U('M/Index/adv',array('id'=>24)));
//        $this->display();
    }
    
    /*
     * 使用须知
     */
    public function faq() {
        $this->display();
    }


    public function update_member(){

        $id = I('post.id');
        if(!empty($id)){

            $map['id'] = $id;
        }

        $lng = I('post.lng');
        if(!empty($lng)){

            $data['lng'] = $lng;
        }

        $lat = I('post.lat');
        if(!empty($lat)){

            $data['lat'] = $lat;
        }

        $address = I('post.address');
        if(!empty($address)){

            $data['address'] = $address;
        }

        $province = I('post.province');
        if(!empty($province)){

            $data['province'] = $province;
        }

        $city = I('post.city');
        if(!empty($city)){

            $data['city'] = $city;
        }

        $district = I('post.district');
        if(!empty($district)){

            $data['district'] = $district;
        }

        $User = M('members');

        $re = $User->where($map)->data($data)->save();

        if(!empty($re)){

            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }
    
    
    /*
     * share ok bg
     * 
     */
    public function sharebg() {
        $this->display();
    }
    
    /*
     * 用户协议
     */
    public function agreement() {
        $this->display();
    }
    
    
}