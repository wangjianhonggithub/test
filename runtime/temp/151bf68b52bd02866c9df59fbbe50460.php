<?php /*a:1:{s:75:"/home/wwwroot/hotyiranjianshen.com/application/manage/view/login/login.html";i:1572232908;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <title>鼎智诚后台登录系统</title>
    <link rel="icon" type="image/png" href="assets/manage/i/favicon.ico"/>

	<meta name="description" content="particles.js is a lightweight JavaScript library for creating particles.">
	<meta name="author" content="Vincent Garreau" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	
	<!-- 国内使用 -->
	<script type="text/javascript" charset="utf-8" src="//g.alicdn.com/sd/nch5/index.js?t=2015052012"></script>

    <link rel="stylesheet" href="assets/layui/css/layui.css"/>
    <link rel="stylesheet" href="assets/manage/css/swiper.min.css"/>
    <link rel="stylesheet" href="assets/manage/css/login/style.css"/>
	
</head>
<style>
#layui-layer-shade1 {display:block}
#layui-layer1 {display:block}
</style>
<body>
	<div id="particles-js" style="display: flex;align-items: center;justify-content: center"></div>

	<div class="container">
		<div class="login-body">
			<div class="swep-left">
				<div>
					<div class="swiper-container">
						<div class="swiper-wrapper">
							<div class="swiper-slide"><img src="/assets/manage/img/login_bg.jpg"></div>
							<div class="swiper-slide"><img src="/assets/manage/img/login_bg1.jpg"></div>
							<div class="swiper-slide"><img src="/assets/manage/img/login_bg2.jpg"></div>
						</div>
						<!-- Add Pagination -->
						<div class="swiper-pagination"></div>
					</div>
				</div>
				<!-- <div class="denl">
					<p class="denl-p">登录方式</p>
					<ul>
						<li>
							<img src="/assets/manage/img/alipay.png"/>
							<p>	支付宝</p>
						</li>
						<li>
							<img src="/assets/manage/img/wxpay.png"/>
							<p>	微信</p>
						</li>
						<li>
							<img src="/assets/manage/img/QQ.png"/>
							<p>	QQ</p>
						</li>
					</ul>
				</div>  -->
			</div>
			<div class="login-content">
				<div class="brand">
					<img alt="logo" style='width: 220px;' src="/assets/manage/img/logo_text.png">
					<!-- <h2 class="brand-text">鼎智诚科技</h2> -->
				</div>
				<form id="login-form" class="login-form">
					<div class="form-group">
						<input name="login[user_name]" placeholder="请输入用户名" type="text" required>
					</div>
					<div class="form-group">
						<input name="login[password]" placeholder="请输入密码" type="password" required>
					</div>
					<input type="hidden" name="login[type]" id="type" value="login"/>
					<div class="form-group" style="margin-bottom:-21px;">
						<button id="login-submit" type="submit">登录</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>

<script src="assets/manage/css/login/particles.js"></script>
<script src="assets/manage/css/login/app.js"></script>

<script src="assets/manage/js/jquery.min.js"></script> 
<script src="assets/manage/js/swiper.min.js"></script>
<script src="assets/manage/js/jquery.form.min.js"></script>
<script src="assets/layer/layer.js"></script>
<script>
	// 表单提交
	var $form = $('#login-form');
	$form.submit(function () {
		var $btn_submit = $('#login-submit');
		//$btn_submit.attr("disabled", true);
		$form.ajaxSubmit({
			type: "post",
			dataType: "json",
			// url: '',
			success: function (result) {
				$btn_submit.attr('disabled', false);
				if (result.code === 1) {
					layer.msg(result.msg, {time: 1500, anim: 1}, function () {
						window.location = result.url;
					});
					return true;
				}
				layer.msg(result.msg, {time: 1500, anim: 6});
			}
		});
		return false;
	});
  
	var swiper = new Swiper('.swiper-container', {
		loop : true,
		autoplay: {
			delay: 3000,
		},
		pagination: {
			el: '.swiper-pagination',
		},
	});

	// layer.open({
	//   type: '提示', 
	//   area: ['300px','200px'],
	//   content: '\<div style="line-height:170px; text-align:center;">交流群号：136488250\</div>'
	// });
		
</script>

</html>
