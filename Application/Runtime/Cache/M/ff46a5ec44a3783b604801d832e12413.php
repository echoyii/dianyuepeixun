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
      .sponsor_lists [class*=col-]{background-color: #b9e3d9;border: none; margin-top: 1em;}
      .sponsor_pay [class*=col-]{background-color: #fff;border: none; margin-top: 1em;}
      .sponsor_pay .blend-button-primary{background-color: #fff; border: #03ab9e 1px solid; color: #000;}
      .blend-formgroup-label{width: 40%;}
      .blend-formgroup-label-content{
            color: #777;
            display: block;
            padding-left: 15px;
            height: 40px;
            font-size: 14px;
            line-height: 40px;
            border: none;
      }
  </style>
</head>

<body>
    <!-- Header -->
<header data-blend-widget="header" class="blend-header header_title_bg">
    <span class="blend-header-left">
            <a class="blend-header-item  blend-button" href="javascript:history.go(-1);"><img src="<?php echo ($resource_basic); ?>/img/btn_back.png" style="height:28px;"/></a>
        </span>
    <span class="blend-header-title">
        <a class="blend-header-item">产品详情</a>
    </span>
    <span class="blend-header-right">
    </span>
</header>
     <!-- Header End -->
     
    <!--main content-->
    <div class="blend-panel">
        <div class="blend-panel-header blend-panel-center">
            <?php echo ($product["product_name"]); ?>
        </div>
        
    </div>
    <div  class="blend-form" style="margin: 8px 0;">
    <div class="blend-formgroup">
            <label class="blend-formgroup-label">检测结果</label>
            <label class="blend-formgroup-label-content"> <?php if($product["qualified"] == 1): ?><label style="color:#03ab9e;"><img style="height:60%;" src="<?php echo ($resource_basic); ?>/img/result_qualified.png" /></label><?php else: ?><font color="red"><img style="height:60%;" src="<?php echo ($resource_basic); ?>/img/result_unqualified.png" /></font><?php endif; ?></label>
        </div>
        
    </div>
    <div class="div_gap"></div>
    <div class="blend-panel">
        <div class="blend-panel-header">
           抽检项目 
        </div>
        <div class="blend-panel-body">
            <?php echo ($product["items"]); ?>
        </div>
    </div>
    <?php if($product["qualified"] == 0): ?><div class="div_gap"></div>
        <div class="blend-panel">
            <div class="blend-panel-header">
               不合格项目║检验结果║标准值 
            </div>
            <div class="blend-panel-body">
                <?php echo ($product["unqualified_remark"]); ?>
            </div>
        </div><?php endif; ?>
    <div  class="blend-form" style="margin: 8px 0;">
        <div class="blend-formgroup">
            <label class="blend-formgroup-label">食品名称</label>
            <label class="blend-formgroup-label-content"> <?php echo ($product["product_name"]); ?></label>
        </div>
        <div class="blend-formgroup">
            <label class="blend-formgroup-label">规格型号</label>
            <label class="blend-formgroup-label-content"> <?php echo ($product["product_model"]); ?></label>
        </div>
        <div class="blend-formgroup">
            <label class="blend-formgroup-label">生产日期/批号</label>
            <label class="blend-formgroup-label-content"> <?php echo ($product["cdate"]); ?></label>
        </div>
        <div class="blend-formgroup">
            <label class="blend-formgroup-label">分类</label>
            <label class="blend-formgroup-label-content"> <?php echo ($product["category"]); ?></label>
        </div>
        <div class="blend-formgroup">
            <label class="blend-formgroup-label">公告号</label>
            <label class="blend-formgroup-label-content"> <?php echo ($product["notification_number"]); ?></label>
        </div>
        <div class="blend-formgroup">
            <label class="blend-formgroup-label">抽样地</label>
            <label class="blend-formgroup-label-content"> <?php echo ($product["province"]); ?></label>
        </div>
    </div>
    <!--<div class="div_gap"></div>-->
    <div id="blendForm1" class="blend-form" style="margin: 8px 0;">
        <div class="blend-formgroup">
            <label class="blend-formgroup-label">标称生产企业名称</label>
            <label class="blend-formgroup-label-content"></label>
        </div>
        <div class="blend-formgroup">
            <label class="blend-formgroup-label-content"> <?php echo ($product["firm_name"]); ?></label>
        </div>
        <div class="blend-formgroup">
            <label class="blend-formgroup-label">标称生产企业地址</label>
            <label class="blend-formgroup-label-content"> </label>
        </div>
        <div class="blend-formgroup">
            <label class="blend-formgroup-label-content"> <?php echo ($product["firm_address"]); ?></label>
        </div>
        
        <div class="blend-formgroup">
            <label class="blend-formgroup-label">被抽样单位名称</label>
            <label class="blend-formgroup-label-content"> </label>
        </div>
        <div class="blend-formgroup">

            <label class="blend-formgroup-label-content"> <?php echo ($product["Department_name"]); ?></label>
        </div>
        
        
        <div class="blend-formgroup">
            <label class="blend-formgroup-label">备注</label>
            <label class="blend-formgroup-label-content"> <?php echo ($product["remarks"]); ?></label>
        </div>
    </div>
    
    <div class="div_gap"></div>
    
    
    <div class="blend-panel">
        <div class="blend-panel-body">
            <?php echo ($product["declares"]); ?>
        </div>
    </div>
    <div class="div_gap"></div>
    <?php if($product["qualified"] == 1): ?><div class="blend-panel">
        <div class="blend-panel-header">
           数据来源 
        </div>
        <div class="blend-panel-body">
            <a href="http://app1.sfda.gov.cn/datasearch/face3/content.jsp?tableId=110&tableName=TABLE110&tableView=%B9%FA%BC%D2%CA%B3%C6%B7%B0%B2%C8%AB%BC%E0%B6%BD%B3%E9%BC%EC%A3%A8%BA%CF%B8%F1%B2%FA%C6%B7%A3%A9&Id=<?php echo ($product["from_id"]); ?>">国家食品药品监督管理总局</a>
        </div>
    </div>
        <?php else: ?>
        <div class="blend-panel">
        <div class="blend-panel-header">
           数据来源 
        </div>
        <div class="blend-panel-body">
            国家食品药品监督管理总局
        </div>
    </div><?php endif; ?>
    
   
    
    <!--test-->
    

    
    <!--main content End-->
    
    
    <!--footer-->
    
    <!--footer End-->
    
    
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
<script>

    
    $(document).ready(function(){
        $(".sponsor_btn").click(function(){
          $(".blend-dialog").dialog("show");
        });
      });
      
//    $(function({
//        $(".sponsor_btn").click();
////        $(".sponsor_btn").click(function({
////            alert("ddd");
//////            $(".blend-dialog").dialog("show");
////        });
//    });
</script>


</html>