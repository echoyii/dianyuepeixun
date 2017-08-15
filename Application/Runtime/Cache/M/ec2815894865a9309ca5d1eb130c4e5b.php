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
      .blend-button-primary{
          background-color: #03ab9e;
          border-color: #03ab9e;
      }
      .blend-button-primary:active{
          background-color: #03ab9e;
          border-color: #03ab9e;
      }
      .blend-button-primary:focus{
          background-color: #03ab9e;
          border-color: #03ab9e;
      }
      
      .blend-button-primary:visited{
          background-color: #03ab9e;
          border-color: #03ab9e;
      }
      .main-content{ background-color: #fff;}
      img{width: 100%;}
      .blend-panel-left p{ margin:0;} 
      .Bar ,.Bars { position: relative; width: 100%;    /* 宽度 */ border: none; padding: 1px; background-color: #e9fbfa}
        .Bar div,.Bars div { display: block; position: relative; background:#03ab9e;/* 进度条背景颜色 */ color: #333333; height: 20px; /* 高度 */ line-height: 20px;  /* 必须和高度一致，文本才能垂直居中 */ }
        .Bars div{ background:#090}
        .Bar div span,.Bars div span { position: absolute; width: 100%; /* 宽度 */ text-align: center; font-weight: bold; }
        .cent{ margin:0 auto; width:100%; overflow:hidden}
        .sponsor_pay_username{
            font-size: 1.5rem;
        }
        .sponsor_pay_username_date{
            font-size: 1.3rem;
        }
        .sponsor_pay_username_money{
            font-size: 1.3rem;
        }
        .blend-card-img-view{
            width: 45px;
            height: 45px;
        }
        @media screen and (max-width: 320px)
        {
            .sponsor_pay_username{
                font-size: 1.2rem;
            }
            .sponsor_pay_username_date{
                font-size: 1rem;
            }
        }
        .blend-fixedBar{
            height: 65px;
            z-index: 1000;
        }
        .blend-card-content-view{
            text-align: left;
        }
  </style>
</head>

<body>
    <?php if($issubscribe == 0): ?><div data-blend-widget="fixedBar" class="blend-fixedBar blend-fixedBar-top" style="background-color: #fefdf4;">
            <div class="blend-card blend-card-no-icon" style=" position: relative; background-color: #fefdf4;">
                <div class="blend-card-img-view" style=" float: left;">
                    <img src="http://www.zhongcetianxia.com/logo.png">
                </div>

                <div class="blend-card-content-view" style="width:75%;float: right; ">
                    <div class="blend-card-title">
                        众测天下
                    </div>
                    <div class="blend-card-sub-title">中国食品第一民意平台</div>

                </div>
                <div class="blend-card-img-view" style=" float: right;  position: absolute; right: 10px; width:70px; top: 25%;">
                    <a href="http://mp.weixin.qq.com/s?__biz=MzIwNTA4MDI2OQ==&mid=400782306&idx=1&sn=37b3565d67b5b70017e8a0b6ddf5fdc0&scene=1&srcid=1216wRjczQqPUxPaksqI7ZIf&key=ac89cba618d2d976456aa41f43a2b06dfd1a54e3923db82a7f3d3e377f4e259e0697341f6a42f4c6b84e371b332e994b&ascene=0&uin=MTM3ODM0&devicetype=iMac+MacBookPro10%2C1+OSX+OSX+10.11.2+build(15C50)&version=11020201&pass_ticket=6kK2IUir3HybYujxE7oyT7J9wbkmSXS7NKdU8YLB3b4%3D" class="blend-button blend-button-primary" style="border-radius:8px;">立即关注</a>
                </div>
            </div>
        </div>
        <div style="height: 65px;">
        </div><?php endif; ?>
    <header data-blend-widget="header" class="blend-header header_title_bg">
        <span class="blend-header-left">
            <a class="blend-header-item  blend-button" href="<?php echo U('M/Index/index');?>"><img src="<?php echo ($resource_basic); ?>/img/btn_back.png" style="height:28px;"/></a>
        </span>
        <span class="blend-header-title">
            <a class="blend-header-item"><?php echo ($list["name"]); ?></a>
        </span>
        <span class="blend-header-right">
        </span>
    </header>
    
    <div class="main-content">
            <div class="blend-panel-left">
                <?php echo ($list["content"]); ?>
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
        title: '众测邀你一起抓致癌君 力争上“油”，测一测你吃的油是否安全？', // 分享标题
        desc: '力争上“油”，测一测你吃的油是否安全？', // 分享描述
        link: "http://www.zhongcetianxia.com/index.php/M/Special/special/id/1.html", // 分享链接
        imgUrl: 'http://www.zhongcetianxia.com/Public/125.125.png', // 分享图标
        success: function () { 
            // 用户确认分享后执行的回调函数，生成红包

        },
        cancel: function () { 
            // 用户取消分享后执行的回调函数
        }
    });
    
    wx.onMenuShareAppMessage({
        title: '众测邀你一起抓致癌君', // 分享标题
        desc: '力争上“油”，测一测你吃的油是否安全？', // 分享描述
        link: "http://www.zhongcetianxia.com/index.php/M/Special/special/id/1.html", // 分享链接
        imgUrl: 'http://www.zhongcetianxia.com/Public/125.125.png', // 分享图标
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