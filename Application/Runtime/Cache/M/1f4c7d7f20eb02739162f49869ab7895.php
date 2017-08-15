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
      .blend-row{ margin-bottom: 20px; height: }
      [class*=blend-col-]{ background-color: #fff; padding-left: 35px; padding-top:15px; padding-bottom: 15px;}
      .blend-col-12{ font-size: 1.7rem;}
      a{color: #000;}
      a:active{color: #333;}
      a:hover{color: #333;}
      a:visited{color: #333;}
      .col_r{ float: right;}
  </style>
  <style type="text/css">
  .blend-picture-full img{ margin:0 auto;}
  </style>
</head>

<body>
    <header data-blend-widget="header" class="blend-header header_title_bg">
        <span class="blend-header-left">
            <!--<a class="blend-header-item  blend-button" href="javascript:history.go(-1);"><img src="<?php echo ($resource_basic); ?>/img/btn_back.png" style="height:28px;"/></a>-->
        </span>
        <span class="blend-header-title">
            <a class="blend-header-item">会员中心</a>
        </span>
        <span class="blend-header-right">
        </span>
    </header>
    
    <!-- <figure class="blend-picture blend-picture-full" style="margin-top:0;">
      <div style=" float:left; margin-left:40%; margin-top:10%; position:absolute;">
        <a href="<?php echo U('M/Member/my_profile');?>"><img src="<?php echo ($resource_basic); ?>/img/head.jpg" style="border-radius:100%;"></a> 
        <div style='text-align: center; width: 70px; height: 30px;'><span style=" font-size:1.5rem; color:#000; "><?php echo ($memberinfo["username"]); ?></span></div>
      </div>
      
       <a href=""><img src="<?php echo ($resource_basic); ?>/img/2.jpg"></a>
    </figure> -->
    <figure class="blend-picture blend-picture-full" style="margin-top:0;">
      <div style=" float:left; margin-left:10%; margin-top:10%; position:absolute;">
        <a href="<?php echo U('M/Member/my_profile');?>"><img src="<?php echo ($memberinfo["avatar"]); ?>" style='width:70px;heihgt:70px;border-radius:100%;' />
        <span style=" font-size:1.5rem; color:#000; position:absolute;margin-left:108%;width:100px;bottom:27px;"><?php echo ($memberinfo["username"]); ?></span></a>
      </div>
       <a href=""><img src="<?php echo ($resource_basic); ?>/img/2.jpg"></a>
    </figure>
    
    <div class="blend-grid blend-container-fluid">
      <div class="blend-row">
          <a href="<?php echo U('M/Member/my_orders');?>"><span class="blend-col-12"><img style='width:30px; height: 30px;' src="<?php echo ($resource_basic); ?>/img/funding.png" />&nbsp;&nbsp;我的项目<img src="<?php echo ($resource_basic); ?>/img/right.png" class="col_r"></span></a>
      </div>
      <div class="blend-row">
          <a href="#"><span class="blend-col-12"><img style='width:30px; height: 30px;' src="<?php echo ($resource_basic); ?>/img/message.png" />&nbsp;&nbsp;我的消息<img src="<?php echo ($resource_basic); ?>/img/right.png" class="col_r"></span></a>
      </div>
      <div class="blend-row">
          <a href="<?php echo U('M/Member/feedback');?>"><span class="blend-col-12"><img style='width:30px; height: 30px;' src="<?php echo ($resource_basic); ?>/img/write.png" />&nbsp;&nbsp;反馈意见<img src="<?php echo ($resource_basic); ?>/img/right.png" class="col_r"></span></a></div>
    </div>
    
    
    
    <div style="height: 50px;"></div>
    <!--菜单入口-->
    
    <!--菜单入口 end-->
    
    
    <!--最新检测结果列表-->
    
    
    <!--最新检测结果end-->
    
    
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
                     <img src="<?php echo ($resource_basic); ?>/img/tab_search_n@2x.png" style=" height: 50%;margin-top: 5px;"/>
                    <span class="blend-tabnav-item-text" style="height:30px; line-height: 16px;font-size: 12px; color: #777;">
                     众测
                </span>
        </a>
        <a class="blend-tabnav-item blend-tabnav-item-active" href="<?php echo U('M/Member/index');?>" style="height:50px;">
                     <img src="<?php echo ($resource_basic); ?>/img/tab_me_p@2x.png" style=" height: 50%;margin-top: 5px;"/>
                     <span class="blend-tabnav-item-text" style="height:30px; line-height: 16px; font-size: 12px; color: #03ab9e;">
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
      $('.blend-loading').hide({
        animate: true,        // 切换时地步标示是否使用动画，默认是true
        start: 1            // 初始化指定展示tab的索引，默认是0
      });
    }

    <!-- //标签选择初始化 -->
    boost('.blend-tab').tab();

</script>
<?php require_once 'cs.php';echo '<img src="'._cnzzTrackPageView(1256622573).'" width="0" height="0"/>';?>
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=52678450" charset="UTF-8"></script>
</body>
</html>