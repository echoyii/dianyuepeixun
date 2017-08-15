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
 * 共用的一些函数方法
 */
class CommonController extends Controller {
    public function index(){
        header("Content-type:text/html;charset=utf8;");
        echo "众测天下";
    }
    
    
    /*
     * 发短信的接口功能
     */
    
//    public function send_sms() {
//        
//    }
    
    /*
     * 检查用户的权限
     */
    public function chk_user($memberid,$password) {
        $map['id']=$memberid;
        $map['password']=$password;
        $member = M('members')->where($map)->find();
//        print_r(M('members')->getLastSql());
        if(!empty($member))
        {
            return $member;
        }
        else
        {
            return FALSE;
        }
    }
    
    /*
     * 统一输出的格式
     */
    public function json_output($data,$suc_msg="OK",$err_msg="NO") {
        if(!empty($data))
        {
            $res['err']=0;
            $res['msg']=$suc_msg;
            $res['count']=count($data);

            $res['content']=$data;
            
        }
        else
        {
            $res['err']=0;
            $res['msg']=$err_msg;
            $res['count']=0;
            $res['content']="";
        }
        
        echo json_encode($res);
    }

    /*
    * date: 2015-10-09
    * des: what to do
     */
    

    /**
        date:   2015-10-09
        des:    获取用户详细资料  传入mid(用户id)
     */
    public function get_information_member($mid){

        $User = M('members');
        $map['id'] = $mid;

        $result = $User->where($map)->find();

        return $result;
    }



    /**
        date: 2015-10-09
        des:  获取众测项目的详细信息  传入fid(项目id)    
     */
    
    public function get_information_fundings($fid){

        $User = M('fundings');
        $map['id'] = $fid;

        $result = $User->where($map)->find();

        return $result;
    }



    /**
        data:   2015-10-09
        des:    获取检测报告  传入tid(检测报告id)
     */
    public function get_information_testreport($tid){

        $User = M('testreport');
        $map['id'] = $tid;

        $result = $User->where($map)->find();

        return $result;
    }



    /**
        data:   2015-10-09
        des:    获取项目所属的用户评论  传入参数 fid(项目id)
     */
    
    public function get_information_fundings_comment($fid){

        $User = M('fundings_comment');
        $map['fdid'] = $fid;

        $result = $User->where($map)->select();

        return $result;
    }




    /**
        date:   2015-10-09
        des:    获取产品的详细信息 传入参数 name(产品名)
     */
    
    public function get_information_product_name($name){

        $User = M('product');

        $map_name = '%'.$name.'%';
        $map['name'] = array('like',$map_name);

        $result = $User->where($map)->select();

        return $result;
    }


    /**
        date:   2015-10-09
        des:    获取产品的详细信息 传入参数 barcode(条形码)
     */
    
    public function get_information_product_barcode($barcode){

        $User = M('product');
        $map['barcode'] = $barcode;

        $result = $User->where($map)->find();

        return $result;
    }



    /**
        date:   2015-10-09
        des:    获取参与项目的所有用户  传入参数fid(项目id)
     */
    
    public function get_information_fundings_sponsor($fid){

        $User = M('fundings_sponsor');
        $map['fid'] = $fid;

        $result = $User->where($map)->select();

        return $result;
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
    
    /*
     * 根据sortid获取分类名称
     */
    protected function _get_name_by_sortid($id) {
        $DB = M('food_classify');
        $res = $DB->find($id);
        return $res['name'];
    }
    
    /*
     * 获取检测机构信息
     */
    protected function get_institution_by_id($id) {
        $res = M('institution')->find($id);
        return $res;
        
    }
    
    /*
     * 获取套餐的信息
     */
    protected function get_package_by_id($id) {
        $res = M('fundings_package')->find($id);
        return $res;
    }
    
        
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
//            echo $name;
        }
        
        
        
        return substr($name, 1);
    }
    

    
}
