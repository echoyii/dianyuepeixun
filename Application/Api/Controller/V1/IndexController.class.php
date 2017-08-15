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
* @global 指明在此函数中引用的全局变量
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
 * APP 获取到的杂七杂八的功能
 */
class IndexController extends CommonController{
    public function index(){
        header("Content-type:text/html;charset=utf8;");
        echo "众测天下";
    }
    
    
    /*
     * 首页广告列表
     */
    public function ad_lists() {
        $DB = M('adv');
        $map['state']=1;
        $map['ttype']=2;
        
        $res = $DB->where($map)->order("rank DESC")->select();
        
        /*
         * 如果是项目信息，获取fdid状态
         * 
         */
        foreach ($res as $key => $value) {
            if($value['type']==0)
            {
                $res[$key]['fd_state']=$this->_get_state_by_fdid($value['fdid']);
            }
            else
            {
                $res[$key]['fd_state']=0;
            }
            
        }
        $this->json_output($res);
    }
    
    
    /*
     * 广告详情（单单是数据展示的一种）
     * 没有包括 H5 页面、跳转到指定项目这些
     */
    public function ad_detail() {
        $adid = I('get.adid');  //获取广告id
        
        $DB = M('adv');
        $res = $DB->find($adid);
        $this->json_output($res);
        
    }
    
    /*
     * 获取首页的热点资讯内容
     */
    public function latest_news() {
        $pagecount = I('pagecount');
        
        if(empty($pagecount))
        {
            $pagecount=0;
        }
        
        $startconnt = $pagecount*10;
        $map['active']=1;
        
        $DB = M("news");
        
        $res = $DB->where($map)->order("ctime DESC")->limit($startconnt,10)->select();
        
        if(!empty($res))
        {
            //判断环境
            if(!C('debug'))
            {
                $site_domain = C('product_domain');
            }
            else
            {
                $site_domain = C('development_domain');
            }

            foreach ($res as $key => $value) {
                $res[$key]['url']=$site_domain.U('M/News/details_for_app',array('newsid'=>$value['id']));
            }
            $this->json_output($res);
        }
        else
        {
            $result['err']=1;
            $result['msg']="暂无新数据";
            $result['content']="";
            echo json_encode($result);
        }
        
        
    }
    
    /*
     * 获取指定的热点资讯
     */
    public function news_get_one() {
        $newsid = I('get.newsid');
        
        $DB = M("news");
        
        $res = $DB->find($newsid);
        
        $this->json_output($res);
        
    }
    
    /*
     * 获取指定热点资讯的分享资料
     */
    public function get_share_of_news() {
        $newsid = I('get.newsid');
        
        $DB = M("news");
        
        $res = $DB->find($newsid);
        
        //判断环境
        if(!C('debug'))
        {
            $site_domain = C('product_domain');
        }
        else
        {
            $site_domain = C('development_domain');
        }
        
        $share['title']=$res['title'];
//        $share['desc']=$res['title'];
        $share['desc']="食品安全靠大家，一步一步更安全，我觉得这个信息很实用……";
        $share['link']=$site_domain.U('M/News/details_for_share',array('id'=>$newsid));
        $share['imgUrl']=$site_domain."/Public/assets/img/logo.png";

        $this->json_output($share, '成功', '失败');
    }
    
    /*
     * 获取标签信息
     */
    public function tags() {
//        仅仅获取4个
        $DB = M('product_tags');
        $map['active']=1;
        
        $res = $DB->where($map)->select();
        
        $this->json_output($res);
        
    }
    
    
    /*
     * 获取项目的state
     */
    private function _get_state_by_fdid($fdid) {
        $res = M('fundings')->find($fdid);
        return $res['state'];
        
    }


}
