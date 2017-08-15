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

  </style>
</head>
<body>


    

    
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=52678450" charset="UTF-8"></script>    
</body>
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
            callpay();

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
<!--取出来本地存储的信息，提交到服务器，得到返回结果再显示-->
<script type="text/javascript">
        //调用微信JS api 支付
        function jsApiCall()
        {
                WeixinJSBridge.invoke(
                        'getBrandWCPayRequest',
                            <?php echo ($jsApiParameters); ?>,
                        function(res){
                                WeixinJSBridge.log(res.err_msg);
                                if(res.err_msg == "get_brand_wcpay_request:ok" ) {
//                                    window.location.href = "<?php echo U('M/Order/detail');?>?id="+<?php echo ($order["id"]); ?>;
//                                    window.location.href = "<?php echo U('M/Index/index');?>";
                                    window.location.href = "<?php echo U('M/Founding/donate_ok_to_share_for_special',array('fdid'=>$fd_info['id'],'speicialid'=>$specialid));?>";
                                    //支付成功的反馈
                                }else
                                {
                                    alert('支付取消');
                                    window.location.href = "<?php echo U('M/Founding/detail',array('fdid'=>$fd_info['id'],'speicialid'=>$specialid));?>";
                                }
                                //alert(res.err_code+res.err_desc+res.err_msg);
                        }
                );
        }

        function callpay()
        {
                if (typeof WeixinJSBridge == "undefined"){
                    if( document.addEventListener ){
                        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                    }else if (document.attachEvent){
                        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
                        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                    }
                }else{
                    jsApiCall();
                }
        }
        
        

</script>
<?php require_once 'cs.php';echo '<img src="'._cnzzTrackPageView(1256622573).'" width="0" height="0"/>';?>
</body>
</html>