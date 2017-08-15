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
        title: '<?php echo ($fd_info["pjname"]); ?>'+"-吃货凑份子钱测一测有没毒、快来支持我！", // 分享标题
        desc: '俺不想千毒不侵，集合爱吃的货货，凑份子钱测有没毒，快支持我一下吧', // 分享描述
        link: "<?php echo ($site_url); echo U('M/Founding/detail_of_share');?>"+"?fdid="+<?php echo ($fd_info['id']); ?>, // 分享链接
        imgUrl: 'http://www.zhongcetianxia.com/logo.png', // 分享图标
        success: function () { 
            // 用户确认分享后执行的回调函数，生成红包

        },
        cancel: function () { 
            // 用户取消分享后执行的回调函数
        }
    });
    
    wx.onMenuShareAppMessage({
        title: '<?php echo ($fd_info["pjname"]); ?>', // 分享标题
        desc: '俺不想千毒不侵，集合爱吃的货货，凑份子钱测有没毒，快支持我一下吧', // 分享描述
        link: "<?php echo ($site_url); echo U('M/Founding/detail_of_share');?>"+"?fdid="+<?php echo ($fd_info['id']); ?>, // 分享链接
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
  <style>
      .blend-panel{background-color: #fff;}
      .sponsor_lists [class*=col-]{background-color: #fff;border: none; margin-top: 1em;}
      .sponsor_pay [class*=col-]{background-color: #fff;border: none; margin-top: 1em;}
      .sponsor_pay .blend-button-primary{background-color: #fff; border: #03ab9e 1px solid; color: #000;}
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
      .list_right_content{
          color: #262626;
            display: block;
            padding-left: 15px;
            height: 40px;
            font-size: 14px;
            line-height: 40px;
            border: none;
      }
      .blend-formgroup{
          position: relative;
      }
      .arrow_forwards:after {
            position: absolute;
            font-family: boostfont;
            content: '\e604';
            display: block;
            width: 20px;
            height: 22px;
            right: 0;
            top: 50%;
            margin-top: -20px;
            font-size: 18px;
            color: #c5c5c5;
        }
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
    <?php if(($issubscribe == 0) OR ($issubscribe == '0')): ?><div data-blend-widget="fixedBar" class="blend-fixedBar blend-fixedBar-top" style="background-color: #fefdf4;">
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
    <!-- Header -->
<header data-blend-widget="header" class="blend-header header_title_bg">
    <span class="blend-header-left">
        <?php if($specialid > 0): ?><a class="blend-header-item  blend-button" href="javascript:history.go(-1);"><img src="<?php echo ($resource_basic); ?>/img/btn_back.png" style="height:28px;"/></a>
            <?php else: ?>
            <a class="blend-header-item  blend-button" href="<?php echo U('M/Founding/lists',array('state'=>1));?>"><img src="<?php echo ($resource_basic); ?>/img/btn_back.png" style="height:28px;"/></a><?php endif; ?>
        
        </span>
    <span class="blend-header-title">
        <a class="blend-header-item">检测项目详情</a>
    </span>
    <span class="blend-header-right">
    </span>
</header>
     <!-- Header End -->
     <div class="blend-panel">
        <div class="blend-panel-header blend-panel-center">
            <?php echo ($fd_info["pjname"]); ?>
        </div>
        
    </div>
     <div class="div_gap"></div>
     <div class="blend-panel">
        <div class="blend-panel-header blend-panel-left">
            项目状态 
            <?php switch($fd_info["state"]): case "1": ?><span class=" font_green_color"><img style="height:60%;" src="<?php echo ($resource_basic); ?>/img/result_founding.png" /></span><?php break;?>
            
            <?php case "2": ?><span class=" font_green_color"><img style="height:60%;" src="<?php echo ($resource_basic); ?>/img/result_testing.png" /></span><?php break;?>
            
            <?php case "3": if($fd_info["result"] == 1): ?><span class=" font_green_color"><img style="height:60%;" src="<?php echo ($resource_basic); ?>/img/result_qualified.png" /></span>
                    <?php else: ?>
                    <span class=" font_green_color"><img style="height:60%;" src="<?php echo ($resource_basic); ?>/img/result_unqualified.png" /></span><?php endif; break;?>
            <?php default: ?>
                <span class=" font_green_color"><img style="height:60%;" src="<?php echo ($resource_basic); ?>/img/result_founding.png" /></span>
                <!--<span class=" font_green_color">审核中</span>--><?php endswitch;?>
            
        </div>
        
    </div>
    <!--main content-->
    
    <div class="div_gap"></div>
    <div class="blend-panel">
        <div class="blend-panel-header blend-panel-left ">
            检测内容 
            <a href="<?php echo U('M/Founding/founding_package_detail',array('fdid'=>$fd_info['id']));?>"><span class="float_right " style='color: red'>危害介绍 ></span></a>
            
        </div>
        <div class="blend-panel-body">
            <?php echo ($fd_info["packagename"]); ?>
            
        </div>
    </div>
    
    <?php if($fd_info["state"] == 3): ?><div id="blendForm1" class="blend-form" style="margin: 8px 0;">
        <div class="blend-formgroup">
            <label class="blend-formgroup-label">检测报告</label>
            <a href="<?php echo ($fd_info["testreport_jpg"]); ?>"><label class="list_right_content arrow_forwards">点击查看</label></a>
        </div>
        <div class="blend-formgroup">
            <label class="blend-formgroup-label">样品信息</label>
            <a href="<?php echo ($fd_info["sample_url"]); ?>"><label class="list_right_content arrow_forwards">点击查看</label></a>
        </div>
    </div><?php endif; ?>
    </div>

    
    <!--<div class="div_gap"></div>-->
    <div id="blendForm1" class="blend-form" style="margin: 8px 0;">
        <div class="blend-formgroup">
            <label class="blend-formgroup-label">品牌</label>
            <label class="list_right_content"><?php echo ($fd_info["brand"]); ?></label>
        </div>
        <div class="blend-formgroup">
            <label class="blend-formgroup-label">产品名称</label>
            <label class="list_right_content"><?php echo ($fd_info["pdname"]); ?></label>
        </div>
        <div class="blend-formgroup">
            <label class="blend-formgroup-label">分类</label>
            <label class="list_right_content"><?php echo ($fd_info["category"]); ?></label>
        </div>
        <div class="blend-formgroup">
            <label class="blend-formgroup-label">抽样地</label>
            <label class="list_right_content" style="display:block; overflow: hidden;"><?php echo ($fd_info["location"]); ?></label>
        </div>
        <div class="blend-formgroup ">
            <label class="blend-formgroup-label">检测机构</label>
            <a href="<?php echo U('M/Founding/publish_institution_detail',array('id'=>$fd_info['institutionid']));?>"><label class="list_right_content arrow_forwards"><?php echo ($fd_info["institution"]); ?> </label></a>
        </div>
    </div>
    <?php if($fd_info["state"] == 3): ?><div class="blend-panel">
        <div class="blend-panel-header blend-panel-left">
            众测进度 
            <span class="float_right font_green_color">已完成众测</span>
            
        </div>
        <div class="blend-panel-body">

            <span style="float:left">目标金额: ￥<?php echo ($total_fee); ?></span>
            <br/>
            
        </div>
    </div>
        <?php else: ?>
        <div class="blend-panel">
        <div class="blend-panel-header blend-panel-left">
            众测进度 
            <span class="float_right font_green_color"><?php echo ($sponsors_count); ?>人支持</span>
            
        </div>
        <div class="blend-panel-body">
            

            <?php if(($fd_info["state"] == 1) OR ($fd_info["state"] == 0)): ?><div class="cent">
                    <div class="Bar">
                        <div style="width: <?php echo ($rate); ?>%;">
                            <span><?php echo ($rate); ?>%</span>
                        </div>
                    </div>
                </div><?php endif; ?>
            

            <span style="float:left">已筹金额: ￥<?php echo ($fd_info["finish"]); ?></span><span style="float:right">目标金额: ￥<?php echo ($total_fee); ?></span>
            <br/>
            
        </div>
    </div><?php endif; ?>
    
    <?php if(($fd_info["state"] == 0)): ?><div id="" class="blend-form" style="margin: 8px 0; font-size: 1.2rem; padding: 10px; color: #22ac9e; background-color: #e9fbfa">
            温馨提示:检测金额为检测费用，不包含检测样品费用，最终金额以审核过后的金额为准。
    </div><?php endif; ?>
    <!--判断状态-->
    
    
    
    
    <div class="div_gap"></div>
    <div class="blend-panel">
        <div class="blend-panel-header blend-panel-left">
            支持者
        </div>
    </div>
    <?php if(!empty($sponsors)): ?><div id='list_more'>
            <?php if(is_array($sponsors)): $i = 0; $__LIST__ = $sponsors;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="blend-card blend-card-no-icon" style="height:60px; position: relative;">
                        <div class="blend-card-img-view" style="float: left;">
                            <img src="<?php echo ($vo["avatar"]); ?>" style='border-radius:100%;'>
                        </div>
                        <div class="blend-card-content-view" style="width:75%;float: left; ">
                            <div class="blend-card-title sponsor_pay_username">
                               <?php echo ($vo["username"]); ?>
                            </div>
                            <div class="blend-card-sub-title sponsor_pay_username_date"><?php echo (date("Y-m-d H:i",$vo["paytime"])); ?></div>

                        </div>

                        <div class="blend-card-img-view sponsor_pay_username_money" style=" float: right;  position: absolute; right: 5px; top: 10px;color: red; width: 55px; ">
                            ￥<?php echo ($vo["money"]); ?> 元
                        </div>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        <?php if($sponsors_count > 10): ?><button class="blend-button blend-button-primary blend-button-large" id="loading_more">加载更多</button><?php endif; ?>
        
        <?php else: ?>
        <span class="blend-col-12">
            亲，等你立刻参与啦！
        </span><?php endif; ?>
    
    <!--test-->
    

    
    <!--main content End-->
    
    
    <!--footer-->
    
    <!--footer End-->
    <div style="height:40px;"></div>
    <?php if(($fd_info["state"] == 1) OR ($fd_info["state"] == 0)): ?><div class="footer_nav">
            <?php if($specialid > 0): ?><a class="blend-button blend-button-primary blend-button-large" href="<?php echo U('M/Founding/donate_pay_money',array('fdid'=>$fdid,'specialid'=>$specialid));?>">立即支持</a>
                
                <?php else: ?>
                <a class="blend-button blend-button-primary blend-button-large" href="<?php echo U('M/Founding/donate_pay_money',array('fdid'=>$fdid));?>">立即支持</a><?php endif; ?>
            
        </div><?php endif; ?>
    
 <script type="text/javascript" src="http://tajs.qq.com/stats?sId=52678450" charset="UTF-8"></script> 
</body>
<script type="text/javascript" src="<?php echo ($resource_basic); ?>/js/handlebars.min.js"></script>
<script type="text/x-handlebars-template" id="tpi-list-item">
  {{#each this}}
    <div class="blend-card blend-card-no-icon" style="height:60px; position: relative;">
                <div class="blend-card-img-view" style="float: left;">
                    <img src="{{avatar}}" style='border-radius:100%;'>
                </div>
                <div class="blend-card-content-view" style="width:75%;float: left; ">
                    <div class="blend-card-title sponsor_pay_username">
                       {{username}}
                    </div>
                    <div class="blend-card-sub-title sponsor_pay_username_date">{{paytime_format}}</div>

                </div>

                <div class="blend-card-img-view sponsor_pay_username_money" style=" float: right;  position: absolute; right: 5px; top: 10px;color: red; width: 55px; ">
                    ￥{{money}} 元
                </div>
            </div>
  {{/each}}
</script>
<script>
$(function(){
//    open_loading();

    var generatedCount=0;

    $("#loading_more").click(function(){
            open_loading();

            generatedCount++;


            /*
            * 获取更多的美容师列表
            */
           $.ajax({ 
                   type: 'GET', 
                   url: '<?php echo U("M/Founding/get_sponor_by_page");?>', 
                   dataType: 'json', 
                   data:"pagecount="+generatedCount+"&fdid="+"<?php echo ($fd_info["id"]); ?>",
                   cache: false, 
                   error: function(){ 
                       return false; 
                   }, 
                   success:function(json){ 
                        close_loading();
                        if(json.err===0)
                        {
                            var template = Handlebars.compile($('#tpi-list-item').html());
                            var html = template(json.res);
                            $("#list_more").append(html);
                        }
                        else
                        {
                            alert(json.msg);
                            $("#loading_more").text(json.msg);
                            $("#loading_more").hide();
                        }

                   } 
               });
        });
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
        var loading = $.blend.loading();
        loading.show();
    }
    
    function close_loading()
    {
        $('.blend-loading').hide();
    }
</script>
<script>
    $("#customDialog").dialog({
        maskTapClose:true,
        renderType:2
    });
    
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


<?php require_once 'cs.php';echo '<img src="'._cnzzTrackPageView(1256622573).'" width="0" height="0"/>';?>
</body>
</html>