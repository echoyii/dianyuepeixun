<?php
namespace Api\Controller\V1;
use Think\Controller;

class ProductController extends CommonController {
    public function index(){
        header("Content-type:text/html;charset=utf8;");
        echo "众测天下";
    }
    
    /*
     * 产品查询 by 条形码
     * 通过条形码，先去本地的数据库找一下
     */
    public function get_info_by_barcode() {
        $barcode = I('get.barcode');
        $mid = I('get.mid');
        
        $DB = M('product');
        $map['barcode']=$barcode;
        
        $res = $DB->where($map)->find();
        
        if(!empty($res))
        {
            $relative_foundings = $this->get_product_foundings($res['id']);
            if(!empty($relative_foundings))
            {
                $res['item_count']= count($relative_foundings);
                $res['items']= ($relative_foundings);
            }
            else
            {
                $res['item_count']= 0;
                $res['items']=array();
            }
            
            $res['items']= ($relative_foundings);
            $this->json_output($res);
            
        }
        else
        {
            $ress = $this->get_info_product_juhe($barcode);
            if(!empty($ress)){
               
                $this->json_output($ress);
            }
            else
            {
                $data=array();
                $this->json_output($data,'ok','聚合API无输出');
            }
            
        }
        
        
    }
    
    /*
     * 新增一个来测试第三方库的丰富度
     */
    public function get_info_by_barcode_new_test() {
        $barcode = I('get.barcode');
        $mid = I('get.mid');
        
        $DB = M('product_copy');
        $map['barcode']=$barcode;
        
        $res = $DB->where($map)->find();
        
        if(!empty($res))
        {
            $relative_foundings = $this->get_product_foundings($res['id']);
            if(!empty($relative_foundings))
            {
                $res['item_count']= count($relative_foundings);
                $res['items']= ($relative_foundings);
            }
            else
            {
                $res['item_count']= 0;
                $res['items']=array();
            }
            
            $res['items']= ($relative_foundings);
            $this->json_output($res);
            
        }
        else
        {
            $ress = $this->get_info_product_yiyuan($barcode);
            if(!empty($ress)){
               
                $this->json_output($ress);
            }
            else
            {
                $data=array();
                $this->json_output($data,'ok','易源API无输出');
            }
            
        }
        
        
    }
    
    public function get_info_by_barcode_from_android() {
        $barcode = I('get.barcode');
        $mid = I('get.mid');
        
        $DB = M('product');
        $map['barcode']=$barcode;
        
        $res = $DB->where($map)->find();
        
        if(!empty($res))
        {
            $relative_foundings = $this->get_product_foundings($res['id']);
            if(!empty($relative_foundings))
            {
                $res['item_count']= count($relative_foundings);
                $res['items']= ($relative_foundings);
            }
            else
            {
                $res['item_count']= 0;
                $res['items']=array();
            }
            
            $res['items']= ($relative_foundings);
            $this->json_output($res);
            
        }
        else
        {
            $ress = $this->get_info_product_juhe($barcode);
            if(!empty($ress)){
               
                $this->json_output($ress);
            }
            else
            {
                $res['err']=0;
                $res['msg']='暂无数据(server)';
                $res['count']=0;
                $res['content']='';
                
            echo json_encode($res);
//                $data="";
//                $this->json_output($data,'ok','聚合API无输出');
            }
            
        }
        
        
    }

    // public function ceshi(){

    //     $barcode = I('get.barcode');
    //     $res = $this->get_info_product_juhe($barcode);

    //     print_r($res);
    // }

