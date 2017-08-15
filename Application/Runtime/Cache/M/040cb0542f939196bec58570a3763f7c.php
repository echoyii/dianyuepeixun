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
  <script type="text/javascript" src="/Public/date/js/laydate.js"></script>
  <script type="text/javascript">
    window.boost = $.noConflict(true);
    </script>
  <style>
  .blend-formgroup{ margin-top: 10px;}
    select{
          display: block;
        width: 30%;
        padding: .5em;
        font-size: 1.6rem;
        line-height: 1.2;
        color: #555;
        vertical-align: middle;
        background-color: #eee;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 0;
        -webkit-appearance: none;
        -webkit-transition: border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
      }
      body{ background-color: #fff;}
  </style>
</head>

<body>
    <header data-blend-widget="header" class="blend-header header_title_bg">
        <span class="blend-header-left">
            <a class="blend-header-item  blend-button" href="javascript:history.go(-1);"><img src="<?php echo ($resource_basic); ?>/img/btn_back.png" style="height:28px;"/></a>
        </span>
        <span class="blend-header-title">
            <a class="blend-header-item">地址</a>
        </span>
        <span class="blend-header-right">
        <a class="blend-header-item" ></a>
        </span>
    </header>


    <div class="blend-formgroup">
        <label class="blend-formgroup-label">地址:</label>
        <select name="province" id="province">
            <option value="44">广东</option>
        </select>
        <select name="city" id="city">
            <option value="4403">深圳</option>
        </select>
        <br/>
    </div>
    <div class="blend-formgroup">
        <label class="blend-formgroup-label"></label>
        <input type="text" name="address" id='address' class="blend-formgroup-input" placeholder="<?php echo ($memberinfo["address"]); ?>">
    </div>
    
    <div class="" style=" margin-top:30px;">
    <button class="blend-button blend-button-primary blend-button-large" id="submit" style=" background-color:#03ab9e;">提交</button>
    </div>
    <div style="height: 50px;"></div>
    <!--菜单入口-->
    


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

            var province = $("#province").val();
            var city = $("#city").val();
            var address = $("#address").val();
            if(address==''){

                alert('地址未变更!');
                return false;
            }
            var url = "<?php echo U('M/Member/modify_location');?>";
            $.ajax({
                type:'POST',
                url:url,
                data:{province:province,city:city,address:address},
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