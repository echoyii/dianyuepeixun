<?php

//搜索页面

namespace M\Controller;
use Think\Controller;

class SearchController extends CommonController {
    public function index() {
        if(IS_GET){
//            $DB_search_tags = M('search_tags');
//            $map['type'] = 0;
//            $tag_list = $DB_search_tags->where($map)->order("rank DESC")->select();
//
//            $this->assign('tag_list',$tag_list);

        $DB = M('fundings');
        $map['state']=array(array("neq",0),array("neq",4));
        
        $res = $DB->where($map)->order("state DESC,result DESC")->select();
        
//        print_r($res);
        
        foreach ($res as $key => $value) {
            $res[$key]['name']= $value['pjname'];
            $res[$key]['isurl']= 0;
            $res[$key]['url']= "";
            $res[$key]['qualified']= 0;
            $res[$key]['show_mark_icon']=  $this->_get_fouding_list_mark(0,$value['result'],$value['state']);
            $res[$key]['location']= $value['location'];
            $res[$key]['testing_date']= date("Y-m-d",$value['ctime']);
            $res[$key]['batch_number']= $value['batch'];
            switch ($value['state']) {
                case 0:
                    $res[$key]['isshow_batch']= 0;

                    break;
                case 1:
                    $res[$key]['isshow_batch']= 0;

                    break;
                case 2:
                    $res[$key]['isshow_batch']= 0;

                    break;
                case 3:
                    $res[$key]['isshow_batch']= 1;

                    break;

                default:
                    $res[$key]['isshow_batch']= 0;
                    break;
            }
        }
        
//        print_r($DB->getLastSql());
//        print_r($res);
        
        
        $count_res = count($res);
        
        $res_thirdparty = $this->get_thirdparty_product_by_name_for_index(0,20);
        
        if($res_thirdparty)
        {
            foreach ($res_thirdparty as $key => $value) {
                $res[$count_res+$key]['name'] = $value['product_name'];
                $res[$count_res+$key]['id'] = 0;
                $res[$count_res+$key]['isurl']= 1;
                $res[$count_res+$key]['url']= C('product_domain').U('M/Founding/show_thirdparty_data',array('id'=>$value['id']));
                $res[$count_res+$key]['qualified']= (int)$value['qualified'];
                $res[$count_res+$key]['show_mark_icon']=  $this->_get_fouding_list_mark(1,$value['qualified'],3);
                $res[$count_res+$key]['location']= $value['province'];
                $res[$count_res+$key]['testing_date']= $value['notification_number'];
                $res[$count_res+$key]['batch_number']= $value['cdate'];
                $res[$count_res+$key]['isshow_batch']= 1;
                
            }
        }
                
        if(!empty($res))
        {
            foreach ($res as $key => $value) {
                if($value['id'])
                {
                    $relative_foundings[$key] = $this->get_product_foundings($value['id']);
                }
                else
                {
                    $relative_foundings[$key] = 0;
                }
                
                
                if(!empty($relative_foundings[$key]))
                {
                    $res[$key]['item_count']= count($relative_foundings[$key]);
                    $res[$key]['items']= ($relative_foundings[$key]);
                }
                else
                {
                    $res[$key]['item_count']= 0;
                    $res[$key]['items']= array();
                }

                
            }
   
        }
        $this->assign('results', $res);
            $this->display();
        }
    }
    
