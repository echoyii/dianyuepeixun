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

  </style>
</head>

<body>
    <header data-blend-widget="header" class="blend-header header_title_bg">
        <span class="blend-header-left">
            <a class="blend-header-item  blend-button" href="javascript:history.go(-1);"><img src="<?php echo ($resource_basic); ?>/img/btn_back.png" style="height:28px;"/></a>
        </span>
        <span class="blend-header-title">
            <a class="blend-header-item">食品分类</a>
        </span>
        <span class="blend-header-right">
        </span>
    </header>

    
    
 <!--最新检测结果列表-->
    <?php if(is_array($sorts)): $i = 0; $__LIST__ = $sorts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="blend-card blend-card-no-icon" data-price='<?php echo ($vo["totalprice"]); ?>' data-name='<?php echo ($vo["name"]); ?>' data-package-name='<?php echo ($vo["packagename"]); ?>' data-id='<?php echo ($vo["id"]); ?>' data-packageids='<?php echo ($vo["packageids"]); ?>' style="height:100px;position: relative;">
            <div class="blend-card-img-view" >
                <img src="<?php echo ($site_url); echo ($vo["thumb"]); ?>" style="width:90%; height: 90%;">
            </div>
            
            <div class="blend-card-content-view" style=" width: 70%; float: right;position: absolute; top:20%; right: 0;">
                <div class="blend-card-title">
                    <?php echo ($vo["name"]); ?>
                </div>
            </div>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
    <!--最新检测结果end-->
    
    
    
    
<script>
$(function(){
    open_loading();
    
    $(".blend-card").click(function(){
//        alert("fdfd");
        var name = $(this).attr('data-name');
        var iid = $(this).attr('data-id');
        var packageids = $(this).attr('data-packageids');
        var packagename = $(this).attr('data-package-name');
        var totalprice = $(this).attr('data-price');
        
        sessionStorage.sortname = name;
        sessionStorage.sortid = iid;
        sessionStorage.packageids =packageids;
        sessionStorage.packageid =packageids;
        sessionStorage.packagename =packagename;
        sessionStorage.price =totalprice;
        
        window.history.go(-1);
        
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