    //从远程聚合数据获取到条形码产品数据
    private function get_info_product_juhe($barcode){
        //包名
        $pkg = 'com.mdkkj.zhongcetianxia';
        //appkey
        $appkey = '71588de5d4c2b919a8fd432d39912317';
        //拼接聚合get请求链接
        $url = 'http://api.juheapi.com/jhbar/bar?appkey='.$appkey.'&pkg='.$pkg.'&barcode='.$barcode.'&cityid=1';

        $list = file_get_contents($url);
        //将list由返回的json格式转换为数组;
        $list = json_decode($list,true);

        if($list['error_code']==0){

            $data['name'] = $list['result']['summary']['name'];
            $data['imgurl'] = $list['result']['summary']['imgurl'];
            $data['barcode'] = $list['result']['summary']['barcode'];
            $data['ctime'] = I('server.REQUEST_TIME');

            $id = M('product')->data($data)->add();

            if(!empty($id)){

                $res = M('product')->find($id);
                $res['item_count']= 0;
                $res['items']=array();

            }else{

                return false;
            }

            return $res;

        }else{

            return false;
        }
    }
    
    //通过 易源 API 来获取数据
    private function get_info_product_yiyuan($barcode){
        //包名
        $pkg = 'com.mdkkj.zhongcetianxia';
        //appkey 
       $appkey = '71588de5d4c2b919a8fd432d39912317';
        //拼接聚合get请求链接
//        $url = 'http://api.juheapi.com/jhbar/bar?appkey='.$appkey.'&pkg='.$pkg.'&barcode='.$barcode.'&cityid=1';
        
        $timestamp = date("YmdHis",I("server.REQUEST_TIME"));
        
        $url = "http://route.showapi.com/66-22?code=$barcode&showapi_appid=12020&showapi_timestamp=$timestamp&showapi_sign=ff003a5c3fb3411c9ad3839365b5df49";
//        print_r($url);
        $list = file_get_contents($url);
//        print_r($list);
        //将list由返回的json格式转换为数组;
        $list = json_decode($list,true);
//        print_r($list);
        
        if($list['showapi_res_code']==0)
        {
            $data['name'] = $list['showapi_res_body']['goodsName'];
            $data['imgurl'] = $list['showapi_res_body']['img'];
            $data['barcode'] = $barcode;
            $data['ctime'] = I('server.REQUEST_TIME');

            $id = M('product_copy')->data($data)->add();

            if(!empty($id)){

                $res = M('product_copy')->find($id);
                $res['item_count']= 0;
                $res['items']=array();

            }else{

                return false;
            }

            return $res;
        }
        else
        {
            return false;
        }

//        if($list['error_code']==0){
//
//            $data['name'] = $list['result']['summary']['name'];
//            $data['imgurl'] = $list['result']['summary']['imgurl'];
//            $data['barcode'] = $list['result']['summary']['barcode'];
//            $data['ctime'] = I('server.REQUEST_TIME');
//
//            $id = M('product')->data($data)->add();
//
//            if(!empty($id)){
//
//                $res = M('product')->find($id);
//                $res['item_count']= 0;
//                $res['items']=array();
//
//            }else{
//
//                return false;
//            }
//
//            return $res;
//
//        }else{
//
//            return false;
//        }
    }
    
    
    /*
     * 产品查询 by 关键字，得到一个list
     */
    public function get_info_by_keywords() {
        $mid = I('get.mid');
        $keywords = trim(I('get.keywords'));
        $keywords_py  = trim(I('get.keywords_py'));
        $DB = M('fundings');
        $map['pdname']=array('like',"%$keywords%");
        //过滤掉未审核的和未支付的订单
        $map['state']=array('neq',0);
        
        $res = $DB->where($map)->order("ctime DESC,state DESC,result DESC")->select();
        foreach ($res as $key => $value) {
            $res[$key]['name']=$value['pjname'];
            $res[$key]['isurl']= 0;
            $res[$key]['url']= "";
            $res[$key]['qualified']= 0;
            $res[$key]['testing_date']= date("Y-m-d",$value['ctime']);
            $res[$key]['batch_number']= $value['batch'];
            $res[$key]['packagename']= $this->get_name_by_packageid($value['packageid']);
        }          
        $count_res = count($res);
        
        $res_thirdparty = $this->get_thirdparty_product_by_name($keywords_py,$keywords,0,20);
        
        if($res_thirdparty)
        {
            foreach ($res_thirdparty as $key => $value) {
                $res[$count_res+$key]['name'] = $value['product_name'];
                $res[$count_res+$key]['id'] = 0;
                $res[$count_res+$key]['isurl']= 1;
                $res[$count_res+$key]['url']= C('product_domain').U('M/Founding/show_thirdparty_data_of_app',array('id'=>$value['id']));
                $res[$count_res+$key]['qualified']= (int)$value['qualified'];
                $res[$count_res+$key]['location']= $value['province'];
                $res[$count_res+$key]['testing_date']= $value['notification_number'];
                $res[$count_res+$key]['batch_number']= $value['cdate'];
            }
        }
        if(!empty($res))
        {
            $this->json_output($res);
        }
        else
        {
            $this->json_output(0);
        }
    }
    
