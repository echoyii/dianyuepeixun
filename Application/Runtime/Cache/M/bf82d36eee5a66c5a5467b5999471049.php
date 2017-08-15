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
      .sponsor_pay .blend-button-primary_focus{background-color: #03ab9e;}
    .blend-card-img-view img{
                width: 65%; 
                height: 65%;
            }
  </style>
</head>

<body>
<header data-blend-widget="header" class="blend-header header_title_bg">
    <span class="blend-header-left">
            <a class="blend-header-item  blend-button" href="javascript:history.go(-1);"><img src="<?php echo ($resource_basic); ?>/img/btn_back.png" style="height:28px;"/></a>
        </span>
    <span class="blend-header-title">
        <a class="blend-header-item">确认支付</a>
    </span>
    <span class="blend-header-right">
    </span>
</header>
<form action="<?php echo U('publish_money_confirm');?>" method="post" id="myform">
    <div class="blend-panel">
            
        <div class="blend-panel-body blend-panel-center">
            
            <div class="blend-grid blend-container-fluid sponsor_pay">
                <div class="blend-row">
                    
                    <span class="blend-col-4">
                        <button type='button' class="blend-button blend-button-primary blend-button-xs pay_item blend-button-primary_focus" data-money='10'>10元</button>
                    </span>
                    <span class="blend-col-4">
                        <button type='button' class="blend-button blend-button-primary blend-button-xs pay_item"  data-money='20'>20元</button>
                    </span>
                    <span class="blend-col-4">
                        <button type='button' class="blend-button blend-button-primary blend-button-xs pay_item" data-money='50'>50元</button>
                    </span>
                    
                    <span class="blend-col-4">
                        <button type='button' class="blend-button blend-button-primary blend-button-xs pay_item" data-money='100'>100元</button>
                    </span>
                    <span class="blend-col-4">
                        <button type='button' class="blend-button blend-button-primary blend-button-xs pay_item" data-money='150'>150元</button>
                    </span>
                    <span class="blend-col-4">
                        <button type='button' class="blend-button blend-button-primary blend-button-xs pay_item" data-money='200'>200元</button>
                    </span>

                </div>
            </div>
            
           
        </div>
        
    </div>
    <div class="blend-panel">
        <div class="blend-panel-body blend-panel-center">
            <input type="number" name="donate_other_money" id="donate_other_money" placeholder="其他金额"  style="border: 1px solid #ddd;width: 90%;"/>元
            <input type="hidden" name="donate_money" id="donate_money" placeholder="输入金额" value="10" style="border: 1px solid #ddd;width: 90%;"/>
        </div>
        
        
    </div>
    <div id="" class="blend-form" style="margin: 8px 0; font-size: 1.2rem; padding: 10px; color: #22ac9e; background-color: #e9fbfa">
            温馨提示：领投众测项目10元起
    </div>
    <input type="hidden" name="fdid" value="<?php echo ($fdid); ?>" />
    <div class="div_gap"></div>
</form>
    <div class="blend-card blend-card-no-icon" style="height:100px; position: relative;">
            <div class="blend-card-img-view" style=" float: left; text-align: center; position: relative;top:10%;">
                <img src="<?php echo ($resource_basic); ?>img/payment_wechat.png">
            </div>
            <div class="blend-card-content-view" style="width:150px;  ">
                <div class="blend-card-title">
                    <h4>微信支付</h4>
                </div>
            </div>

            <div class="blend-card-img-view" style=" float: right;  right: 10px;text-align: center; top:30px;position: absolute;">
                <img src="<?php echo ($resource_basic); ?>img/payment_checked.png" style='width:30%; height:30%;'>
            </div>
        </div>
    <div class="blend-panel">
        <div class="blend-panel-header blend-panel-left">
            支付金额: <span style="color:red" >￥</span><span style="color:red" id='money_to_pay'>10</span>
        </div>
        <div class="blend-panel-body">
          <button class="blend-button blend-button-primary blend-button-large wx-pay-btn">微信支付</button>  
            
        </div>
    </div>
    
    
</body>
<script>
$(function(){
    sessionStorage.clear();
    
    $(".pay_item").on("click",function(){
        var money_to_pay = $(this).attr("data-money");
        $("#money_to_pay").text(money_to_pay);
        $("#donate_money").val(money_to_pay);
        $("#donate_other_money").val("");
        
        /*
         * 清除其他的样式
         */
        $(".pay_item").removeClass("blend-button-primary_focus");
        $(this).addClass("blend-button-primary_focus");
        
    });
    
    $("#donate_other_money").bind('input propertychange', function() {
        console.log($(this).val());
        $("#donate_money").val($(this).val());
        $("#money_to_pay").text($(this).val());
        
        $(".pay_item").removeClass("blend-button-primary_focus");
    });
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
<!--取出来本地存储的信息，提交到服务器，得到返回结果再显示-->
<script type="text/javascript">   
        
        $(function() {
            $('.wx-pay-btn').on('click', function() {
                
                //判断金额的输入
                var price = $("#donate_money").val();
                
                var reg = /^[0-9]*[1-9][0-9]*$/;
         
                var strNumber = $("#donate_money").val();
                if(reg.test(strNumber))
                {
                    if(price>=10)
                    {
                        $("#myform").submit();
                    }
                    else
                    {
                        alert("温馨提示：领投众测项目10元起");
                    }
                }
                else
                {
                    alert("请输入整数金额");
                }
                
              });
            });
</script>
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=52678450" charset="UTF-8"></script>
<?php require_once 'cs.php';echo '<img src="'._cnzzTrackPageView(1256622573).'" width="0" height="0"/>';?>
</body>
</html>