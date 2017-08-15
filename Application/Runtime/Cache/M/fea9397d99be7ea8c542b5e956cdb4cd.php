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
      .blend-panel-header{color:#03ab9e;}
      a:active,a:hover,a:visited,a:focus,a{text-decoration: none;}
      
  </style>
</head>

<body>
    <header data-blend-widget="header" class="blend-header header_title_bg">
        <span class="blend-header-left">
<!--            <a class="blend-header-item  blend-button" href="<?php echo U('M/Index/index');?>"><img src="<?php echo ($resource_basic); ?>/img/btn_back.png" style="height:28px;"/></a>-->
        </span>
        <span class="blend-header-title">
            <a class="blend-header-item"><?php echo ($sub_title); ?></a>
        </span>
        <span class="blend-header-right">
        </span>
    </header>

  
    <?php if($state != 3): ?><!--最新检测结果列表-->
        <figure class="blend-picture blend-button-full">
            <a href="<?php echo U('M/Founding/publish');?>"> <img src="<?php echo ($resource_basic); ?>img/create_fouding_btn.png" /></a>
        </figure>
        <br/><?php endif; ?>
<?php if(empty($fundings)): ?><div class="blend-panel">
        
        <div class="blend-panel-body">
            亲，还没有项目，我们先去<a href="<?php echo U('M/Founding/publish');?>">发起</a>呗!
            
            
        </div>
    </div>
    
    <?php else: ?>
    <?php if($state != 3): ?><div class="blend-panel">
        <div class="blend-panel-header blend-panel-left">
            ▎众测中的检测项目
        </div>
    </div><?php endif; ?>
    <?php if(is_array($fundings)): $i = 0; $__LIST__ = $fundings;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a  href="<?php echo U('M/Founding/detail',array('fdid'=>$vo['id']));?>">
            <div class="blend-card blend-card-no-icon" style="height:100px; position: relative;">
                <div class="blend-card-img-view" style="width:20%; float: left;">
                    <img src="<?php echo ($site_url); echo ($vo["thumb"]); ?>">
                </div>
                <div class="blend-card-content-view" style="width:75%;float: right; ">
                    <div class="blend-card-title">
                       <?php echo ($vo["pjname"]); ?>
                    </div>
                    <div class="blend-card-sub-title">检测内容:<?php echo ($vo["packagename"]); ?></div>
                    <div class="blend-card-sub-title">抽样地:<?php echo (substr($vo["location"],0,18)); ?></div>

                </div>

                <div class="blend-card-img-view" style=" float: right;  position: absolute; right: 10px;">
                    <?php if($vo["state"] == 2): ?><img src="<?php echo ($resource_basic); ?>img/testing_mark.png">
                        <?php elseif($vo["state"] == 3): ?>
                        <img src="<?php echo ($resource_basic); echo ($vo["show_mark_icon"]); ?>">
                        <?php else: ?>
                        <img src="<?php echo ($resource_basic); ?>img/fouding_mark.png">
                        <div style="  float: right;position: relative;top: -74%;font-size: 1.3rem;right: 26%;color:#03ab9e; text-align: center; width: 30px;"><?php echo ($vo["rate"]); ?>%</div><?php endif; ?>
                    
                    
                </div>
            </div>
        </a><?php endforeach; endif; else: echo "" ;endif; endif; ?>
    
    <!--最新检测结果end-->
    
    <div style='height:60px;'></div> 
    <!--底部菜单-->
<div class="footer_nav">
    <nav data-blend-widget="tabnav" class="blend-tabnav" style="margin-bottom: 0px; bottom: 0px; position: absolute; width: 100%; height: 50px; border-top: #b2b2b2 1px solid;">
        <a class="blend-tabnav-item blend-tabnav-item-active" href="<?php echo U('M/Index/index');?>" style="height:50px;">
            <img src="<?php echo ($resource_basic); ?>/img/tab_main_n@2x.png" style=" height: 50%;margin-top: 5px;"/>
                <span class="blend-tabnav-item-text" style="height:30px; line-height: 16px;font-size: 12px; color: #777">
                     首页
                </span>
        </a>
        <a class="blend-tabnav-item blend-tabnav-item-active" href="<?php echo U('M/Founding/lists',array('state'=>1));?>" style="height:50px;">
                     <img src="<?php echo ($resource_basic); ?>/img/tab_search_p@2x.png" style=" height: 50%;margin-top: 5px;"/>
                    <span class="blend-tabnav-item-text" style="height:30px; line-height: 16px;font-size: 12px; color: #03ab9e;">
                     众测
                </span>
        </a>
        <a class="blend-tabnav-item blend-tabnav-item-active" href="<?php echo U('M/Member/index');?>" style="height:50px;">
                     <img src="<?php echo ($resource_basic); ?>/img/tab_me_n@2x.png" style=" height: 50%;margin-top: 5px;"/>
                     <span class="blend-tabnav-item-text" style="height:30px; line-height: 16px; font-size: 12px; color: #777;">
                     我的
                </span>
        </a>


     </nav>
</div>
<!--底部菜单end-->
<!--微信分享的基本信息-->
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
//分享

wx.config({
    debug: false,
    appId: '<?php echo ($signPackage["appId"]); ?>',
    timestamp: <?php echo ($signPackage["timestamp"]); ?>,
    nonceStr: '<?php echo ($signPackage["nonceStr"]); ?>',
    signature: '<?php echo ($signPackage["signature"]); ?>',
    jsApiList: [
      // 所有要调用的 API 都要加到这个列表中
      'onMenuShareTimeline',
      'onMenuShareAppMessage'
    ]
});
wx.ready(function () {
// 在这里调用 API
    wx.onMenuShareTimeline({
        title: '<?php echo ($sharedata["share_all"]["title"]); ?>', // 分享标题
        desc: '<?php echo ($sharedata["share_all"]["desc"]); ?>', // 分享描述
        link: '<?php echo ($sharedata["share_all"]["link"]); ?>', // 分享链接
        imgUrl: '<?php echo ($sharedata["share_all"]["thumb"]); ?>', // 分享图标
        success: function () { 
            // 用户确认分享后执行的回调函数，生成红包

        },
        cancel: function () { 
            // 用户取消分享后执行的回调函数
        }
    });
    
    wx.onMenuShareAppMessage({
        title: '<?php echo ($sharedata["share"]["title"]); ?>', // 分享标题
        desc: '<?php echo ($sharedata["share"]["desc"]); ?>', // 分享描述
        link: '<?php echo ($sharedata["share"]["link"]); ?>', // 分享链接
        imgUrl: '<?php echo ($sharedata["share"]["thumb"]); ?>', // 分享图标
        type: '', // 分享类型,music、video或link，不填默认为link
        dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
        success: function () { 
            // 用户确认分享后执行的回调函数

        },
        cancel: function () { 
            // 用户取消分享后执行的回调函数
        }
    });

});

</script>
<!--微信分享的基本信息end-->
<script>
$(function(){
//    open_loading();
});
// 加载的loading...
 document.onreadystatechange = subSomething;//当页面加载状态改变的时候执行这个方法. 
    function subSomething() 
    { 
        if(document.readyState === "complete") //当页面加载状态 
        {
//            close_loading();

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