    /*
     * 分页数据
     */
    public function get_info_by_keywords_with_page() {
        $mid = I('get.mid');
        $keywords = trim(I('get.keywords'));
        $keywords_py  = trim(I('get.keywords_py'));
        
        $pagecount = I('get.pagecount');
        if(empty($pagecount))
        {
            $pagecount=0;
        }
        
        $startposition = ($pagecount)*10;

        $res_thirdparty = $this->get_thirdparty_product_by_name($keywords_py,$keywords,$startposition,10);
        
        if($res_thirdparty)
        {
            foreach ($res_thirdparty as $key => $value) {
                $res[$key]['name'] = $value['product_name'];
                $res[$key]['id'] = 0;
                $res[$key]['isurl']= 1;
                $res[$key]['url']= C('product_domain').U('M/Founding/show_thirdparty_data_of_app',array('id'=>$value['id']));
                $res[$key]['qualified']= (int)$value['qualified'];
                $res[$key]['location']= $value['province'];
                $res[$key]['testing_date']= $value['notification_number'];
                $res[$key]['batch_number']= $value['cdate'];
            }
        }
        if(!empty($res))
        {
            $this->json_output($res);
        }
        else
        {
            $this->json_output(0);
        }
    }
    
    
    /*
     * 产品查询 by id
     */
    public function get_info_by_pid() {
        $pid = I('get.pid');
        $mid = I('get.mid');
        
        $DB = M('product');
        
        $res = $DB->find($pid);
        
        if(!empty($res))
        {
            $relative_foundings = $this->get_product_foundings($res['id']);
            if(!empty($relative_foundings))
            {
                $res['item_count']= count($relative_foundings);
                $res['items']= ($relative_foundings);
            }
            else
            {
                $res['item_count']= 0;
                $res['items']= array();
            }   
            
            
            $this->json_output($res);
        }
        else
        {
            $this->json_output(0);
        }
    }
    
    
    /*
     * 找出该产品是否有对应的检测项目
     */
    
    private function get_product_foundings($pid) {
        $DB= M('fundings');
        
        $map['pid']=$pid;
        $res = $DB->where($map)->select();
        if(!empty($res))
        {
            return $res;
        }
        else
        {
            return 0;
        }
        
    }
    
    /*
     * 找出关键字产品在第三方抓取的数据库里边的匹配数据
     */
    private function get_thirdparty_product_by_name($keywords_py,$keywords,$offset,$number) {
        
//        if($type==1)
//        {
//            $DB = M('product_thirdparty');
//        
//            $map['product_name'] = array('like',"%$name%");
//
//            $res = $DB->where($map)->select();
//        }
//        else
//        {
            $keywords_pys = str_replace(',', ' +', $keywords_py);
            $keywords_pys ='+'.$keywords_pys;
            $Model = new \Think\Model();
            $res = $Model->query("select * from fd_product_thirdparty where product_name LIKE '%".$keywords."%' AND match(product_name_pinyin) against('".$keywords_pys."' IN BOOLEAN MODE) order by qualified limit $offset,$number");
//            print_r($Model->getLastSql());
            
//        }
        
        if(!empty($res))
        {
            return $res;
        }
        else {
            return FALSE;
        }   
    }
    
