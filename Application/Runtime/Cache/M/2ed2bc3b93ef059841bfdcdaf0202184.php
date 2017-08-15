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
      .blend-button-large{ border-radius:7px;}
      .blend-suggest-arrow:before{
          color: #fff;
      }
      .main-content{ background-color: #fff;}
      .blend-suggest-input-gray{height: 40px;}
      .blend-suggest-input-gray input{height: 30px; font-size: 1.8rem;}
      [class*=blend-col-]{
          background-color: #fff;
          border: none;
          text-align: center;
      }
  </style>
</head>

<body>
    <header data-blend-widget="header" class="blend-header header_title_bg">
        <span class="blend-header-left">
            <a class="blend-header-item  blend-button" href="<?php echo U('M/Index/index');?>"><img src="<?php echo ($resource_basic); ?>/img/btn_back.png" style="height:28px;"/></a>
        </span>
        <span class="blend-header-title">
            <a class="blend-header-item">搜索不合格产品</a>
        </span>
        <span class="blend-header-right">
        </span>
    </header>
    
    <div class="main-content">
        <!--<div style='height:30px;'></div>--> 
        
       <div class="blend-suggest-wrap2 mar-t-10" id="inputGray2">
        <div class="blend-suggest">
           <div class="blend-suggest-input-gray">
               
                   <input class="blend-suggest-wd" id='input_search' type="search" placeholder="输入您想要搜索的关键字">
               
               <a href="javascript:;" class="blend-suggest-delete"></a>
               <span class="blend-suggest-ico" id='action_search'></span>
           </div>
        </div>
    </div>
        <?php if(!empty($results)): ?><div id="result_list">
    <?php if(is_array($results)): $i = 0; $__LIST__ = $results;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["isurl"] == 1): ?><a href='<?php echo ($vo["url"]); ?>'>
                <?php else: ?>
                <a href="<?php echo U('M/Founding/detail',array('fdid'=>$vo['id']));?>"><?php endif; ?>

            <div class="blend-card blend-card-no-icon" style="height:100px;">
                <div class="blend-card-content-view" style="width:75%;float: left;">
                    <div class="blend-card-title">
                       <?php echo ($vo["name"]); ?>
                    </div>
                    <div class="blend-card-sub-title">生产日期/批号:<?php echo ($vo["batch_number"]); ?></div>
                    <div class="blend-card-sub-title">检测日期:<?php echo ($vo["testing_date"]); ?></div>
                    <div class="blend-card-sub-title">抽样地:<?php echo ($vo["location"]); ?></div>

                </div>
                <div class="blend-card-img-view" style="float: right;">
                    <img src="<?php echo ($resource_basic); echo ($vo["show_mark_icon"]); ?>">
                </div>

            </div>
            </a><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
            <button class="blend-button blend-button-primary blend-button-large" id="loading_more">加载更多</button><?php endif; ?>
<!--    <div style='height:30px;'></div> 
    <div class="blend-title">
        <span>搜索</span>
    </div>

    <div class="blend-panel">
        <label style="margin-left:30px; font-size:1.4rem;">热门搜索</label>
        <div class="blend-panel-body">
            <div class="blend-grid blend-container-fluid">
                <div class='blend-row'>
                    <?php if(is_array($tag_list)): $i = 0; $__LIST__ = $tag_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span class="blend-col-4">
                        <a href="<?php echo U('M/Founding/unsafe_list');?>?keywords=<?php echo ($vo["keyword"]); ?>" class="blend-button blend-button-default blend-button-large"><?php echo ($vo["keyword"]); ?></a>
                    </span><?php endforeach; endif; else: echo "" ;endif; ?>
                    
                </div>
            </div>
        </div>
    </div>-->
    
    
    </div> 
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
         <img src="<?php echo ($resource_basic); ?>/img/tab_search_p@2x.png" style=" height: 50%;margin-top: 5px;"/>
        <span class="blend-tabnav-item-text" style="height:30px; line-height: 16px;font-size: 12px; color: #03ab9e;">
             搜索
        </span>
        </a>
        <a class="blend-tabnav-item blend-tabnav-item-active" href="<?php echo U('M/Member/index');?>" style="height:50px;">
         <img src="<?php echo ($resource_basic); ?>/img/tab_me_n@2x.png" style=" height: 50%;margin-top: 5px;"/>
        <span class="blend-tabnav-item-text" style="height:30px; line-height: 16px; font-size: 12px; color: #777;">
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
<script type="text/javascript" src="<?php echo ($resource_basic); ?>/js/handlebars.min.js"></script>
<script type="text/x-handlebars-template" id="tpi-list-item">
  {{#each this}}
    <a href='{{url}}'>
        <div class="blend-card blend-card-no-icon" style="height:100px;">
            <div class="blend-card-content-view" style="width:75%;float: left;">
                <div class="blend-card-title">
                {{name}}
                </div>
                <div class="blend-card-sub-title">生产日期/批号:{{batch_number}}</div>
                <div class="blend-card-sub-title">检测日期:{{testing_date}}</div>
                <div class="blend-card-sub-title">抽样地:{{location}}</div>

            </div>
            <div class="blend-card-img-view" style="float: right;">
                <img src="<?php echo ($resource_basic); ?>{{show_mark_icon}}">
            </div>

        </div>
    </a>
  {{/each}}
</script>
<script>
 $(function(){
     open_loading();
     $("#input_search").focus();
     
     $("#action_search").on('click',function(){
         var keyword = $('#input_search').val();
         top.location.href="<?php echo U('M/Founding/unsafe_list');?>?keywords="+keyword;
     });
     
     var generatedCount=0;
     
     $("#loading_more").click(function(){
        open_loading();
        generatedCount++;   
          
           $.ajax({ 
                   type: 'GET', 
                   url: '<?php echo U("M/Founding/unsafe_list_by_page_for_index");?>', 
                   dataType: 'json', 
                   data:"pagecount="+generatedCount,
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
                            $("#result_list").append(html);
                        }
                        else
                        {
                            alert(json.msg);
                            $("#loading_more").text(json.msg);
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
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=52678450" charset="UTF-8"></script>
</body>
</html>