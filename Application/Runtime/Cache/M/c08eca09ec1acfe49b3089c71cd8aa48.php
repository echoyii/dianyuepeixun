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
      .blend-row{ margin-bottom: -1px;}
      [class*=blend-col-]{ background-color: #fff; padding-left: 35px; padding-top:10px; padding-bottom: 10px;}
      .blend-picture-full img{ margin:0 auto;}
      .blend-col-12{ font-size: 1.5rem;}
      .col_pol{ float:right;}
      .col_r{ margin-left:10px;}
      .col_address_r{ position: absolute; right:15px; top: 14px;}
      .col_pol_text{ margin-right: 0px; width: 58%; text-align: right; position: absolute; right: 33px;}
      .col_address{ padding-bottom: 28px; overflow: auto;}
  </style>
</head>

<body>
    <header data-blend-widget="header" class="blend-header header_title_bg">
        <span class="blend-header-left">
            <a class="blend-header-item  blend-button" href="<?php echo U('M/Member/index');?>"><img src="<?php echo ($resource_basic); ?>/img/btn_back.png" style="height:28px;"/></a>
        </span>
        <span class="blend-header-title">
            <a class="blend-header-item">我的资料</a>
        </span>
        <span class="blend-header-right">
        </span>
    </header>
    
    
    <figure class="blend-picture blend-picture-full" style="margin-top:0;">
      <div style=" float:left; margin-left:10%; margin-top:10%; position:absolute;">
        <img src="<?php echo ($list["avatar"]); ?>" style='width:70px;heihgt:70px;border-radius:100%;' />
        <!-- <span style=" font-size:1.5rem; color:#000; position:absolute;margin-left:108%;width:100px;bottom:27px;">点击修改</span> -->
      </div>
       <a href=""><img src="/Public/template/default/img/2.jpg"></a>
    </figure>
    
    <div class="blend-grid blend-container-fluid">
      <div class="blend-row">
          <span class="blend-col-12"><span><?php echo ($list['username']); ?></span><a href="<?php echo U('M/Member/modify_name');?>"><span class="col_pol">修改<img src="/Public/template/default/img/small_right.png" class="col_r"></span></a></span>
      </div>
      <div class="blend-row">
          <span class="blend-col-12">手机号<a href="<?php echo U('M/Member/modify_mobile');?>"><span class="col_pol"><?php echo ($list['mobile']); ?><img src="/Public/template/default/img/small_right.png" class="col_r"></span></a></span>
      </div>
      <div class="blend-row">
          <span class="blend-col-12">性别<a href="<?php echo U('M/Member/modify_sex');?>"><span class="col_pol"><?php if($list['gender'] == 1 ): ?>男<?php elseif($list['gender'] == 0): ?>女<?php else: ?>未填<?php endif; ?><img src="/Public/template/default/img/small_right.png" class="col_r"></span></a></span>
      </div>
      <div class="blend-row">
          <span class="blend-col-12">生日<a href="<?php echo U('M/Member/modify_birthday');?>"><span class="col_pol"><?php echo ($list['birthday']); ?><img src="/Public/template/default/img/small_right.png" class="col_r"></span></a></span>
      </div>
      <div class="blend-row">
          <span class="blend-col-12 col_address">地址<a href="<?php echo U('M/Member/modify_location');?>"><span class="col_pol"><span class="col_pol_text"><?php echo ($list['province']); echo ($list['city']); echo ($list['address']); ?></span><img src="/Public/template/default/img/small_right.png" class="col_r col_address_r"></span></a></span>
      </div>

<!--       <div id="asyn" class="blend-address blend-row">
          <div class="blend-address-name"><span>地址</span></div>
          <div class="blend-address-value">
              <div class="blend-address-input">
                  <p class="blend-address-detail blend-address-btn"><?php echo ($list['province']); echo ($list['city']); echo ($list['district']); echo ($list['address']); ?><span class="blend-address-arrow"></span></p>
              </div>
          </div>
      </div> -->
    </div>
  
    
    
    <div style="height: 50px;"></div>
    <!--菜单入口-->
    
    <!--菜单入口 end-->
    
    
    <!--最新检测结果列表-->
    
    
    <!--最新检测结果end-->
    
    
    <!--底部菜单-->
<!-- <div class="footer_nav">
    <nav data-blend-widget="tabnav" class="blend-tabnav" style="margin-bottom: 0px; bottom: 0px; position: absolute; width: 100%; height: 50px; border-top: #b2b2b2 1px solid;">
        <a class="blend-tabnav-item blend-tabnav-item-active" href="<?php echo U('M/Index/index');?>" style="height:50px;">
            <img src="<?php echo ($resource_basic); ?>/img/tab_main_n@2x.png" style=" height: 50%;margin-top: 5px;"/>
                <span class="blend-tabnav-item-text" style="height:30px; line-height: 16px;font-size: 12px; color: #777">
                     首页
                </span>
        </a>
        <a class="blend-tabnav-item blend-tabnav-item-active" href="<?php echo U('M/Search/index');?>" style="height:50px;">
                     <img src="<?php echo ($resource_basic); ?>/img/tab_search_n@2x.png" style=" height: 50%;margin-top: 5px;"/>
                    <span class="blend-tabnav-item-text" style="height:30px; line-height: 16px;font-size: 12px; color: #777;">
                     搜索
                </span>
        </a>
        <a class="blend-tabnav-item blend-tabnav-item-active" href="<?php echo U('M/Member/index');?>" style="height:50px;">
                     <img src="<?php echo ($resource_basic); ?>/img/tab_me_p@2x.png" style=" height: 50%;margin-top: 5px;"/>
                     <span class="blend-tabnav-item-text" style="height:30px; line-height: 16px; font-size: 12px; color: #03ab9e;">
                     我的
                </span>
        </a>


     </nav>
</div> -->
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