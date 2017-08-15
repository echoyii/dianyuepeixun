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
      .blend-suggest-input-blue .blend-suggest-ico{
          background-color: #03ab9e;
      }
      .blend-suggest-arrow:before{
          color: #fff;
      }
      .blend-list{ padding: 0 10px; background-color: #fff;}
      .blend-card-content-view{
          max-height: 90px;
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
      .blend-card-title {
            width: 100%;
            white-space: nowrap;
            text-overflow: ellipsis;
            -o-text-overflow: ellipsis;
            overflow: hidden;
        }
  </style>
</head>

<body>
    
        <header data-blend-widget="header" class="blend-header header_title_bg">
        <span class="blend-header-left">
            <a class="blend-header-item  blend-button" href="javascript:history.go(-1);"><img src="<?php echo ($resource_basic); ?>/img/btn_back.png" style="height:28px;"/></a>
        </span>
        <span class="blend-header-title">
            <a class="blend-header-item"><?php echo ($tag_name); ?></a>
        </span>
        <span class="blend-header-right">
        </span>
    </header>
    
    <div class="main-content">
        
       <div class="blend-suggest-wrap2 mar-t-10" id="inputGray2">
        <div class="blend-suggest">
           <div class="blend-suggest-input-gray">
               <input type="hidden" name="tagid" id="tagid" value="<?php echo ($tagid); ?>" />
               <input class="blend-suggest-wd" id='input_search' type="search" placeholder="输入您想要搜索的商品">
               <a href="javascript:;" class="blend-suggest-delete"></a>
               <span class="blend-suggest-ico" id='action_search'></span>
           </div>
        </div>
    </div>
        <?php if(empty($results)): ?><div class="blend-panel">
        
                <div class="blend-panel-body" style='text-align: center;'>
                    没有找到您搜索的内容<br/>
                    <a class="blend-button blend-button-primary"  href="<?php echo U('M/Founding/publish');?>">马上发起</a>


                </div>
            </div>
            <?php else: ?>
            
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
    </a><?php endforeach; endif; else: echo "" ;endif; endif; ?>
        
        
    </div>
    
    
    
    <!--菜单入口-->
    
    <!--菜单入口 end-->
    
    
    <!--最新检测结果列表-->
    
    
    <!--最新检测结果end-->
    
    
    <!--底部菜单-->
<!--<div class="footer_nav">
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
</div>-->
<!--底部菜单end-->
<script>
$(function(){
    open_loading();
    
    $("#action_search").on('click',function(){
         var keyword = $('#input_search').val();
         top.location.href="<?php echo U('tag_search_result_list');?>?keywords="+keyword+"&tagid="+$("#tagid").val()+"&tag_name=<?php echo ($tag_name); ?>";
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