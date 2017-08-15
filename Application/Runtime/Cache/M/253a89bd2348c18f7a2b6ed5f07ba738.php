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
  <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=vGGKPV50y4pW0j8YP8Opsifk"></script>
  <script type="text/javascript">
    window.boost = $.noConflict(true);
    </script>
  <style>
      .blend-panel{background-color: #fff; }
      .blend-panel-header{color:#03ab9e;}
      #allmap {width:100%;height:1px;display: none;}   
      .blend-card-title{
          width:100%;
            white-space:nowrap;
            text-overflow:ellipsis; 
            -o-text-overflow:ellipsis; 
            overflow: hidden;
      }
      #search_text{
          position: relative;
          top:-10px;
          font-size: 1.2rem;
      }
      #search_img{
          margin-top: -16px;
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
      .tag_name{
          font-size:1.5rem;
          color: #000;
      }
      @media screen and (max-width: 320px)
        {
           .tag_name{
                font-size:1.2rem;
            } 
        }
  </style>
</head>
<body>
    <div id="allmap"></div>
    <header data-blend-widget="header" class="blend-header header_title_bg">
        <span class="blend-header-left">
            <!--<a id='search_img' class="blend-header-item  blend-button" href="<?php echo U('M/Search/index');?>"><img src="<?php echo ($resource_basic); ?>/img/btn_search.png" style="height:80%; width: 80%;"/><br/><label id='search_text'>搜一搜</label></a>-->
        </span>
        <span class="blend-header-title">
            <a class="blend-header-item">众测天下</a>
        </span>
        <span class="blend-header-right">
            
        </span>
    </header>
   
    
    <!--广告模块-->
    
    
    <?php if($adv_counts > 1 ): ?><div class="blend-slider" data-blend-slider="">
            <ul class="blend-slides">
                <?php if(is_array($advertisements)): $i = 0; $__LIST__ = $advertisements;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["isoutlink"] == 0): ?><li>
                            <a href="<?php echo U('M/Index/adv',array('id'=>$vo['id']));?>"><img src="<?php echo ($site_url); echo ($vo["img"]); ?>"></a>
                            <!--<div class="blend-slider-title"><?php echo ($vo["title"]); ?></div>-->
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="<?php echo ($vo["url"]); ?>"><img src="<?php echo ($site_url); echo ($vo["img"]); ?>"></a>
                            <!--<div class="blend-slider-title"><?php echo ($vo["title"]); ?></div>-->
                        </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
    <?php else: ?>
    <figure class="blend-picture blend-picture-full" >
        <?php if(is_array($advertisements)): $i = 0; $__LIST__ = $advertisements;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["isoutlink"] == 0): ?><a href="<?php echo U('M/Index/adv',array('id'=>$vo['id']));?>"><img src="<?php echo ($site_url); echo ($vo["img"]); ?>" style="margin: 0;"></a>
                <?php else: ?>
                <a href="<?php echo ($vo["url"]); ?>"><img src="<?php echo ($site_url); echo ($vo["img"]); ?>" style="margin: 0;"></a><?php endif; endforeach; endif; else: echo "" ;endif; ?>
    </figure><?php endif; ?>
    
    <!--广告幻灯片模块 结束-->
    <!--菜单入口-->
