<?php
namespace Api\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $res['err']=0;
        $res['msg']="API前缀";
        $res['content']="http://www.zhongcetianxia.com";
        
        echo json_encode($res);
    }
    

}