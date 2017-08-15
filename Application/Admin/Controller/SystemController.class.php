<?php
namespace Admin\Controller;
use Think\Controller;
class SystemController extends CommonController {


    // 项目套餐明细表
    public function items_lists()
    {
        if(IS_GET)
        {
            $level_one[]='>系统设置';
            $level_one[]="";
            $level_two[]='>检测项目表';
            $level_two[]=U('Admin/System/items_lists');
            $level_three[]='';
            $level_three[]='';
            $this->assign('level_one',$level_one);
            $this->assign('level_two',$level_two);
            $this->assign('level_three',$level_three);


            $User=M('test_items');
            $list=$User->select();
            $this->assign('list',$list);
            $this->display('items_lists');
        }
        
    }


    //添加检测项目
    public function add_items()
    {
        if(IS_POST)
        {
            $name=I('post.name');
            if(!empty($name))
            {
                $data['name']=$name;
            }
            $User=M('test_items');
            $re=$User->data($data)->add();
            if($re)
            {
                $this->success('添加成功!');
            }
            else
            {
                $this->error('添加失败!');
            }
        }
        else
        {
            $level_one[]='>系统设置';
            $level_one[]="";
            $level_two[]='>检测项目表';
            $level_two[]=U('Admin/System/items_lists');
            $level_three[]='>添加检测项目';
            $level_three[]=U('Admin/System/add_items');
            $this->assign('level_one',$level_one);
            $this->assign('level_two',$level_two);
            $this->assign('level_three',$level_three);
            $this->display('add_items');
        }
        
    }


    //删除检测项目
    public function del_items(){
        if(IS_AJAX){

            $id=I('post.id');
            if(!empty($id))
            {
                $map['id']=$id;
            }
            $User=M('test_items');
            $re=$User->where($map)->delete();
            if($re)
            {
                echo json_encode(1);
            }
        }
    }


    //套餐表
    public function package_lists()
    {
        if(IS_POST)
        {

        }
        else
        {
            $level_one[]='>系统设置';
            $level_one[]="";
            $level_two[]='>检测套餐列表';
            $level_two[]=U('Admin/System/package_lists');
            $level_three[]='';
            $level_three[]='';
            $this->assign('level_one',$level_one);
            $this->assign('level_two',$level_two);
            $this->assign('level_three',$level_three);
            


            $User=M('fundings_package');
            $list=$User->select();
            foreach ($list as $k => $v) 
            {
                $list[$k]['items'] = explode(',', $v['items']);
                $list[$k]['institutionid'] = explode(',',$v['institutionid']);
                $list[$k]['classifyid'] = explode(',', $v['classifyid']);
            }

            // print_r($list);die;
            $test_items = M('test_items');
            $institution = M('institution');
            $food_classify = M('food_classify');
            foreach ($list as $k => $v) 
            {
                foreach ($v['items'] as $key => $value) 
                {
                    $list[$k]['items'][$key] = $test_items->where(array('id' => $value))->getField('name');
                }
            }
            foreach ($list as $k => $v) 
            {
                foreach ($v['institutionid'] as $key => $value) 
                {
                    $list[$k]['institutionid'][$key] = $institution->where(array('id' => $value))->getField('name');
                }
            }
            foreach ($list as $k => $v) 
            {
                foreach ($v['classifyid'] as $key => $value) 
                {
                    $list[$k]['classifyid'][$key] = $food_classify->where(array('id' => $value))->getField('name');
                }
            }

            //将数组内容转化为字符串
            foreach ($list as $k => $v) 
            {
                $list[$k]['items']=implode('，',$v['items']);
                $list[$k]['institutionid']=implode('，',$v['institutionid']);
                $list[$k]['classifyid']=implode('，',$v['classifyid']);
            }

            // print_r($list);die;
            $this->assign('list',$list);


            $this->display('package_lists');
        }
        
    }


    //删除检测套餐
    public function del_package()
    {
        if(IS_AJAX){

            $id=I('post.id');
            if(!empty($id))
            {
                $map['id']=$id;
            }
            $re=M('fundings_package')->where($map)->delete();
            if($re)
            {
                echo json_encode(1);
            }
        }
    }


