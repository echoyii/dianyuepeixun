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
      .blend-panel-center-title{color: #03ab9e;}

  </style>
</head>

<body>
    <header data-blend-widget="header" class="blend-header header_title_bg">
        <span class="blend-header-left">
            <a class="blend-header-item  blend-button" href="javascript:history.go(-1);"><img src="<?php echo ($resource_basic); ?>/img/btn_back.png" style="height:28px;"/></a>
        </span>
        <span class="blend-header-title">
            <a class="blend-header-item">危害简介</a>
        </span>
        <span class="blend-header-right">
        </span>
    </header>
    
<?php if(is_array($packages)): $i = 0; $__LIST__ = $packages;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$package): $mod = ($i % 2 );++$i;?><div class="blend-panel">
        <div class="blend-panel-header blend-panel-center blend-panel-center-title">
            <?php echo ($package["name"]); ?>
        </div>
    </div>

    <div class="div_gap"></div>
    <div class="blend-panel">
        <div class="blend-panel-header blend-panel-center">
            基本信息
        </div>
        <div class="blend-panel-body">
            <?php echo (htmlspecialchars_decode($package["content"])); ?>
        </div>
    </div>
    
    <div class="div_gap"></div>
    <div class="blend-panel">
        <div class="blend-panel-header blend-panel-center">
            基本知识
        </div>
        <div class="blend-panel-body">
            <?php echo (htmlspecialchars_decode($package["information"])); ?>
        </div>
    </div>
    <br/><br/><?php endforeach; endif; else: echo "" ;endif; ?>  
    

<script>
$(function(){
    open_loading();
    
    $(".blend-flexbox-ratio7").click(function(){
//        alert("fdfd");
        $(".blend-checkbox").removeClass('blend-checkbox-checked');
        $(this).find('.blend-checkbox-default').addClass('blend-checkbox-checked');
        var name = $(this).attr('data-name');
        var pid = $(this).attr('data-id');
        var price = $(this).attr('data-price');
        
        sessionStorage.price= price;
        sessionStorage.packagename = name;
        sessionStorage.packageid = pid;
        
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