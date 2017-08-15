<?php
namespace Home\Controller;
use Think\Controller;
class EssayController extends Controller {

    public function lists(){
        if(IS_POST){

        }else{

            $User = M('adv');
            $map['type'] = 1;

            $list = $User->where($map)->select();
            // print_r($list); die;
            $this->assign('list',$list);
            $this->display();
        }
    }


    public function details(){

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

}
?>