    public function index_by_page() {
        $pagecount = I('get.pagecount');
        
        $startposition = ($pagecount)*10;
        
//        $res = $DB->where($map)->select();
        
//        foreach ($res as $key => $value) {
//            $res[$key]['name']= $value['pjname'];
//            $res[$key]['isurl']= 0;
//            $res[$key]['url']= "";
//            $res[$key]['qualified']= 0;
//            $res[$key]['show_mark_icon']=  $this->_get_fouding_list_mark(0,$value['result'],$value['state']);
//            $res[$key]['location']= $value['qualified'];
//            $res[$key]['testing_date']= date("Y-m-d",$value['ctime']);
//            $res[$key]['batch_number']= $value['batch'];
//        }
        
        
//        $count_res = count($res);
        $count_res=0;
        $res_thirdparty = $this->get_thirdparty_product_by_name_for_index($startposition,20);
        
        if($res_thirdparty)
        {
            foreach ($res_thirdparty as $key => $value) {
                $res[$count_res+$key]['name'] = $value['product_name'];
                $res[$count_res+$key]['id'] = 0;
                $res[$count_res+$key]['isurl']= 1;
                $res[$count_res+$key]['url']= C('product_domain').U('M/Founding/show_thirdparty_data',array('id'=>$value['id']));
                $res[$count_res+$key]['qualified']= (int)$value['qualified'];
                $res[$count_res+$key]['show_mark_icon']=  $this->_get_fouding_list_mark(1,$value['qualified'],3);
                $res[$count_res+$key]['location']= $value['province'];
                $res[$count_res+$key]['testing_date']= $value['notification_number'];
                $res[$count_res+$key]['batch_number']= $value['cdate'];
                $res[$count_res+$key]['isshow_batch']= 1;
            }
        }
                
        if(!empty($res))
        {
            foreach ($res as $key => $value) {
                if($value['id'])
                {
                    $relative_foundings[$key] = $this->get_product_foundings($value['id']);
                }
                else
                {
                    $relative_foundings[$key] = 0;
                }
                
                
                if(!empty($relative_foundings[$key]))
                {
                    $res[$key]['item_count']= count($relative_foundings[$key]);
                    $res[$key]['items']= ($relative_foundings[$key]);
                }
                else
                {
                    $res[$key]['item_count']= 0;
                    $res[$key]['items']= array();
                }

                
            }
            
            $result['res']=$res;
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
     * Date： 2015-10-19
     * Des： 搜索结果列表
     */

    public function lists() {
        $keywords = I('get.keywords');

        $DB = M('fundings');
        $map['pdname']=array('like',"%$keywords%");
        $map['state']=array(array("neq",0),array("neq",4));
        
        $res = $DB->where($map)->order("state DESC,result DESC")->select();
        
//        print_r($res);
        
        foreach ($res as $key => $value) {
            $res[$key]['name']= $value['pjname'];
            $res[$key]['isurl']= 0;
            $res[$key]['url']= "";
            $res[$key]['qualified']= 0;
            $res[$key]['show_mark_icon']=  $this->_get_fouding_list_mark(0,$value['result'],$value['state']);
            $res[$key]['location']= $value['location'];
            $res[$key]['testing_date']= date("Y-m-d",$value['ctime']);
            $res[$key]['batch_number']= $value['batch'];
            switch ($value['state']) {
                case 0:
                    $res[$key]['isshow_batch']= 0;

                    break;
                case 1:
                    $res[$key]['isshow_batch']= 0;

                    break;
                case 2:
                    $res[$key]['isshow_batch']= 0;

                    break;
                case 3:
                    $res[$key]['isshow_batch']= 1;

                    break;

                default:
                    $res[$key]['isshow_batch']= 0;
                    break;
            }
        }
        
//        print_r($DB->getLastSql());
//        print_r($res);
        
        
        $count_res = count($res);
        
        $res_thirdparty = $this->get_thirdparty_product_by_name($keywords,0,20);
        
        if($res_thirdparty)
        {
            foreach ($res_thirdparty as $key => $value) {
                $res[$count_res+$key]['name'] = $value['product_name'];
                $res[$count_res+$key]['id'] = 0;
                $res[$count_res+$key]['isurl']= 1;
                $res[$count_res+$key]['url']= C('product_domain').U('M/Founding/show_thirdparty_data',array('id'=>$value['id']));
                $res[$count_res+$key]['qualified']= (int)$value['qualified'];
                $res[$count_res+$key]['show_mark_icon']=  $this->_get_fouding_list_mark(1,$value['qualified'],3);
                $res[$count_res+$key]['location']= $value['province'];
                $res[$count_res+$key]['testing_date']= $value['notification_number'];
                $res[$count_res+$key]['batch_number']= $value['cdate'];
                $res[$count_res+$key]['isshow_batch']= 1;
                
            }
        }
                
        if(!empty($res))
        {
            foreach ($res as $key => $value) {
                if($value['id'])
                {
                    $relative_foundings[$key] = $this->get_product_foundings($value['id']);
                }
                else
                {
                    $relative_foundings[$key] = 0;
                }
                
                
                if(!empty($relative_foundings[$key]))
                {
                    $res[$key]['item_count']= count($relative_foundings[$key]);
                    $res[$key]['items']= ($relative_foundings[$key]);
                }
                else
                {
                    $res[$key]['item_count']= 0;
                    $res[$key]['items']= array();
                }

                
            }
   
        }
        $this->assign("keywords", $keywords);
        $this->assign('results', $res);
//        print_r($res);
         $this->display();
        
        
    }
    
    
    
    public function list_by_page() {
        $keywords = I('get.keywords');
        $pagecount = I('get.pagecount');
        
        $startposition = ($pagecount)*10;
        
        $DB = M('fundings');
        $map['pdname']=array('like',"%$keywords%");
        $map['state']=array(array("neq",0),array("neq",4));
//        $res = $DB->where($map)->select();
        
//        foreach ($res as $key => $value) {
//            $res[$key]['name']= $value['pjname'];
//            $res[$key]['isurl']= 0;
//            $res[$key]['url']= "";
//            $res[$key]['qualified']= 0;
//            $res[$key]['show_mark_icon']=  $this->_get_fouding_list_mark(0,$value['result'],$value['state']);
//            $res[$key]['location']= $value['qualified'];
//            $res[$key]['testing_date']= date("Y-m-d",$value['ctime']);
//            $res[$key]['batch_number']= $value['batch'];
//        }
        
        
//        $count_res = count($res);
        $count_res=0;
        $res_thirdparty = $this->get_thirdparty_product_by_name($keywords,$startposition,20);
        
        if($res_thirdparty)
        {
            foreach ($res_thirdparty as $key => $value) {
                $res[$count_res+$key]['name'] = $value['product_name'];
                $res[$count_res+$key]['id'] = 0;
                $res[$count_res+$key]['isurl']= 1;
                $res[$count_res+$key]['url']= C('product_domain').U('M/Founding/show_thirdparty_data',array('id'=>$value['id']));
                $res[$count_res+$key]['qualified']= (int)$value['qualified'];
                $res[$count_res+$key]['show_mark_icon']=  $this->_get_fouding_list_mark(1,$value['qualified'],3);
                $res[$count_res+$key]['location']= $value['province'];
                $res[$count_res+$key]['testing_date']= $value['notification_number'];
                $res[$count_res+$key]['batch_number']= $value['cdate'];
                $res[$count_res+$key]['isshow_batch']= 1;
            }
        }
                
        if(!empty($res))
        {
            foreach ($res as $key => $value) {
                if($value['id'])
                {
                    $relative_foundings[$key] = $this->get_product_foundings($value['id']);
                }
                else
                {
                    $relative_foundings[$key] = 0;
                }
                
                
                if(!empty($relative_foundings[$key]))
                {
                    $res[$key]['item_count']= count($relative_foundings[$key]);
                    $res[$key]['items']= ($relative_foundings[$key]);
                }
                else
                {
                    $res[$key]['item_count']= 0;
                    $res[$key]['items']= array();
                }

                
            }
            
            $result['res']=$res;
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
     * Date:2015-11-30
     * 获取tag下的产品结果首页
     */
    public function tag() {
        $tagid = I('get.tagid');
        $tag_name = I('get.tag_name');
        $pagecount  = I('get.pagecount');
//        获取改tagid下的所有产品信息   
        if(empty($pagecount))
        {
            $pagecount=0;
        }
        $startcount = $pagecount*10;
        
        $DB_tag=M("product_tags_relationship");
        $map_tag['tagid']=$tagid;
        
        $res_tag = $DB_tag->where($map_tag)->limit($startcount,10)->select(); 
        
        
        
        if(!empty($res_tag))
        {
            foreach ($res_tag as $key => $value) {
                
                
                    $res_tag[$key]['pinfo']=  $this->get_third_party_info_by_id($value['pid']);
                    
                    $res_tag[$key]['name'] = $res_tag[$key]['pinfo']['product_name'];
                    $res_tag[$key]['id'] = 0;
                    $res_tag[$key]['isurl']= 1;
                    $res_tag[$key]['url']= C('product_domain').U('M/Founding/show_thirdparty_data',array('id'=>$res_tag[$key]['pinfo']['id']));
                    $res_tag[$key]['qualified']= (int)$res_tag[$key]['pinfo']['qualified'];
                    $res_tag[$key]['show_mark_icon']=  $this->_get_fouding_list_mark(1,$res_tag[$key]['pinfo']['qualified'],3);
                    $res_tag[$key]['location']= $res_tag[$key]['pinfo']['province'];
                    $res_tag[$key]['testing_date']= $res_tag[$key]['pinfo']['notification_number'];
                    $res_tag[$key]['batch_number']= $res_tag[$key]['pinfo']['cdate'];
                    $res_tag[$key]['isshow_batch']= 1;
                    
                    

                }
        }
        
        $this->assign('results', $res_tag);
        
        $this->assign("tagid", $tagid);
        if($pagecount>0)
        {
            if(!empty($res_tag)){
                
            $result['res']=$res_tag;
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
        else
        {
            $this->assign('tag_name', $tag_name);
            $this->display();
        }
        
    }
    
    /*
     * Date:2015-11-30
     * 获取tag下的产品结果列表
     */
    public function tag_search_result_list() {
        $keywords = I('get.keywords');
        $tagid = I('get.tagid');
        $tag_name = I('get.tag_name');
        
        /*
         * 找出tagid 对应的pid，和找出来的结果做对比
         */
        $DB_tag=M("product_tags_relationship");
        $map_tag['tagid']=$tagid;
        
        $res_tag = $DB_tag->where($map_tag)->select(); 
        
//        print_r($res_tag);
       
        $DB = M('fundings');
        $map['pdname']=array('like',"%$keywords%");
        
        $res = $DB->where($map)->select();
        
        foreach ($res as $key => $value) {
            $res[$key]['name']= $value['pjname'];
            $res[$key]['isurl']= 0;
            $res[$key]['url']= "";
            $res[$key]['qualified']= 0;
            $res[$key]['show_mark_icon']=  $this->_get_fouding_list_mark(0,$value['result'],$value['state']);
            $res[$key]['location']= $value['qualified'];
            $res[$key]['testing_date']= date("Y-m-d",$value['ctime']);
            $res[$key]['batch_number']= $value['batch'];
            
            switch ($value['state']) {
                case 0:
                    $res[$key]['isshow_batch']= 0;

                    break;
                case 1:
                    $res[$key]['isshow_batch']= 0;

                    break;
                case 2:
                    $res[$key]['isshow_batch']= 0;

                    break;
                case 3:
                    $res[$key]['isshow_batch']= 1;

                    break;

                default:
                    $res[$key]['isshow_batch']= 0;
                    break;
            }
            
        }
        
//        print_r($DB->getLastSql());
//        print_r($res);
        
        
        $count_res = count($res);
        
        $res_thirdparty = $this->get_thirdparty_product_by_name($keywords,0,10000);
        
//        print_r($res_thirdparty);
//        exit();
        
        if(!empty($res_tag))
        {
            foreach ($res_tag as $k => $v) {
                $tmp_pid_array[]=$v['pid'];
            }
            
//            print_r($tmp_pid_array);
//            exit();
            foreach ($res_thirdparty as $key => $value) {
                if(in_array($value['id'], $tmp_pid_array))
                {
                    $new_res_thirdparty[]=$value;
                }
            }
            
//            print_r($new_res_thirdparty);
//            exit();
            
            
            if($new_res_thirdparty)
            {
                
                foreach ($new_res_thirdparty as $key => $value) {
                    $res[$count_res+$key]['name'] = $value['product_name'];
                    $res[$count_res+$key]['id'] = 0;
                    $res[$count_res+$key]['isurl']= 1;
                    $res[$count_res+$key]['url']= C('product_domain').U('M/Founding/show_thirdparty_data',array('id'=>$value['id']));
                    $res[$count_res+$key]['qualified']= (int)$value['qualified'];
                    $res[$count_res+$key]['show_mark_icon']=  $this->_get_fouding_list_mark(1,$value['qualified'],3);
                    $res[$count_res+$key]['location']= $value['province'];
                    $res[$count_res+$key]['testing_date']= $value['notification_number'];
                    $res[$count_res+$key]['batch_number']= $value['cdate'];
                    $res[$count_res+$key]['isshow_batch']= 1;
                }
            }
        }
        
        
                
        if(!empty($res))
        {
            foreach ($res as $key => $value) {
                if($value['id'])
                {
                    $relative_foundings[$key] = $this->get_product_foundings($value['id']);
                }
                else
                {
                    $relative_foundings[$key] = 0;
                }
                
                
                if(!empty($relative_foundings[$key]))
                {
                    $res[$key]['item_count']= count($relative_foundings[$key]);
                    $res[$key]['items']= ($relative_foundings[$key]);
                }
                else
                {
                    $res[$key]['item_count']= 0;
                    $res[$key]['items']= array();
                }

                
            }
   
        }
        $this->assign("tagid", $tagid);
        $this->assign('results', $res);
        $this->assign('tag_name', $tag_name);
        $this->display();
    }
    
    public function get_thirdparty_product_by_name($keywords,$offset,$number) {
        import("ORG.Util.Pinyin");
        $py = new \PinYin();
        
        $result = $py->getAllPY("$keywords",',');
        
        $keywords_pys = str_replace(',', ' +', $result);
        $keywords_pys ='+'.$keywords_pys;
        
        $Model = new \Think\Model();
        $res = $Model->query("select * from fd_product_thirdparty where product_name LIKE '%".$keywords."%' AND match(product_name_pinyin) against('".$keywords_pys."' IN BOOLEAN MODE) order by qualified limit $offset,$number");
        
        return $res;
    }
    /*
     * 首页进入关键字为空时候的数据
     */
    public function get_thirdparty_product_by_name_for_index($offset,$number) {

        
        $Model = new \Think\Model();
        $res = $Model->query("select * from fd_product_thirdparty order by id DESC limit $offset,$number");
        
        return $res;
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
                return "img/testing_mark.png";

                break;

            default:
                return "img/testing_mark.png";
                break;
        }
        
    }
}