    public function testsearch() {
//        $DB = M('product_thirdparty');
//        select * from song where match(singername) against('周杰伦') ;
        $words = trim(I('get.words'));
        $Model = new \Think\Model();
        $res = $Model->query("select * from fd_product_thirdparty where match(product_name_pinyin) against('".$words."')");
        print_r($res);
        
        $name="猪肉";
        $DB = M('product_thirdparty');
        
        $map['product_name'] = array('like',"%$name%");

        $ress = $DB->where($map)->select();
        
        print_r($ress);
        
    }
    
    /*
     * 获取搜索关键字(正常搜索和曝光台）
     * type =0 正常搜索页面
     * type=1 曝光台
     */
    public function get_search_keywords() {
        $mid = I("get.mid");
        $type=I('get.type');
        if(empty($type))
        {
            $type=0;
        }
         $DB_search_tags = M('search_tags');
        $map['type'] = $type;
        $list = $DB_search_tags->where($map)->order("rank DESC")->select();
        
        $this->json_output($list);
    }




    /**
    曝光台产品查询接口
    **/
    //通过关键字查询不合格产品
    public function get_unsafe_info_by_keywords() {
        $mid = I('get.mid');
        $pagecount = I('get.pagecount');
        if(!empty($pagecount)){

            $pagecount = $pagecount*10;
        }else{

            $pagecount = 0;
        }
        $keywords = trim(I('get.keywords'));
        $keywords_py  = trim(I('get.keywords_py'));
        $DB = M('fundings');
        $map['pdname']=array('like',"%$keywords%");
        $map['result'] = 2;
        $map['state'] = 3;
        $res = $DB->where($map)->order('ctime DESC')->limit($pagecount,10)->select();
        foreach ($res as $key => $value) {
            $res[$key]['name']=$value['pjname'];
            $res[$key]['isurl']= 0;
            $res[$key]['url']= "";
            // $res[$key]['qualified']= 0;
            $res[$key]['testing_date']= date("Y-m-d",$value['ctime']);
            $res[$key]['batch_number']= $value['batch'];
            $res[$key]['packagename']= $this->get_name_by_packageid($value['packageid']);
        }          
        $count_res = count($res);
        
        $res_thirdparty = $this->get_unsafe_thirdparty_product_by_name($keywords_py,$keywords,$pagecount);
        // echo json_output($res_thirdparty); die;
        if($res_thirdparty)
        {
            foreach ($res_thirdparty as $key => $value) {
                $res[$count_res+$key]['name'] = $value['product_name'];
                $res[$count_res+$key]['id'] = 0;
                $res[$count_res+$key]['isurl']= 1;
                $res[$count_res+$key]['url']= C('product_domain').U('M/Founding/show_thirdparty_data_of_app',array('id'=>$value['id']));
                // $res[$count_res+$key]['qualified']= (int)$value['qualified'];
                $res[$count_res+$key]['location']= $value['province'];
                $res[$count_res+$key]['testing_date']= $value['notification_number'];
                $res[$count_res+$key]['batch_number']= $value['cdate'];
            }
        }
        if(!empty($res))
        {
            $this->json_output($res);
        }
        else
        {
            $this->json_output(0);
        }
    }



    /**
     * 查询第三方数据中不合格的产品
     */
    
    private function get_unsafe_thirdparty_product_by_name($keywords_py,$keywords,$pagecount) {
        
        $keywords_py = str_replace(',', ' +', $keywords_py);
        $keywords_py ='+'.$keywords_py;
        $Model = new \Think\Model();
        $res = $Model->query("select * from fd_product_thirdparty where product_name LIKE '%".$keywords."%' AND qualified=0 AND match(product_name_pinyin) against('".$keywords_py."' IN BOOLEAN MODE) order by qualified limit ".$pagecount.",10");
        // echo $Model->getLastSql();
        if(!empty($res)){

            return $res;
        }else {
            return FALSE;
        }   
    }
}