<!--    <div class='blend-grid blend-container-fluid' style="background-color:#fff;">
        <div class='blend-row'>
            <?php if(is_array($tags)): $i = 0; $__LIST__ = $tags;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span class="blend-col-3" style="background-color:#FFF; border: none; text-align: center;">
                <span class='' style="background-color:#FFF; border: none; text-align: center;"><a href="<?php echo U('M/Search/tag',array('tag_name'=>$vo['tag_name'],'tagid'=>$vo['id']));?>"><img src='<?php echo ($site_url); echo ($vo["thumb"]); ?>' style='width: 60%;'/></a>
                </span>
                <span style="background-color:#FFF; border: none; text-align: left; padding-left: 0; padding-right: 0; padding-top:5%;"><a href="<?php echo U('M/Search/tag',array('tag_name'=>$vo['tag_name'],'tagid'=>$vo['id']));?>"  class='tag_name'><?php echo ($vo["tag_name"]); ?></a>
                </span>
            </span><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        
    </div>-->
    
    <div class='blend-grid blend-container-fluid' style="background-color:#fff;">
        <div class='blend-row'>
            <span class="blend-col-3" style="background-color:#FFF; border: none; text-align: center;">
                <span class='' style="background-color:#FFF; border: none; text-align: center;"><a href="<?php echo U('M/Founding/publish',array('from'=>1));?>"><img src='<?php echo ($resource_basic); ?>img/index1.png' style='width: 60%;'/></a>
                </span>
                <span style="background-color:#FFF; border: none; text-align: left; padding-left: 0; padding-right: 0; padding-top:5%;"><a href="<?php echo U('M/Founding/publish',array('from'=>1));?>"  class='tag_name'>发起众测</a>
                </span>
            </span>
            <span class="blend-col-3" style="background-color:#FFF; border: none; text-align: center;">
                <span class='' style="background-color:#FFF; border: none; text-align: center;"><a href="<?php echo U('M/Founding/lists',array('state'=>1));?>"><img src='<?php echo ($resource_basic); ?>img/index2.png' style='width: 60%;'/></a>
                </span>
                <span style="background-color:#FFF; border: none; text-align: left; padding-left: 0; padding-right: 0; padding-top:5%;"><a href="<?php echo U('M/Founding/lists',array('state'=>1));?>"  class='tag_name'>参与众测</a>
                </span>
            </span>
            <span class="blend-col-3" style="background-color:#FFF; border: none; text-align: center;">
                <span class='' style="background-color:#FFF; border: none; text-align: center;"><a href="<?php echo U('M/Founding/lists',array('state'=>3));?>"><img src='<?php echo ($resource_basic); ?>img/index3.png' style='width: 60%;'/></a>
                </span>
                <span style="background-color:#FFF; border: none; text-align: left; padding-left: 0; padding-right: 0; padding-top:5%;"><a href="<?php echo U('M/Founding/lists',array('state'=>2));?>"  class='tag_name'>最新报告</a>
                </span>
            </span>
            <span class="blend-col-3" style="background-color:#FFF; border: none; text-align: center;">
                <span class='' style="background-color:#FFF; border: none; text-align: center;"><a href="<?php echo U('M/Search/index');?>"><img src='<?php echo ($resource_basic); ?>img/index4.png' style='width: 60%;'/></a>
                </span>
                <span style="background-color:#FFF; border: none; text-align: left; padding-left: 0; padding-right: 0; padding-top:5%;"><a href="<?php echo U('M/Search/index');?>"  class='tag_name'>查一查</a>
                </span>
            </span>
        </div>
        
    </div>
    <!--菜单入口 end-->
    <!--<div class="div_gap"></div>-->
    <figure class="blend-picture blend-picture-full" >
        <a href="<?php echo U('M/Founding/unsafe');?>"><img src='<?php echo ($resource_basic); ?>img/index_unqualified.png' /></a>
    </figure>
    <!--<div class="div_gap"></div>-->
    <!--最新检测结果列表-->
    <div class="blend-panel">
        <div class="blend-panel-header blend-panel-left">
            ▎热点资讯
        </div>
    </div>
    <div id="result_list">
    <?php if(is_array($news)): $i = 0; $__LIST__ = $news;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo_news): $mod = ($i % 2 );++$i;?><a  href="<?php echo U('M/News/details',array('id' => $vo_news['id']));?>">
            <div class="blend-card blend-card-no-icon" style="height:100px;">
                <div class="blend-card-img-view" style="width:20%; float: left;">
                    <img src="<?php echo ($site_url); echo ($vo_news["thumb"]); ?>">
                </div>
                <div class="blend-card-content-view" style="width:75%;float: right;">
                    <div class="blend-card-title"><?php echo ($vo_news["title"]); ?></div>
                    <div class="blend-card-sub-title"><?php echo ($vo_news["description"]); ?></div>
                </div>
            </div>
        </a><?php endforeach; endif; else: echo "" ;endif; ?> 
    </div>
    <button class="blend-button blend-button-primary blend-button-large" id="loading_more">加载更多</button>
    <!--最新检测结果end-->
    
    <div style="height:50px;"></div>
    <!--底部菜单-->
