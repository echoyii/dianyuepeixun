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
      /*.blend-suggest-wrap,.blend-suggest-wrap2{background-color: #03ab9e;}*/
      .blend-suggest-input-blue .blend-suggest-ico{
          background-color: #03ab9e;
      }
      .blend-suggest-arrow:before{
          color: #fff;
      }
      .main-content{ background-color: #fff;}
      .blend-suggest-input-gray{height: 40px;}
      .blend-suggest-input-gray input{height: 30px; font-size: 1.8rem;}
      img{width: 100%;}
  </style>
</head>

<body>
    <header data-blend-widget="header" class="blend-header header_title_bg">
        <span class="blend-header-left">
            <a class="blend-header-item  blend-button" href="<?php echo U('M/Index/index');?>"><img src="<?php echo ($resource_basic); ?>/img/btn_back.png" style="height:28px;"/></a>
        </span>
        <span class="blend-header-title">
            <a class="blend-header-item">食品安全热点</a>
        </span>
        <span class="blend-header-right">
        </span>
    </header>
    
    <div class="main-content">
        <div class="blend-panel" style=" padding:5%;">
            <div class="blend-panel-left">
                <span><b style="font-size:1.6rem;"><?php echo ($get_new["title"]); ?></b></span><br />
                <span style="font-size:1.3rem;"><?php echo ($get_new["author"]); ?> <?php echo (date("Y-m-d",$get_new["stime"])); ?></span>
                <div class="blend-separator"></div>
            </div>
            <div class="blend-panel-left">
                <?php echo ($get_new["content"]); ?>
            </div>
            <div>
              
            </div>
        </div>
    </div>
<!--底部菜单end-->
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
        title: '<?php echo ($get_new["title"]); ?>', // 分享标题
        desc: '食品安全靠大家，一步一步更安全，我觉得这个信息很实用……', // 分享描述
        link: "<?php echo ($site_url); echo U('M/News/details_for_share');?>"+"?id="+<?php echo ($get_new["id"]); ?>, // 分享链接
        imgUrl: 'http://www.zhongcetianxia.com/logo.png', // 分享图标
        success: function () { 
            // 用户确认分享后执行的回调函数，生成红包

        },
        cancel: function () { 
            // 用户取消分享后执行的回调函数
        }
    });
    
    wx.onMenuShareAppMessage({
        title: '<?php echo ($get_new["title"]); ?>', // 分享标题
        desc: '食品安全靠大家，一步一步更安全，我觉得这个信息很实用……', // 分享描述
        link: "<?php echo ($site_url); echo U('M/News/details_for_share');?>"+"?id="+<?php echo ($get_new["id"]); ?>, // 分享链接
        imgUrl: 'http://www.zhongcetianxia.com/logo.png', // 分享图标
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
<?php require_once 'cs.php';echo '<img src="'._cnzzTrackPageView(1256622573).'" width="0" height="0"/>';?>
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=52678450" charset="UTF-8"></script>
</body>
</html>