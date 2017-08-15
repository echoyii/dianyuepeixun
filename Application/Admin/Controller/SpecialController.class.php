<?php
namespace Admin\Controller;
use Think\Controller;
class SpecialController extends CommonController{
    /*
     * 专题列表页面
     */
    public function special_lists() {
        
        if(!IS_POST){

            $DB_special = M('special');

            $list = $DB_special->where(array('active' => 1))->select();
            foreach ($list as $key => $value) {
                # code...
                $list[$key]['url'] ="http://".I('server.HTTP_HOST').U('M/Special/special',array('id' => $value['id']));
            }
            // print_r($list); die;
            $this->assign('list',$list);
            $this->display();
        }
    }


    /**
     * 添加专题
     */
    public function add_special(){

        if(!IS_POST){

            $this->display();
        }else{

            $name = I('post.name');
            if(!empty($name)){

                $data['name'] = $name;
            }

            $content = I('post.content');
            if(!empty($content)){

                $data['content'] = $content;
                $data['ctime'] = I('server.REQUEST_TIME');
            }

            $DB_special = M('special');
            $result = $DB_special->data($data)->add();
            if(!empty($result)){

                $this->success('添加成功!',U('Admin/Special/special_lists'),1);
            }else{

                $this->error('添加失败!','',1);
            }
        }
    }

    /**
     * 删除专题
     */
    public function del_special(){

        $id = I('post.id');
        if(!empty($id)){

            $map['id'] = $id;
            $data['active'] = 0;
        }

        $DB_special = M('special');
        $result = $DB_special->where($map)->data($data)->save();

        if(!empty($result)){

            echo json_encode(1);
        }else{

            echo json_encode(0);
        }
    }


    /**
     * 修改专题内容
     */
    public function modify_special(){

        if(!IS_POST){

            $id = I('get.id');
            if(!empty($id)){

                $map['id'] = $id;
            }
            
            $DB_special = M('special');

            $list = $DB_special->where($map)->find();

            //对content内容进行实体化
            $list['content'] = htmlspecialchars_decode($list['content']);

            $this->assign('list',$list);
            $this->display();
        }else{

            $id = I('post.id');
            if(!empty($id)){

                $map['id'] = $id;
            }
            $name = I('post.name');
            if(!empty($name)){

                $data['name'] = $name;
            }

            $content = I('post.content');
            if(!empty($content)){

                $data['content'] = $content;
            }

            $DB_special = M('special');
            $result = $DB_special->where($map)->data($data)->save();
            if(!empty($result)){

                $this->success('修改成功!',U('Admin/Special/special_lists'),1);
            }else{

                $this->error('修改失败!','',1);
            }
        }
    }


    /**
     * 绑定产品项目到专题
     */
    
    public function special_bind_fundings(){

        if(!IS_POST){

            $sid = I('get.id');
            if(empty($sid)){

                $this->error('参数出错!','',1);
            }else{

                $this->assign('sid',$sid);
                $this->display();
            }
        }else{
            $sid = I('post.sid');
            if(!empty($sid)){

                $this->assign('sid',$sid);
            }else{

                $this->error('参数出错!','',1);
            }

            $keywords = I('post.keywords');
            if(!empty($keywords)){
                $keywords = trim($keywords);
                $s_keywords = "%".$keywords."%";
                $map['pjname'] = array('like',$s_keywords);
            }

            $DB_fundings = M('fundings');

            $list = $DB_fundings->where($map)->select();
            /**
             * 显示项目状态
             */
            foreach ($list as $k => $v) {
                # code...
                switch ($v['state']) {
                    case 0:
                        $list[$k]['state'] = '待审核';
                    break;
                    case 1:
                        $list[$k]['state'] = '发起中';
                    break;
                    case 2:
                        $list[$k]['state'] = '抽样中';
                    break;
                    case 3:
                        $list[$k]['state'] = '已完成';
                    break;
                    default:
                        # code...
                        $list[$k]['state'] = '异常项目';    
                    break;
                }
                //检测结果
                switch ($v['result']) {
                    case 0:
                        $list[$k]['result'] = '未知';
                    break;
                    case 1:
                        $list[$k]['result'] = '合格';
                    break;
                    default:
                        # code...
                        $list[$k]['result'] = '不合格';    
                    break;
                } 
            }
            $this->assign('list',$list);
            $this->display();
        }
    }


    /**
     * ajax绑定具体产品到对应的项目
     */
    public function bind_funding_to_special(){

        $sid = I('post.sid');
        $fid = I('post.fid');

        if(!empty($sid) && !empty($fid)){


            $re = $this->_chk_special_founding_relationship($sid,$fid);
        }else{

            echo json_encode('参数出错!');
        }

        if(!empty($re)){

            echo json_encode('该项目已存在');
        }else{

            $data['sid'] = $sid;
            $data['fid'] = $fid;
            $DB = M('funding_special_relationship');
            $result = $DB->data($data)->add();

            if(!empty($result)){

                echo json_encode('绑定成功!');
            }
        }

    }


    /*
     * 这里应该还增加一个判断专题和商品是否已经有绑定关系的功能
     * 返回 true or false
     */
    private function _chk_special_founding_relationship($sid,$fid){
        
        $map['sid'] = $sid;
        $map['fid'] = $fid;

        $DB = M('funding_special_relationship');

        $re = $DB->where($map)->find();
        return $re;
    }


    /**
     * 获取专题里所有包含的项目
     */
    
    public function funding_special_lists(){

        if(!IS_POST){

            $id = I('get.id');
            if(!empty($id)){

                $map['sid'] = $id;
            }
            $DB_fse = M('funding_special_relationship');

            $fid_list = $DB_fse->where($map)->getField('fid',true);

            //获取到对应的项目信息
            $DB_fundings = M('fundings');

            foreach ($fid_list as $k => $v) {
                # code...
                $fid_list[$k] = $DB_fundings->where(array('id' => $v))->find();
            }
            /**
             * 显示项目状态
             */
            foreach ($fid_list as $k => $v) {
                # code...
                switch ($v['state']) {
                    case 0:
                        $fid_list[$k]['state'] = '待审核';
                    break;
                    case 1:
                        $fid_list[$k]['state'] = '发起中';
                    break;
                    case 2:
                        $fid_list[$k]['state'] = '抽样中';
                    break;
                    case 3:
                        $fid_list[$k]['state'] = '已完成';
                    break;
                    default:
                        # code...
                        $fid_list[$k]['state'] = '异常项目';    
                    break;
                }
                //检测结果
                switch ($v['result']) {
                    case 0:
                        $fid_list[$k]['result'] = '未知';
                    break;
                    case 1:
                        $fid_list[$k]['result'] = '合格';
                    break;
                    default:
                        # code...
                        $fid_list[$k]['result'] = '不合格';    
                    break;
                } 
            }
            // print_r($fid_list); die;
            $this->assign('sid',$id);
            $this->assign('list',$fid_list);
            $this->display();
        }
    }
	
    /**
     * 删除专题中的项目
     */
    public function del_special_funding(){

        $fid = I('post.fid');
        if(!empty($fid)){

            $map['fid'] = $fid;
        }

        $sid = I('post.sid');
        if(!empty($sid)){

            $map['sid'] = $sid;
        }

        $DB_fsr = M('funding_special_relationship');
        $re = $DB_fsr->where($map)->delete();

        if(!empty($re)){

            echo json_encode(1);
        }else{

            echo json_encode(0);
        }
    }
}