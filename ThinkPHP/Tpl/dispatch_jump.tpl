<?php
    if(C('LAYOUT_ON')) {
        echo '{__NOLAYOUT__}';
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<link rel="stylesheet" href="__PUBLIC__/assets/css/font-awesome.min.css">
<title>跳转提示</title>
<style type="text/css">
*{ padding: 0; margin: 0; }
body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
.system-message{ width: 800px; margin-left: auto;margin-right: auto; }
.system-message h1{ font-size: 100px; font-weight: normal; line-height: 120px;margin-top: 20px; margin-bottom: 12px; margin-left: auto; margin-right: auto; width: 100%;text-align: center; }
.system-message .jump{ padding-top: 10px;text-align: center;}
.system-message .jump a{ color: #333;}
.system-message .success,.system-message .error{ line-height: 1.8em; font-size: 20px; text-align: center; }
.system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none}
</style>
</head>
<body>
  <header class="am-topbar admin-header">

  <div class="am-collapse am-topbar-collapse" id="topbar-collapse">

    
  </div>
</header>
        
<div class="system-message" style="margin-top:300px;">
    
<?php if(isset($message)) {?>
<h1><i class="am-icon-cog am-icon-spin"></i></h1>
<p class="success" style="color:red;">提示:<?php echo($message); ?></p>
<?php }else{?>
<h1><i class="am-icon-cog am-icon-spin"></i></h1>
<p class="error" style="color:red;">提示:<?php echo($error); ?></p>
<?php }?>
<p class="detail"></p>
<p class="jump">
<i class="icon-spinner icon-spin orange bigger-125"></i>
页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
</p>
</div>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>
</body>
</html>
