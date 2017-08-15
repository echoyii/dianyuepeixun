<?php
namespace Admin\Controller;
use Think\Controller;
class DetailsController extends CommonController {
/**
 date:   2015-10-10
 des:    添加项目详情介绍
 */
    //项目详细里列表
    public function lists()
    {

        if(!IS_POST)
        {
            $User = M('details');
            $list = $User->select();

            //实体化内容
            foreach ($list as $k => $v) 
            {
                $list[$k]['content'] = htmlspecialchars_decode($v['content']);
                $list[$k]['information'] = htmlspecialchars_decode($v['information']);    
            }

            $this->assign('list',$list);
            $this->display();
        }
    }



    //添加新的项目详情
    public function add()
    {
        if(!IS_POST)
        {
            $this->display();
        }
        else
        {
            $title = I('post.title');
            $content = I('post.content');
            $information = I('post.information');

            if(!empty($title))
            {
                $data['title'] = trim($title);
            }
            else
            {
                $this->error('标题不能为空');
            }

            if(!empty($content))
            {
                $data['content'] = trim($content);
            }
            else
            {
                $this->error('检测内容不能为空');
            }

            if(!empty($information))
            {
                $data['information'] = trim($information);
            }
            else
            {
                $this->error('相关知识介绍不能为空');
            }

            $User = M('details');
            $result = $User->data($data)->add();

            if(!empty($result))
            {
                $this->success('添加成功!',U('Admin/Details/lists'));
            }
            else
            {
                $this->error('添加失败!');
            }
        }
    }



    //修改项目介绍
    public function modify()
    {
        if(!IS_POST)
        {
            $id = I('get.id');
            if(!empty($id))
            {
                $map['id'] = $id;
            }

            $User = M('details');

            $list = $User->where($map)->find();

            //消除实体化

            $list['content'] = htmlspecialchars_decode($list['content']);
            $list['information'] = htmlspecialchars_decode($list['information']);
        
            $this->assign('list',$list);
            $this->display();
        }
        else
        {
            $id = I('post.id');
            if(!empty($id))
            {
                $map['id'] = $id;
            }
            else
            {
                $this->error('参数出错!');
            }

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

            $User = M('details');
            $result = $User->where($map)->data($data)->save();

            if(!empty($result))
            {
                $this->success('添加成功!',U('Admin/Details/lists'));
            }   
            else
            {
                $this->error('添加失败!');
            }
        }
    }



    //ajax删除检测项目介绍
    public function del()
    {
        $id = I('post.id');
        if(!empty($id))
        {
            $map['id'] = $id;
        }

        $User = M('details');
        $result = $User->where($map)->delete();

        if($result)
        {
            echo json_encode(1);
        }
        else
        {
            echo json_encode(0);
        }
    }

}