<div class="footer_nav">
    <nav data-blend-widget="tabnav" class="blend-tabnav" style="margin-bottom: 0px; bottom: 0px; position: absolute; width: 100%; height: 50px; border-top: #b2b2b2 1px solid;">
        <a class="blend-tabnav-item blend-tabnav-item-active" href="<?php echo U('M/Index/index');?>" style="height:50px;">
            <img src="<?php echo ($resource_basic); ?>/img/tab_main_p@2x.png" style=" height: 50%;margin-top: 5px;"/>
                <span class="blend-tabnav-item-text" style="height:30px; line-height: 16px;font-size: 12px; color: #03ab9e">
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
                     <img src="<?php echo ($resource_basic); ?>/img/tab_me_n@2x.png" style=" height: 50%;margin-top: 5px;"/>
                     <span class="blend-tabnav-item-text" style="height:30px; line-height: 16px; font-size: 12px; color: #777;">
                     我的
                </span>
        </a>


     </nav>
</div>
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
<!--底部菜单end-->
<script type="text/javascript" src="<?php echo ($resource_basic); ?>/js/handlebars.min.js"></script>
<script type="text/x-handlebars-template" id="tpi-list-item">
  {{#each this}}
    <a  href="<?php echo U('M/News/details');?>?id={{id}}">
        <div class="blend-card blend-card-no-icon" style="height:100px;">
            <div class="blend-card-img-view" style="width:20%; float: left;">
                <img src="<?php echo ($site_url); ?>{{thumb}}">
            </div>
            <div class="blend-card-content-view" style="width:75%;float: right;">
                <div class="blend-card-title">{{title}}</div>
                <div class="blend-card-sub-title">{{description}}</div>
            </div>
        </div>
    </a>
  {{/each}}
</script>
<script>
$(function(){
//    open_loading();
    
    boost(".blend-slider").slider({
            ratio:"small"
        });
        
        var generatedCount=0;
     
     $("#loading_more").click(function(){
        open_loading();
        
        generatedCount++;
          
            
        /*
        * 获取更多的美容师列表
        */
       $.ajax({ 
               type: 'GET', 
               url: '<?php echo U("M/Index/get_news_by_page");?>', 
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
        var loading = boost.blend.loading();
        loading.show();
    }
    
    function close_loading()
    {
        $('.blend-loading').hide();
    }


    //获取访问用户的经纬度
//      if(navigator.geolocation){
          //先取消获取用户的地理信息
//            navigator.geolocation.getCurrentPosition(showPosition);
//      }else{
            
//      }
    // 百度地图API功能
    function showPosition(position){

        sessionStorage.lng = position.coords.longitude;
        sessionStorage.lat = position.coords.latitude;
        

        var map = new BMap.Map("allmap");
        var point = new BMap.Point(113.950424,22.539745);
        map.centerAndZoom(point,15);
        var geoc = new BMap.Geocoder();    
        var obj = new BMap.Point(sessionStorage.lng, sessionStorage.lat);
        
        geoc.getLocation(obj, function(rs){
                        var addComp = rs.addressComponents;
                        var store = window.localStorage;
                        var user_address = (addComp.province + addComp.city + addComp.district + addComp.street + addComp.streetNumber);
                        // $("#zuobiao").append(user_address);
                        sessionStorage.address = user_address;
                        sessionStorage.province = addComp.province;
                        sessionStorage.city = addComp.city;
                        sessionStorage.district = addComp.district;
        });
        var url = "<?php echo U('M/Index/update_member');?>";
        var id = <?php echo ($member['id']); ?>;
        $.ajax({
            type: 'post',
            url: url,
            data:{lng:sessionStorage.lng,lat:sessionStorage.lat,address:sessionStorage.address,province:sessionStorage.province,city:sessionStorage.city,district:sessionStorage.district,id:id},
            dataType:'json',
            success: function(data){
                // alert(data);
            }
        });


    }



</script> 
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=52678450" charset="UTF-8"></script>
<?php require_once 'cs.php';echo '<img src="'._cnzzTrackPageView(1256622573).'" width="0" height="0"/>';?>
</body>
</html>