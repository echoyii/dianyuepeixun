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
      .blend-formgroup{ margin-top: 10px;}
      body{ background-color: #fff;}
  </style>
</head>

<body>
    <header data-blend-widget="header" class="blend-header header_title_bg">
        <span class="blend-header-left">
            <a class="blend-header-item  blend-button" href="javascript:history.go(-1);"><img src="<?php echo ($resource_basic); ?>/img/btn_back.png" style="height:28px;"/></a>
        </span>
        <span class="blend-header-title">
            <a class="blend-header-item">手机号</a>
        </span>
        <span class="blend-header-right">
        <a class="blend-header-item"></a>
        </span>
    </header>


    <div class="blend-formgroup">
        <label class="blend-formgroup-label">手机号:</label>
        <input type="text" name="mobile" id='mobile' class="blend-formgroup-input" placeholder="<?php echo ($memberinfo["mobile"]); ?>">
    </div>
    <div class="" style=" margin-top:30px;">
    <button class="blend-button blend-button-primary blend-button-large" id="submit" style=" background-color:#03ab9e;">提交</button>
    </div>

    <div style="height: 50px;"></div>
    <!--菜单入口-->
    

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


    $(document).ready(function(){

        $("#submit").bind('click',function(){

            var mobile = $("#mobile").val();
            if(mobile == ''){
                alert('手机号未变更!');
                return false;
            }
            // alert(mobile);
            var url = "<?php echo U('M/Member/modify_mobile');?>";
            $.ajax({
                type:'POST',
                url:url,
                data:{mobile:mobile},
                dataType:'json',
                success:function(data){
                    if(data==1){
                        window.location.href="<?php echo U('M/Member/my_profile');?>";   
                    } 
                }
            });
        });
    });

    <!-- //标签选择初始化 -->
    boost('.blend-tab').tab();

</script>
<?php require_once 'cs.php';echo '<img src="'._cnzzTrackPageView(1256622573).'" width="0" height="0"/>';?>
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=52678450" charset="UTF-8"></script>
</body>
</html>