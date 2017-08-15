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
      .blend-card-content-view{
          max-height: 80px;
      }
  </style>
</head>

<body>
    <header data-blend-widget="header" class="blend-header header_title_bg">
        <span class="blend-header-left">
            <a class="blend-header-item  blend-button" href="javascript:history.go(-1);"><img src="<?php echo ($resource_basic); ?>/img/btn_back.png" style="height:28px;"/></a>
        </span>
        <span class="blend-header-title">
            <a class="blend-header-item">我的项目</a>
        </span>
        <span class="blend-header-right">
        </span>
    </header>
    
    <!--最新检测结果列表-->
    <?php if(is_array($my_takepart)): $i = 0; $__LIST__ = $my_takepart;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["state"] != 4): ?><a  href="<?php echo U('M/Founding/detail',array('fdid'=>$vo['fid'],'specialid'=>$specialid));?>">
                <div class="blend-card" style="height:100px;">
                    <div class="blend-card-img-view" style="width:20%; float: left;">
                        <img src="<?php echo ($site_url); echo ($vo["thumb"]); ?>">
                    </div>
                    <div class="blend-card-content-view" style="width:75%;float: right;">
                        <div class="blend-card-title">
                           <?php echo ($vo["pjname"]); ?>
                        </div>
                        <?php if($vo["state"] == 3): ?><div class="blend-card-sub-title">生产日期/批号:<?php echo ($vo["batch"]); ?></div>
                            <div class="blend-card-sub-title">检测日期:<?php echo (date("Y-m-d",$vo["ctime"])); ?></div>
                            <div class="blend-card-sub-title">抽样地:<?php echo ($vo["location"]); ?></div>
                            <?php else: ?>
                            <div class="blend-card-sub-title">检测内容:<?php echo ($vo["packagename"]); ?></div>
                        <div class="blend-card-sub-title">抽样地:<?php echo ($vo["location"]); ?></div><?php endif; ?>

                    </div>
                    <div class="blend-card-img-view" style=" float: right;  position: absolute; right: 10px;">
                            <img src="<?php echo ($resource_basic); echo ($vo["show_mark_icon"]); ?>">
                        </div>
                </div>
            </a>
            
            <?php else: ?>
            
                <div class="blend-card" style="height:100px;">
                    <div class="blend-card-img-view" style="width:20%; float: left;">
                        <img src="<?php echo ($site_url); echo ($vo["thumb"]); ?>">
                    </div>
                    <div class="blend-card-content-view" style="width:75%;float: right;">
                        <div class="blend-card-title">
                           <?php echo ($vo["pjname"]); ?>
                        </div>
                        <?php if($vo["state"] == 3): ?><div class="blend-card-sub-title">生产日期/批号:<?php echo ($vo["batch"]); ?></div>
                            <div class="blend-card-sub-title">检测日期:<?php echo (date("Y-m-d",$vo["ctime"])); ?></div>
                            <div class="blend-card-sub-title">抽样地:<?php echo ($vo["location"]); ?></div>
                            <?php else: ?>
                            <div class="blend-card-sub-title">检测内容:<?php echo ($vo["packagename"]); ?></div>
                        <div class="blend-card-sub-title">抽样地:<?php echo ($vo["location"]); ?></div><?php endif; ?>

                    </div>
                    <div class="blend-card-img-view" style=" float: right;  position: absolute; right: 10px;">
                            <img src="<?php echo ($resource_basic); echo ($vo["show_mark_icon"]); ?>">
                        </div>
                </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
    <!--最新检测结果end-->
    
    
    
    
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