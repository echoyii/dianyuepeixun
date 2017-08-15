<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo ($site_title); ?></title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="stylesheet" type="text/css" href="http://apps.bdimg.com/libs/blendui/2.0.0/boost.min.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo ($resource_basic); ?>/css/webapp.css" />
  <script type="text/javascript" src="<?php echo ($resource_basic); ?>/js/jquery.min.js"></script>
  <script type="text/javascript" src="http://apps.bdimg.com/libs/blendui/2.0.0/boost.min.js"></script>
  <script type="text/javascript">
    window.boost = $.noConflict(true);
    </script>
  <style>
      .blend-panel{background-color: #fff;}
      .blend-flexbox-item{font-size: 1.3rem}
      .blend-button-primary{
          background-color: #03ab9e;
          border-color: #03ab9e;
      }
      .blend-button-primary:active{
          background-color: #03ab9e;
          border-color: #03ab9e;
      }
      .blend-button-primary:focus{
          background-color: #03ab9e;
          border-color: #03ab9e;
      }
      
      .blend-button-primary:visited{
          background-color: #03ab9e;
          border-color: #03ab9e;
      }
      [class*=blend-col-]{
          background-color: #fff;
          border: none;
          text-align: center;
          padding-top: 0;
      }
      .blend-col-8{
          line-height: 40px;
          font-size: 1.7rem;
          text-align: left;
          padding-left: 40px;
      }
      .footer_nav{
          height: 40px;
          background-color: #fff;
      }
      .blend-checkbox-checked, .blend-checkbox-group .blend-checkbox-checked{
          background-color: #03ab9e;
      }
      
  </style>
</head>

<body>
    <header data-blend-widget="header" class="blend-header header_title_bg">
        <span class="blend-header-left">
            <a class="blend-header-item  blend-button" href="javascript:history.go(-1);"><img src="<?php echo ($resource_basic); ?>/img/btn_back.png" style="height:28px;"/></a>
        </span>
        <span class="blend-header-title">
            <a class="blend-header-item">选择检测内容</a>
        </span>
        <span class="blend-header-right">
        </span>
    </header>

    <!--最新检测结果列表-->
    <?php if(is_array($result)): $i = 0; $__LIST__ = $result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="blend-flexbox" style="background-color:#fff; height: 40px; line-height: 40px; margin-top: 5px;">
            <div class="blend-flexbox-item blend-flexbox-ratio"></div>
            <div class="blend-flexbox-item blend-flexbox-ratio7" data-name="<?php echo ($vo["name"]); ?>" data-id="<?php echo ($vo["id"]); ?>" data-price="<?php echo ($vo["price"]); ?>" >
                <div data-blend-widget="radio" data-blend-checkbox='{"type":"radio","value":["man"]}'>
                    <span class="blend-checkbox blend-checkbox-default" id="<?php echo ($vo["id"]); ?>" data-name="<?php echo ($vo["name"]); ?>" data-id="<?php echo ($vo["id"]); ?>" data-price="<?php echo ($vo["price"]); ?>"></span><label class="blend-checkbox-label"><?php echo ($vo["name"]); ?></label>
                </div>
            </div>
            <div class="blend-flexbox-item blend-flexbox-ratio2" style="color:red;">￥<?php echo ($vo["price"]); ?>元</div>
            <div class="blend-flexbox-item blend-flexbox-ratio3"><a style="font-size:1.5rem;color:red;" href="<?php echo U('publish_package_detail',array('id'=>$vo['id']));?>" >危害简介</a></div>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
    <!--最新检测结果end-->
    
    <div style="height: 45px;"></div>
    
    <div class="footer_nav">

        <div class="blend-row">
            <span class="blend-col-8">
                检测金额: <label style="color:red">￥</label><label id="price_total" style="color:red"></label>
            </span>
            <span class="blend-col-4">
                <button class="blend-button blend-button-primary blend-button-large" onclick='javascript:history.go(-1);'>确认</button>
            </span>
        </div>
    </div>
        
    </div>
    

<script>
$(function(){
    open_loading();
    
    //判断是否有选择了产品分类
    
    var  packageids = sessionStorage.packageids;
    if(packageids=="" || typeof(packageids)=="undefined")
    {
        alert("请先选择产品分类");
        top.location.href="<?php echo U('M/Founding/publish_sorts_list');?>";
    }
    else
    {
        //对产品分类绑定的项目进行选中
        var packageids_arr;
        packageids_arr = packageids.split(",");
        $.each(packageids_arr,function(i,n){
            $("#"+n).addClass('blend-checkbox-checked');
        });
        
        console.log($(".blend-checkbox-checked").html());
        
        var price=0;
        var name="";
        var pid="";

        $(".blend-checkbox-checked").each(function(){
    //        累加数据
            price += parseInt($(this).attr('data-price'));
            name += ($(this).attr('data-name')+",");
            pid += ($(this).attr('data-id')+",");

       });
        sessionStorage.price= price;
        sessionStorage.packagename = name.substring(0,name.length-1);
        sessionStorage.packageid = pid.substring(0,pid.length-1);
        $("#price_total").text(price);
    }
    
    
    
    
    $(".blend-flexbox-ratio7").click(function(){
        /*
         * 处理流程：
         * 1. 判断当前点击的是否是选中了
         * 2. 获取所有选中的项目。记录到 sessionStorage 去
         */
        if($(this).find('.blend-checkbox-default').hasClass('blend-checkbox-checked'))
        {
            //存在是则去掉class
            
            $(this).find('.blend-checkbox-default').removeClass('blend-checkbox-checked');
        }
        else
        {
            //不存在的添加 class 选中
            
            $(this).find('.blend-checkbox-default').addClass('blend-checkbox-checked');
        }
        //获取当前一共有多少选中
        var price=0;
        var name="";
        var pid="";
       $(".blend-checkbox-checked").each(function(){
//        累加数据
            price += parseInt($(this).attr('data-price'));
            name += ($(this).attr('data-name')+",");
            pid += ($(this).attr('data-id')+",");
           
       });
       sessionStorage.price= price;
        sessionStorage.packagename = name.substring(0,name.length-1);
        sessionStorage.packageid = pid.substring(0,pid.length-1);
        $("#price_total").text(price);
        
//        $(".blend-checkbox").removeClass('blend-checkbox-checked');
//        $(this).find('.blend-checkbox-default').addClass('blend-checkbox-checked');
//        var name = $(this).attr('data-name');
//        var pid = $(this).attr('data-id');
//        var price = $(this).attr('data-price');
//        
//        sessionStorage.price= price;
//        sessionStorage.packagename = name;
//        sessionStorage.packageid = pid;
        
    });
});


// 加载的loading...
 document.onreadystatechange = subSomething;//当页面加载状态改变的时候执行这个方法. 
    function subSomething() 
    { 
        if(document.readyState === "complete") //当页面加载状态 
        {
            close_loading();

        }
    }
 
    function open_loading()
    {
        var loading = boost.blend.loading();
        loading.show();
    }
    
    function close_loading()
    {
        $('.blend-loading').hide();
    }
</script>
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=52678450" charset="UTF-8"></script>
<?php require_once 'cs.php';echo '<img src="'._cnzzTrackPageView(1256622573).'" width="0" height="0"/>';?>
</body>
</html>