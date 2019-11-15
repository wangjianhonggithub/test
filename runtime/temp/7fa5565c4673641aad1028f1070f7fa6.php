<?php /*a:1:{s:81:"/home/wwwroot/hotyiranjianshen.com/application/index/view/wxauth/authsuccess.html";i:1572507569;}*/ ?>
<!DOCTYPE html>
<html>
<head>
	<title>HOT易燃健身</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
</head>
<style type="text/css">
	img{ margin: 0 auto;display: block; }
	h2{ text-align: center;}
</style>
<body>
<div style="width: 100%;margin-top: 10%;">
	<img src="/assets/success.png">
	<h2>授权成功</h2>
</div>
</body>
<script type="text/javascript">
	//设定倒数秒数    倒计时跳走
  	var t = 3;  
  	//显示倒数秒数  
  	function showTime(){  
     	t -= 1;  
     	// document.getElementById('sec').innerHTML= t;  
     	if(t==0){ 
         	document.addEventListener('WeixinJSBridgeReady', function(){ 
         		WeixinJSBridge.call('closeWindow'); 
         	}, false);
			WeixinJSBridge.call('closeWindow');
     	}  
     	//每秒执行一次,showTime()  
     	setTimeout("showTime()",1000);  
  	}  
  	//执行showTime()  
  	showTime(); 
</script>
</html>