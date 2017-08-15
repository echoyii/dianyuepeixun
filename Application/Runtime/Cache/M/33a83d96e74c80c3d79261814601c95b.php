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
     body{
         background-color: #fff;
     }
      .blend-panel{background-color: #fff;}
      [class*=blend-col-]{
          background-color: #fff;
          border: none;
          text-align: center;
      }
      [class*=blend-col-] img{
          text-align: center;
          width: 100%;
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
  </style>
</head>

<body>
<header data-blend-widget="header" class="blend-header header_title_bg">
    <span class="blend-header-left">
            <a class="blend-header-item  blend-button" href="<?php echo U('M/Founding/publish');?>"><img src="<?php echo ($resource_basic); ?>/img/btn_back.png" style="height:28px;"/></a>
        </span>
    <span class="blend-header-title">
        <a class="blend-header-item">上传图片</a>
    </span>
    <span class="blend-header-right">
    </span>
</header>
    <br/><br/>
  <button class="blend-button blend-button-primary blend-button-large" id="chooseImage">选择要上传的图片</button>
<br/>
  <div  class="blend-imglist ">
      <ul class="blend-imglist-wrapper blend-imglist-column-3" id="imgs_show">
          
      </ul>
  </div>
<button class="blend-button blend-button-primary blend-button-large" id="uploadImage" style="display: none;">上传图片</button>
<div class="footer_nav">
    
    
    <button class="blend-button blend-button-primary blend-button-large" id="confirm_and_back" style="display: none;">上传成功，点击返回继续</button>

</div>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>

// 加载的loading...
 document.onreadystatechange = subSomething;//当页面加载状态改变的时候执行这个方法. 
    function subSomething() 
    { 
        if(document.readyState === "complete") //当页面加载状态 
        {
           

        }
    }
</script>
<!--取出来本地存储的信息，提交到服务器，得到返回结果再显示-->

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

// 5.1 拍照、本地选图
var images = {
    localId: [],
    serverId: []
  };
  document.querySelector('#chooseImage').onclick = function () {
    wx.chooseImage({
      count: 3, // 默认9
      sizeType: [ 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
      sourceType: ['album'], 
      success: function (res) {
        images.localId = res.localIds;
//        alert('已选择 ' + res.localIds.length + ' 张图片');
        for(var i=0; i<res.localIds.length; i++)
        {
            $("#imgs_show").append("<li class='blend-imglist-item'><img src='"+images.localId[i]+"'/></li>");
            sessionStorage.setItem('imgs'+i,images.localId[i]);
//            alert(images.localId[i]);
        }
        
      $('#uploadImage').show();  
      }
    });
  };
  // 5.3 上传图片
  document.querySelector('#uploadImage').onclick = function () {
      
    if (images.localId.length == 0) {
      alert('请先使用 chooseImage 接口选择图片');
      return;
    }
    var i = 0, length = images.localId.length;
    images.serverId = [];
    function upload() {
      wx.uploadImage({
        localId: images.localId[i],
        success: function (res) {
          i++;
          //alert('已上传：' + i + '/' + length);
          images.serverId.push(res.serverId);
          if (i < length) {
            upload();
          }
        },
        fail: function (res) {
          alert(JSON.stringify(res));
        }
      });
    }
    upload();
    $('#uploadImage').hide();  
    $('#confirm_and_back').show();
    
  };
  
  
  document.querySelector('#confirm_and_back').onclick = function () {
      var json = {};
    for(i=0;i<images.serverId.length;i++)
    {
        json[i]=images.serverId[i];
    }

    var wx_media_ids = (JSON.stringify(json));
    sessionStorage.wx_media_ids = wx_media_ids;
    top.location.href="<?php echo U('M/Founding/publish');?>";
  }
  

</script>

</html>