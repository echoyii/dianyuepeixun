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
      .main-content{padding: 10px; font-size: 1.5rem;}

  </style>
</head>

<body>
    <header data-blend-widget="header" class="blend-header header_title_bg">
        <span class="blend-header-left">
            <a class="blend-header-item  blend-button" href="<?php echo U('M/Index/index');?>"><img src="<?php echo ($resource_basic); ?>/img/btn_back.png" style="height:28px;"/></a>
        </span>
        <span class="blend-header-title">
            <a class="blend-header-item">使用须知</a>
        </span>
        <span class="blend-header-right">
        </span>
    </header>

    <div class="main-content">
        众测天下上线了，我们希望以专业、中立、大众的立场解决食品安全问题，为大家营造良好的食品环境。<br/><br/>

        如果您对食品有疑虑，可以点击“扫一扫”和“搜一搜”。<br/>
        “扫一扫”：扫描包装袋上的条形码，查看检测结果。<br/>
        “搜一搜”：输入想查询的食品名称，查看检测结果。<br/><br/>

        如果您没有找到检测结果，或者对平台的现有结果不满意、有疑问，可以发起检测。<br/>
        “发起众筹”：您可以在搜索结果页点击“发起众筹”，或者在首页点击发起众筹。
        “填写众筹信息”：根据您要检测的食品，填写名称、品牌、抽样地、备注信息，选择检测项目和检测机构，点击提交发布成功。<br/>
        “参与众筹”：您可以查看正在众筹中的食品，可以选择跟投，点击“立即支持”，选择金额支付即可。<br/>
        “抽样中”：众筹完的项目会进入到抽样阶段，可以查看取样进度。<br/>
        “查结果”：抽样完成的项目会进入到检测阶段，完成后可以查看检测报告。<br/>
        “我的“：您可以查看发起和参与的众筹项目，以及最新的系统通知消息。<br/><br/>

        如果您希望营造一个安全的食品环境，<br/>
        如果您希望身边的亲戚朋友一起参与，<br/>
        如果您希望尽快的拿到食品检测报告，<br/>
        您可以在发众筹、跟投、查看检测报告、或者点击右上角的按钮，分享给您的朋友。<br/><br/>

    </div>
    
    
    <!--菜单入口-->
    
    <!--菜单入口 end-->
    
    
    <!--最新检测结果列表-->
    
    
    <!--最新检测结果end-->
    
    
    <!--底部菜单-->
    <div style="height: 50px;"></div>
<!--<div class="footer_nav">
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
</div>-->
<!--底部菜单end-->
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
<?php require_once 'cs.php';echo '<img src="'._cnzzTrackPageView(1256622573).'" width="0" height="0"/>';?>
</body>
</html>