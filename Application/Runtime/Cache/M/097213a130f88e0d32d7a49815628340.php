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
      body{background-color: #fff;}
      .blend-panel{background-color: #fff;}
      .sponsor_lists [class*=col-]{background-color: #b9e3d9;border: none; margin-top: 1em;}
      .sponsor_pay [class*=col-]{background-color: #fff;border: none; margin-top: 1em;}
      .sponsor_pay .blend-button-primary{background-color: #fff; border: #03ab9e 1px solid; color: #000;}
      select{
          display: block;
        width: 30%;
        padding: .5em;
        font-size: 1.6rem;
        line-height: 1.2;
        color: #555;
        vertical-align: middle;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 0;
        -webkit-appearance: none;
        -webkit-transition: border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
      }
      .blend-button-large{background-color: #03ab9e; border-color:#03ab9e; }
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
      .blend-formgroup-label{
          width: 110px;
      }
      .blend-panel-header{
          color:#03ab9e;
      }
      @media screen and (max-width: 320px)
      {
          #show_upload_img img{height:40px; width: 40px;}
      }
  </style>
</head>

<body>
    <!-- Header -->
<header data-blend-widget="header" class="blend-header header_title_bg">
    <span class="blend-header-left">
        <?php if($from > 0): ?><a class="blend-header-item  blend-button" href="javascript:history.go(-1);"><img src="<?php echo ($resource_basic); ?>/img/btn_back.png" style="height:28px;"/></a>
            <?php else: ?>
            <a class="blend-header-item  blend-button" href="<?php echo U('M/Founding/lists',array('state'=>1));?>"><img src="<?php echo ($resource_basic); ?>/img/btn_back.png" style="height:28px;"/></a><?php endif; ?>
            
        </span>
    <span class="blend-header-title">
        <a class="blend-header-item">发起检测</a>
    </span>
    <span class="blend-header-right">
    </span>
</header>
     <!-- Header End -->
     
    <!--main content-->
    <form action="/index.php/M/Founding/publish.html?code=021a90f62f7d198d9b0121109186908E&state=65689" method="post" id="myform">
        <div class="blend-panel">
            <div class="blend-panel-header blend-panel-left">   
            ▎基本信息
        
            </div>
        </div>

        <div id="" class="blend-form" style="margin: 8px 0;">
            <div class="blend-formgroup">
                <label class="blend-formgroup-label">产品名称:</label>
                <input type="text" name="pdname" id='pdname' class="blend-formgroup-input" placeholder="请填写产品名称">
            </div>
            <div class="blend-formgroup">
                <label class="blend-formgroup-label">产品品牌:</label>
                <input type="text" name="brand" id='brand' class="blend-formgroup-input" placeholder="请填写品牌信息">
            </div>
            <div class="blend-formgroup">
                <label class="blend-formgroup-label">产品分类:</label>
                <label  class="blend-formgroup-label" style="width:200px;color: #999999;padding-left: 3px;" id='sort_name' onclick="javascript:window.location.href='./publish_sorts_list.html'" >选择产品分类 </label>
            </div>
            <div class="blend-formgroup">
                <label class="blend-formgroup-label">生产日期/批号:</label>
                <input type="text" name="batch" id='batch' class="blend-formgroup-input" placeholder="请填写生产日期/批号 (选填)">
            </div>
            
            <div class="blend-formgroup">
                <input type='hidden' name='citycode' value="4403" />
                <label class="blend-formgroup-label">抽样地:</label>
                
                <label  class="blend-formgroup-label" style="width:200px;color: #999999; padding-left: 3px; " >广东 深圳 </label>
                
            </div>
            <div class="blend-formgroup">
                <label class="blend-formgroup-label"></label>
                <input type="text" name="location_detail" id='location_detail' class="blend-formgroup-input" placeholder="请指定具体抽样地(选填)">
            </div>
            <div class="blend-formgroup">
                <label class="blend-formgroup-label">产品图片:</label>
                <div style="width: 60%;">
                    <ul class="blend-imglist-wrapper blend-imglist-theme" id="show_upload_img">
                        <li class="blend-imglist-item blend-imglist-small">
                            <a href="<?php echo U('M/Founding/uploadimg');?>">
                                <img src="<?php echo ($resource_basic); ?>img/create_founding_photo_default.png" id="chooseImage">
                            </a>
                        </li>
                        
                    </ul>
                    
                </div>
                
            </div>
            
<!--            <div class="blend-formgroup">
                <label class="blend-formgroup-label"></label>
                <button class="blend-button blend-button-primary blend-button-large" id="submit-button-imgs" type="button">提交</button>
            </div>
            
            <div class="blend-formgroup">
                <label class="blend-formgroup-label"></label>
                <button class="blend-button blend-button-primary blend-button-large" id="submit-button-imgs-show" type="button">显示数据</button>
            </div>-->
            
        </div>

        <div class="blend-panel">
            <div class="blend-panel-header blend-panel-left">
                ▎ 检测信息
            </div>
        </div>

        <div id="" class="blend-form" style="margin: 8px 0;">
            <div class="blend-formgroup">
                <label class="blend-formgroup-label">检测内容:</label>
                <label  class="blend-formgroup-label" style="width:200px;color: #999999;" onclick="javascript:window.location.href='./publish_package_list.html'" id='packagename'>选择检测内容 </label>
            </div>
            
            <div class="blend-formgroup">
                <label class="blend-formgroup-label">检测机构:</label>
                <label  class="blend-formgroup-label" style="width:200px;color: #999999;" onclick="javascript:window.location.href='./publish_institution_list.html'" id='institutionname'>选择检测机构 </label>
            </div>
        </div>
        
        <div class="blend-panel">
            <div class="blend-panel-header blend-panel-left">
               检测金额: ￥<span id="need_money">-</span>
            </div>
        </div>
        <input type="hidden" name="emoney" id="emoney" value="" />
        <input type="hidden" name="packageid"  value="" />
        <input type="hidden" name="institutionid"  value="" />
        <input type="hidden" name="first_categoryid" id="first_categoryid"  value="" />
        <input type="hidden" name="wx_media_ids" id="wx_media_ids"  value="" />
        
        <div id="" class="blend-form" style="margin: 8px 0;">
            <div class="blend-formgroup">
                <label class="blend-formgroup-label">众测故事:</label>
                <!--<input type="text" name="content" id='content' class="blend-formgroup-input" placeholder="说说你为什么要发起这个检查呗">-->
                <textarea id='content' name="content" style="background-color:#eee;border: none;height: 80px; width: 120%; margin: 10px; font-size: 1.5rem" placeholder="说说你为什么要发起这个检查呗"></textarea>
            </div>
        </div>
        
        <div id="" class="blend-form" style="margin: 8px 0; font-size: 1.2rem; padding: 10px; color: #22ac9e; background-color: #e9fbfa">
            温馨提示:检测金额为检测费用，不包含检测样品费用，最终金额以审核过后的金额为准。
        </div>
        <div style=" height: 50px;">
            
        </div>
    </form>
<div class="footer_nav">
    <button class="blend-button blend-button-primary blend-button-large" id="submit-button">提交</button>
</div>
    
    
    
    
    

    
    <!--main content End-->
    
    
    <!--footer-->
    
    <!--footer End-->
    
    
 <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
 <script>

wx.config({
    debug: false,
    appId: '<?php echo ($signPackage["appId"]); ?>',
    timestamp: <?php echo ($signPackage["timestamp"]); ?>,
    nonceStr: '<?php echo ($signPackage["nonceStr"]); ?>',
    signature: '<?php echo ($signPackage["signature"]); ?>',
    jsApiList: [
      // 所有要调用的 API 都要加到这个列表中
      'onMenuShareTimeline',
      'onMenuShareAppMessage',
      'chooseImage',
      'previewImage',
      'uploadImage',
      'downloadImage'
    ]
});

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
// 5.1 拍照、本地选图
var images = {
    localId: [],
    serverId: []
  };
  
//  document.querySelector('#chooseImage').onclick = function () {
//    wx.chooseImage({
//      count: 3, // 默认9
//      success: function (res) {
//        
//        images.localId = res.localIds;
//        if(images.localId.length>=3)
//        {
//            $("#chooseImage").hide();
//        }
////        alert('已选择 ' + res.localIds.length + ' 张图片');
//        for(var i=0; i<res.localIds.length; i++)
//        {
//            $("#show_upload_img").append("<li class='blend-imglist-item blend-imglist-small'><img src='"+images.localId[i]+"'/><br/><span style='font-size:1.5rem;'>删除</span></li>");
//        }
//        
//      }
//    });
//  };

// 5.3 上传图片
//  document.querySelector('#submit-button-imgs').onclick = function () {
//    if (images.localId.length == 0) {
//      alert('请先使用 chooseImage 接口选择图片');
//      return;
//    }
//    var i = 0, length = images.localId.length;
//    images.serverId = [];
//    function upload() {
//      wx.uploadImage({
//        localId: images.localId[i],
//        success: function (res) {
//          i++;
//          images.serverId.push(res.serverId);
//          if (i < length) {
//            upload();
//          }
//        },
//        fail: function (res) {
//          alert(JSON.stringify(res));
//        }
//      });
//    }
//    upload();
//  };
</script>
 
<script>
$(function(){
    open_loading();
    
//    $("#submit-button-imgs-show").on("click",function(){
//        var json = {};
//        for(i=0;i<images.serverId.length;i++)
//        {
//            json[i]=images.serverId[i];
//        }
//        alert(JSON.stringify(json));
//        
//    });

    
    /*
     * 页面初始化功能
     * 
     * 1. 从页面 session 获取名称和品牌的值
     * 
     */
    
    var pdname = sessionStorage.pdname;
    var brand = sessionStorage.brand;
    var packagename = sessionStorage.packagename;
    
    //超过10个字的，保留10个
    if(packagename=="" || typeof(packagename)=="undefined")
    {
        
    }
    else
    {
        if(packagename.length>10)
        {
            packagename = packagename.substring(0,10)+"...";
        }
    }
    
    
    
    var packageid = sessionStorage.packageid;
    var packageprice = sessionStorage.price;
    var institutionname = sessionStorage.institutionname;
    var institutionid = sessionStorage.institutionid;
    var content = sessionStorage.content;
    var first_categoryid = sessionStorage.sortid;
    var sort_name = sessionStorage.sortname;
    var batch = sessionStorage.batch;
    var location_detail = sessionStorage.location_detail;
    
    
    //获取上传的图片缓存资料
    var imgs1 = sessionStorage.imgs0;
    var imgs2 = sessionStorage.imgs1;
    var imgs3 = sessionStorage.imgs2;

    
    
    $("#pdname").val(pdname);
    $("#brand").val(brand);
    $("#packagename").text(packagename);
    $("input[name='packageid']").val(packageid);
    $("#institutionname").text(institutionname);
    $("input[name='institutionid']").val(institutionid);
    $("#need_money").text(packageprice);
    $("#emoney").val(packageprice);
    $("#content").val(content);
    $("input[name='first_categoryid']").val(first_categoryid);
    $("#sort_name").text(sort_name);
    $("#batch").val(batch);
    $("#location_detail").val(location_detail);
    //记录微信上传的照片记录
    $("#wx_media_ids").val(sessionStorage.wx_media_ids);
    
    
    
   
    
    if(imgs1=="" || typeof(imgs1)=="undefined")
    {
        
    }
    else
    {
        $("#show_upload_img").prepend('<li class="blend-imglist-item blend-imglist-small"><a href="#"><img src="'+imgs1+'"></a></li>');
    }
    
    if(imgs2=="" || typeof(imgs2)=="undefined")
    {
        
    }
    else
    {
        $("#show_upload_img").prepend('<li class="blend-imglist-item blend-imglist-small"><a href="#"><img src="'+imgs2+'"></a></li>');
    }
    
    if(imgs3=="" || typeof(imgs3)=="undefined")
    {
        
    }
    else
    {
        $("#show_upload_img").prepend('<li class="blend-imglist-item blend-imglist-small"><a href="#"><img src="'+imgs3+'"></a></li>');
        $("#show_upload_img li:last").hide();
    }
    
    
    
    /*
     * 页面初始化功能 end
     */
    
    /*
     * 动态记录用户的输入缓存下来
     */
    $("#pdname").bind('input propertychange', function() { 
//        alert($(this).val());
        sessionStorage.pdname = $(this).val();
    });
    
    $("#brand").bind('input propertychange', function() { 
        
        sessionStorage.brand = $(this).val();
    });
    
    $("#content").bind('input propertychange', function() { 
        
        sessionStorage.content = $(this).val();
    });
    
    $("#batch").bind('input propertychange', function() { 
        
        sessionStorage.batch = $(this).val();
    });
    
    $("#location_detail").bind('input propertychange', function() { 
        
        sessionStorage.location_detail = $(this).val();
    });
    
    
    $("#submit-button").click(function(){
        
        //检查填入的信息是否完备
        
        var pdname = $("#pdname").val();
        var brand = $("#brand").val();
        var packageid = $("input[name='packageid']").val();
        var institutionid = $("input[name='institutionid']").val();
        var first_categoryid = $("#first_categoryid").val();
        
        if(pdname==""|| pdname==null)
        {
            alert("产品名称不能为空");
            $("#pdname").focus();
            return false;
        }
        
        if(brand==""|| brand==null)
        {
//            alert("品牌不能为空");
//            $("#brand").focus();
//            return false;
        }
        
        if(packageid==""|| packageid==null)
        {
            alert("检测套餐不能为空");
            return false;
        }
        
        if(institutionid==""|| institutionid==null)
        {
            alert("检测机构不能为空");
            return false;
        }
        
        if(first_categoryid==""|| first_categoryid==null)
        {
            alert("产品分类不能为空");
            return false;
        }
        
        
        
        
        open_loading();
        $("#myform").submit();
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
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=52678450" charset="UTF-8"></script>
<?php require_once 'cs.php';echo '<img src="'._cnzzTrackPageView(1256622573).'" width="0" height="0"/>';?>
</body>

</html>