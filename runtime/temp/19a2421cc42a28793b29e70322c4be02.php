<?php /*a:2:{s:75:"E:\PHPTutorial\WWW\dzcyiranjianshen\application\manage\view\item\reply.html";i:1573640194;s:71:"E:\PHPTutorial\WWW\dzcyiranjianshen\application\manage\view\layout.html";i:1572926623;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>鼎智诚后台管理系统</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="renderer" content="webkit"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="icon" type="image/png" href="assets/manage/i/favicon.ico"/>
    <link rel="stylesheet" href="assets/manage/css/dzcstyle.css"/>
    <!-- <link rel="stylesheet" href="/assets/manage/vendors/iconfonts/mdi/css/materialdesignicons.min.css"/> -->
    <link rel="stylesheet" href="/assets/manage/vendors/css/vendor.bundle.base.css"/>
	<link rel="stylesheet" href="assets/layui/css/layui.css"  media="all">
    <script src="assets/manage/js/jquery.min.js"></script>
    <script src="assets/manage/js/global.js"></script>
    <script>
        BASE_URL = '<?php echo htmlentities($base_url); ?>';
        STORE_URL =  '/index.php?s=/manage';
    </script>
</head>

<body data-type="">
<div class="layer-g tpl-g">
    <!-- 头部 -->
    <header class="tpl-header">
        <!-- 右侧内容 -->
        <div class="tpl-header-fluid">
            <!-- 侧边切换 -->
            <div class="layer-fl tpl-header-button switch-button">
                <i class="iconfont icon-menufold"></i>
            </div>
            <!-- 刷新页面 -->
            <div class="layer-fl tpl-header-button refresh-button">
				<div class="tpl-header-text-gl">
                    <a href="javascript:void(0)" οnclick="location.reload()" title="刷新页面"><i class="layui-icon">&#xe9aa;</i></a>
                </div>
            </div>
            <!-- 系统配置 -->
            <!-- <div class="layer-fl tpl-header-button refresh-button">
				<div class="tpl-header-text-gl">
                    <a href="javascript:void(0)" title="回收站"><i class="layui-icon" style="font-size: 20px;">&#xe640;</i></a>
                </div>
            </div> -->
             <div class="tpl-header-button"><a href="<?php echo url('Sys/menulist'); ?>" title="系统配置(非开发勿动)"><i class="layui-icon" style="font-size: 20px;">&#xe614;</i></a></div>

            <!-- 其它功能-->
            <div class="layer-fr tpl-header-navbar">
                <ul>
                    <!-- 欢迎语 -->
                    <li class="layer-text-sm tpl-header-navbar-welcome">
                        <a href="<?php echo url('My/myself'); ?>">欢迎你，<span><?php echo htmlentities($dzcadmin['user_name']); ?></span>
                        </a>
                    </li>
					<!-- 意见箱 -->
					<!-- <li>
					    <a href="<?php echo url('index/proposal'); ?>">提交建议</a>
					</li> -->
					
                    <!-- 退出 -->
                    <li class="layer-text-sm">
                        <a href="javascript:void(0)" onclick="logout()">
                            <i class="iconfont icon-tuichu"></i>退出登录
                        </a>
                    </li>
                    <script>
                        function logout() {
                            layer.confirm('确定退出登录？', {
                                title:'友情提示',
                                btn: ['确定', '取消'] //按钮
                            }, function () {
                                location.href = "<?php echo url('login/logout'); ?>";
                            }, function () {} );
                        }
                    </script>
                </ul>
            </div>
        </div>
    </header>
    <!-- 侧边导航栏 -->
    <div class="left-sidebar dis-flex">
        <!-- 一级菜单 -->
        <ul class="sidebar-nav">
            <li class="sidebar-nav-heading"><img class="logo" src="/assets/manage/img/logo.png" width="60" /></li>
            <?php if(is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
				<li class="sidebar-nav-link">
                    <a href="<?= isset($item['url']) ? url($item['url']) : 'javascript:void(0);' ?>" class="<?php if($item['model'] == $group): ?> active <?php endif; ?>">
                        <i class="layui-icon">&#<?php echo htmlentities($item['icon']); ?>;</i> <?php echo htmlentities($item['name']); ?>
                    </a>
                </li>
			<?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <!-- 子级菜单-->
		<?php $second = $menu[$group]; if(isset($second['list'])): ?>
            <ul class="left-sidebar-second">
                <!-- <li class="sidebar-second-title"><?php echo htmlentities($menu[$group]['name']); ?></li> -->
                <li class="sidebar-second-item">
                   
					<?php if(is_array($second['list']) || $second['list'] instanceof \think\Collection || $second['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $second['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <!-- 二级菜单-->
                        <a href="<?php echo url($item['url']); ?>" class="two-active"><?php echo htmlentities($item['name']); ?></a>
                    
                        <!-- 三级菜单-->
                        <div class="sidebar-third-item">
                            <!--  <a href="javascript:void(0);"
                                class="sidebar-nav-sub-title <?php echo !empty($item['active']) ? 'active'  :  ''; ?>">
                                <i class="iconfont icon-caret"></i>
                                <?php echo htmlentities($item['name']); ?>
                            </a>  -->
                            <?php if(isset($item['list'])): ?>
                                <ul class="sidebar-third-nav-sub">
                                    <?php if(is_array($item['list']) || $item['list'] instanceof \think\Collection || $item['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $item['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$third): $mod = ($i % 2 );++$i;?>
                                        <li>
                                            <a class="<?php echo $third['url']==$url ? 'active'  :  ''; ?>" href="<?php echo url($third['url']); ?>"> <?php echo htmlentities($third['name']); ?></a>
                                        </li>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </li>
            </ul>
        <?php endif; ?>
    </div>

    <!-- 内容区域 start -->
    <div class="tpl-content-wrapper <?php if(isset($second['list']) == null): ?>no-sidebar-second<?php endif; ?>">
        <style>
    * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    .panel-title {
        margin-top: 0;
        margin-bottom: 0;
        font-size: 16px;
        color: inherit;
        font-weight: 100;
        font-size: 14px;
    }
    .direct-chat-msg:before, .direct-chat-msg:after {
        content: " ";
        display: table;
    }
    .fa {
        display: inline-block;
        font: normal normal normal 14px/1 FontAwesome;
        font-size: inherit;
        text-rendering: auto;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        transform: translate(0, 0);
    }
    :after, :before {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    .fa-list:before {
        content: "\f03a";
    }
    .btn {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: 400;
        line-height: 1.42857143;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
    }
    .pull-right {
        float: right!important;
    }
    .btn {
        border-radius: 3px;
        -webkit-box-shadow: none;
        box-shadow: none;
        border: 1px solid transparent;
    }
    .btn-default {
        background-color: #f4f4f4;
        color: #444;
        border-color: #ddd;
    }
    .panel-title>a{
        color: inherit;
    }
    .fa-mail-reply:before, .fa-reply:before {
        content: "\f112";
    }
    .panel-body {
        padding: 15px;
        overflow: hidden;
    }
    .btn-group-vertical>.btn-group:after, .btn-group-vertical>.btn-group:before, .btn-toolbar:after, .btn-toolbar:before, .clearfix:after, .clearfix:before, .container-fluid:after, .container-fluid:before, .container:after, .container:before, .dl-horizontal dd:after, .dl-horizontal dd:before, .form-horizontal .form-group:after, .form-horizontal .form-group:before, .modal-footer:after, .modal-footer:before, .nav:after, .nav:before, .navbar-collapse:after, .navbar-collapse:before, .navbar-header:after, .navbar-header:before, .navbar:after, .navbar:before, .pager:after, .pager:before, .panel-body:after, .panel-body:before, .row:after, .row:before {
        display: table;
        content: " ";
    }
    .box-header:before, .box-body:before, .box-footer:before, .box-header:after, .box-body:after, .box-footer:after {
        content: " ";
        display: table;
    }
    .row {
        margin-right: -15px;
        margin-left: -15px;
    }
    @media (min-width: 992px)
        .col-md-2 {
            width: 16.66666667%;
        }
        @media (min-width: 992px)
            .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9 {
                float: left;
            }
            .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
                position: relative;
                min-height: 1px;
                padding-right: 15px;
                padding-left: 15px;
            }
            @media (min-width: 992px)
                .col-md-8 {
                    width: 66.66666667%;
                }
                @media (min-width: 992px)
                    .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9 {
                        float: left;
                    }
                    .box {
                        position: relative;
                        border-radius: 3px;
                        background: #ffffff;
                        border-top: 3px solid #d2d6de;
                        margin-bottom: 20px;
                        width: 100%;
                        box-shadow: 0 1px 1px rgba(0,0,0,0.1);
                    }
                    .box-header {
                        color: #444;
                        display: block;
                        padding: 10px;
                        position: relative;
                    }
                    .box-header.with-border {
                        border-bottom: 1px solid #f4f4f4;
                    }
                    .box-header .box-title {
                        display: inline-block;
                        font-size: 18px;
                        margin: 0;
                        line-height: 1;
                        font-weight: 100;
                        font-size: 14px;
                    }
                    .box-body {
                        border-top-left-radius: 0;
                        border-top-right-radius: 0;
                        border-bottom-right-radius: 3px;
                        border-bottom-left-radius: 3px;
                        padding: 10px;
                    }
                    .direct-chat .box-body {
                        border-bottom-right-radius: 0;
                        border-bottom-left-radius: 0;
                        position: relative;
                        overflow-x: hidden;
                    }
                    .direct-chat-msg, .direct-chat-text {
                        display: block;
                    }
                    .direct-chat-msg {
                        margin-bottom: 10px;
                    }
                    .direct-chat-info {
                        display: block;
                        margin-bottom: 2px;
                        font-size: 12px;
                        overflow: hidden;
                    }
                    .direct-chat-name {
                        font-weight: 600;
                    }
                    .pull-left {
                        float: left;
                    }
                    .direct-chat-timestamp {
                        color: #999;
                    }
                    .direct-chat-img {
                        border-radius: 50%;
                        float: left;
                        width: 40px;
                        height: 40px;
                    }
                    .direct-chat-text {
                        border-radius: 5px;
                        position: relative;
                        padding: 5px 10px;
                        background: #d2d6de;
                        border: 1px solid #d2d6de;
                        margin: 5px 0 0 50px;
                        color: #444;
                    }
                    .container-fluid > .panel {
                        margin-bottom: 20px;
                        background-color: #fff;
                        border: 1px solid transparent;
                        border-radius: 4px;
                        -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
                        box-shadow: 0 1px 1px rgba(0,0,0,.05);
                        border-color: #ddd;
                    }

                    .content {
                        min-height: 250px;
                        margin-right: auto;
                        margin-left: auto;
                        overflow: hidden;
                        padding: 15px 0;
                    }
                    .container-fluid {
                        margin-right: auto;
                        margin-left: auto;
                    }

                    .panel-heading {
                        padding: 10px 15px;
                        border-bottom: 1px solid transparent;
                        border-top-left-radius: 3px;
                        border-top-right-radius: 3px;
                    }
                    .panel-default>.panel-heading {
                        color: #333;
                        background-color: #f5f5f5;
                        border-color: #ddd;
                    }
                    .form-control {
                        /* display: block; */
                        width: 100%;
                        padding: 6px 12px;
                        font-size: 14px;
                        line-height: 1.42857143;
                        color: #555;
                        background-color: #fff;
                        background-image: none;
                        border: 1px solid #ccc;
                        border-radius: 4px;
                        -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
                        box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
                        -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
                        -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
                        transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
                    }
                    .direct-right .direct-chat-img {
                        float: right;
                    }
                    textarea.form-control {
                        height: auto;
                    }
                    .form-group {
                        margin-bottom: 15px;
                    }
                    .margin {
                        margin: 10px;
                    }
                    .btn-primary {
                        background-color: #3c8dbc;
                        border-color: #367fa9;
                        color: white;
                    }
                    .direct-chat-msg:before, .direct-chat-msg:after {
                        content: " ";
                        display: table;
                    }


                    .direct-chat-img {
                        border-radius: 50%;
                        float: left;
                        width: 40px;
                        height: 40px;
                    }

                    .editable-tarea, .editable-tarea2, textarea {
                        line-height: 18px;
                        display: inline-block;
                        height: 36px;
                        cursor: text;
                        overflow: auto;
                    }

                    .form-control {
                        box-sizing: border-box;
                        width: 100%;
                        padding: 6px 12px;
                        height: 74px;
                        font-size: 14px;
                        line-height: 1.42857143;
                        color: #555;
                        background-color: #fff;
                        background-image: none;
                        border: 1px solid #ccc;
                        border-radius: 4px;
                        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
                        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
                        -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
                        -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
                        transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
                    }
                    .btn {
                        display: inline-block;
                        padding: 6px 12px;
                        margin-bottom: 0;
                        font-size: 14px;
                        font-weight: 400;
                        line-height: 1.42857143;
                        text-align: center;
                        white-space: nowrap;
                        vertical-align: middle;
                        -ms-touch-action: manipulation;
                        touch-action: manipulation;
                        cursor: pointer;
                        -webkit-user-select: none;
                        -moz-user-select: none;
                        -ms-user-select: none;
                        user-select: none;
                        background-image: none;
                        border: 1px solid transparent;
                        border-radius: 4px;
                    }
                    .btn {
                        border-radius: 3px;
                        -webkit-box-shadow: none;
                        box-shadow: none;
                        border: 1px solid transparent;
                    }
                    .right .direct-chat-img {
                        float: right;
                    }

                    .direct-chat-primary .right > .direct-chat-text {
                        background: #36BC9B;
                        border-color: #36BC9B;
                        color: #fff;
                    }

                    .right .direct-chat-text {
                        margin-right: 50px;
                        margin-left: 0;
                    }

                    .direct-chat-text:after, .direct-chat-text:before {
                        position: absolute;
                        right: 100%;
                        top: 15px;
                        border: solid transparent;
                        border-right-color: #d2d6de;
                        content: ' ';
                        height: 0;
                        width: 0;
                        pointer-events: none;
                    }

                    .direct-chat-text:before {
                        border-width: 6px;
                        margin-top: -6px;
                    }

                    .right .direct-chat-text:after, .right .direct-chat-text:before {
                        right: auto;
                        left: 100%;
                        border-right-color: transparent;
                        border-left-color: #d2d6de;
                    }

                    .direct-chat-primary .right > .direct-chat-text:after, .direct-chat-primary .right > .direct-chat-text:before {
                        border-left-color: #36BC9B;
                    }
                    .direct-chat-msg.right{
                        clear: both;
                    }
                    .direct-chat-info .pull-left {
                        margin-right: 20px;
                    }