    //添加检测套餐
    public function add_package()
    {
        if(IS_POST)
        {
            $name=I('post.name');

            $price=I('post.price');
            //检测项目明细表
            $items=I('post.items');
            //包含检测机构

            $institutionid=I('post.organ');
            //所包含的子分类
            $classify_son=I('post.classify_son'); 

            $classify=I('post.classify');

            $content = I('post.content');
            $information = I('post.information');

            if(!empty($content))
            {
                $data['content'] = $content;
            }
            if(!empty($information))
            {
                $data['information'] = $information;
            }
            if(!empty($classify))
            {
                $data['classify']=$classify;
            }

            if(!empty($name))
            {
                $data['name']=$name;
            }
            else
            {
                $this->error('套餐名不能为空');
            }


            if(!empty($price))
            {
                $data['price']=$price*100;
            }
            else
            {
                $this->error('套餐价格不能为空');
            }


            if(!empty($items))
            {
                $data['items']= implode(',', $items);
            }

            if(!empty($institutionid))
            {
                $data['institutionid']=implode(',',$institutionid);
            }
            if(!empty($classify_son))
            {
                $data['classifyid']= implode(',',$classify_son);
            }
            // print_r($data);
            $User=M('fundings_package');
            $re=$User->data($data)->add();
            if($re)
            {
                $this->success('添加成功!',U('Admin/System/package_lists'));
            }
            else
            {
                $this->error('添加失败!');
            }


        }
        else
        {
            
            $level_one[]='>系统设置';
            $level_one[]="";
            $level_two[]='>检测套餐列表';
            $level_two[]=U('Admin/System/package_lists');
            $level_three[]='>添加检测套餐';
            $level_three[]='';
            $this->assign('level_one',$level_one);
            $this->assign('level_two',$level_two);
            $this->assign('level_three',$level_three);


            $User=M('test_items');
            $items_lists=$User->select();
            $this->assign('items_lists',$items_lists);
            
            //获取检测机构
            $institution=M('institution')->select();
            $this->assign('institution',$institution);

            //主分类显示
            $food_classify=M('food_classify')->where('pid=0')->select();
            $this->assign('food_classify',$food_classify);
            $this->display('add_package');
        }
        
    }

    /*
     *  套餐修改
     */
    public function modify_package()
    {
        if(IS_POST){
            $name=I('post.name');
            $map['id']=I('post.id');

            $price=I('post.price');
            //检测项目明细表
            $items=I('post.items');
            //包含检测机构

            $institutionid=I('post.organ');
            //所包含的子分类
            $classify_son=I('post.classify_son'); 

            $classify=I('post.classify');
            if(!empty($classify))
            {
                $data['classify']=$classify;
            }

            if(!empty($name))
            {
                $data['name']=$name;
            }
            else
            {
                $this->error('套餐名不能为空');
            }

            if(!empty($price))
            {
                $data['price']=$price*100;
            }
            else
            {
                $this->error('套餐价格不能为空');
            }


            if(!empty($items))
            {
                $data['items']= implode(',', $items);
            }

            if(!empty($institutionid))
            {
                $data['institutionid']=implode(',',$institutionid);
            }

            if(!empty($classify_son))
            {
                $data['classifyid']= implode(',',$classify_son);
            }
            
            $content = I('post.content');
            if(!empty($content))
            {
                $data['content'] = $content;
            }
            $information = I('post.information');
            if(!empty($information))
            {
                $data['information'] = $information;
            }

            $User=M('fundings_package');
            $re=$User->where($map)->data($data)->save();
            if($re)
            {
                $this->success('修改成功!',U('Admin/System/package_lists'));
            }
            else
            {
                $this->error('修改失败!');
            }

        }
        else
        {
            $id=I('get.id');
            $res = M('fundings_package')->find($id);

            //实体化处理
            $res['content'] = $res['content'];
            $res['information'] = $res['information'];


            $map['id']=$res['classify'];
            $res['classify']=M('food_classify')->where($map)->getField('name');
            // print_r($res); die;
            if(!empty($res))
            {
                //获取当前套餐包含的检测项目
                $exist_items = explode(',',$res['items']);
                $User=M('test_items');
                $items_lists=$User->select();
                foreach ($items_lists as $key => $value) 
                {
                    if(in_array($value['id'],$exist_items))
                    {
                        $items_lists[$key]['checked']=1;
                    }
                    else
                    {
                        $items_lists[$key]['checked']=0;
                    }
                }

                //检测当前套餐包含的机构
                $exist_institutionid = explode(',',$res['institutionid']);
                $institution_lists=M('institution')->select();
                foreach ($institution_lists as $k => $v) 
                {
                    if(in_array($v['id'],$exist_institutionid))
                    {
                        $institution_lists[$k]['checked']=1;
                    }
                    else
                    {
                        $institution_lists[$k]['checked']=0;
                    }
                }


                //主分类显示
                $food_classify=M('food_classify')->where('pid=0')->select();

                //获取从属分类
                $res['classifyid'] = explode(',',$res['classifyid']);
                foreach ($res['classifyid'] as $k => $v) 
                {
                    $res['classifyid'][$k]=M('food_classify')->where(array('id' => $v))->getField('name');
                }

                $res['classifyid'] = implode('，',$res['classifyid']);

                $this->assign('food_classify',$food_classify);
                $this->assign('institution_lists',$institution_lists);
                $this->assign('items_lists',$items_lists);
                $this->assign('res',$res);
                $this->display();
            }
            else
            {
                $this->error("id 参数错误");
            }


        }
        
        
    }





        //ajax获取对应的二级目录
    public function ready_classify()
    {
        $map['pid']=I('post.id');
        $User=M('food_classify');
        $list=$User->where($map)->select();

        echo json_encode($list);
    }

}