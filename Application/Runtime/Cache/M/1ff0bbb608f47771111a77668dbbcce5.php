<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo ($list['title']); ?></title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="stylesheet" type="text/css" href="http://apps.bdimg.com/libs/blendui/2.0.0/boost.min.css" />
  <link rel="stylesheet" type="text/css" href="/Public/template/default/css/webapp.css" />
  <script type="text/javascript" src="/Public/template/default/js/jquery.min.js"></script>
  <script type="text/javascript" src="http://apps.bdimg.com/libs/blendui/2.0.0/boost.min.js"></script>
  <script type="text/javascript">
    window.boost = $.noConflict(true);
    </script>
  <style>
      .blend-panel{background-color: #fff;}
      .content-main{
          font-size: 1.5rem;
      }
      img{width: 100%;}
  </style>
</head>

<body>
    <header data-blend-widget="header" class="blend-header header_title_bg">
        <span class="blend-header-left">
        </span>
        <span class="blend-header-title">
            <a class="blend-header-item"><?php echo ($list['title']); ?></a>
        </span>
        <span class="blend-header-right">
        </span>
    </header>
    
    <div>
    <article class="content-main" style="width:90%; margin-left:5%;"><?php echo ($list['content']); ?></article>
    </div>
    <!--菜单入口-->
    
    <!--菜单入口 end-->
    
    
    <!--最新检测结果列表-->
    
    
    <!--最新检测结果end-->
    
    
    <!--底部菜单-->

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
    open_loading();
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