</style>
<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<div class="page ">
    <!--<div class="fixed-bar">-->
        <!--<div class="item-title">-->
            <!--<div class="subject">-->
                <!--<h3>评价管理</h3>-->
                <!--<h5>商品交易评价管理</h5>-->
            <!--</div>-->
        <!--</div>-->
    <!--</div>-->

    <div class="wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-list"></i> 评论回复
                            <a data-original-title="返回" class="btn btn-default pull-right" style="margin-top:-8px;" title="" data-toggle="tooltip" href="javascript:history.go(-1)"><i class="fa fa-reply"></i></a>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <!-- DIRECT CHAT PRIMARY -->
                                <div class="box direct-chat direct-chat-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">用户评论</h3>
                                        <!--
                                        <div class="box-tools pull-right">
                                            <span class="badge bg-light-blue" title="3 New Messages" data-toggle="tooltip">3</span>
                                            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                                            <button data-widget="chat-pane-toggle" title="" data-toggle="tooltip" class="btn btn-box-tool" data-original-title="Contacts"><i class="fa fa-comments"></i></button>
                                            <button data-widget="remove" class="btn btn-box-tool"><i class="fa fa-times"></i></button>
                                        </div>
                                         -->
                                    </div><!-- /.box-header -->
                                    <div class="box-body">
                                        <!-- Conversations are loaded here -->
                                        <div class="">
                                            <!-- Message. Default to the left -->
                                            <div class="direct-chat-msg">
                                                <div class="direct-chat-info clearfix">
                                                    <span class="direct-chat-name pull-left"><?php echo htmlentities($comment['user_name']); ?></span>
                                                    <span class="direct-chat-timestamp pull-right"><?php echo date("Y-m-d H:i:s",$comment['create_time']); ?></span>
                                                </div><!-- /.direct-chat-info -->
                                                <img src="/uploads/user1-128x128.jpg" class="direct-chat-img"><!-- /.direct-chat-img -->
                                                <div class="direct-chat-text"><?php echo htmlentities($comment['content']); ?></div><!-- /.direct-chat-text -->
                                            </div><!-- /.direct-chat-msg -->

                                            <?php foreach($reply as $value): ?>
                                                <div class="direct-chat-msg right">
                                                    <div class="direct-chat-info clearfix direct-right">
                                                        <span class="direct-chat-name pull-right">管理员：<?php echo htmlentities($value['user_name']); ?></span>
                                                        <span class="direct-chat-timestamp pull-left"><?php echo date("Y-m-d H:i:s",$value['create_time']); ?></span>
                                                    </div><!-- /.direct-chat-info -->
                                                    <img alt="管理员" src="/uploads/default_user_portrait.gif" class="direct-chat-img"><!-- /.direct-chat-img -->
                                                    <div class="direct-chat-text" style="text-align: right"><?php echo htmlentities($value['content']); ?></div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <!-- /.direct-chat-pane -->
                                    </div><!-- /.box-body -->
                                    <!-- /.box-footer-->
                                </div><!--/.direct-chat -->
                            </div>
                            <div class="col-md-2"></div>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-8">
                                            <form id="myform" method="post">
                                                <input type="hidden" name="comment_id" value="<?php echo htmlentities($comment['id']); ?>">
                                                <textarea class="form-control" rows="3" placeholder="请输入回复内容" name="content"></textarea>
                                                <div class="form-group"><button type="submit" class="btn btn-primary pull-right margin">回复</button></div>
                                            </form>
                                        </div>
                                        <div class="col-md-2"></div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
</div>
<!-- /.content-wrapper -->
<script>
    $('#myform').submit(function () {
        var content = $("textarea[name='content']").val();
        if(!content.trim()){     layer.msg('请填写回复内容', function () { }); return false;}



        ajaxrun('myform',"<?php echo url('reply'); ?>");
        return false;
    });


</script>
</body>
</html>
    </div>
    <!-- 内容区域 end -->

</div>
    <script src="assets/manage/js/validform.min.js"></script> <!-- 提交 -->
    <script src="assets/manage/js/jquery.form.min.js"></script>
    <script src="assets/manage/js/webuploader.html5only.js"></script>
	<script src="assets/manage/js/art-template.js"></script>
	<script src="assets/manage/js/app.js"></script>
	<script src="assets/manage/js/file.library.js"></script>
	<script src="/assets/layui/layui.all.js" charset="utf-8"></script>

	<script src="assets/manage/he/public.js" charset="utf-8"></script>
</body>
</html>
