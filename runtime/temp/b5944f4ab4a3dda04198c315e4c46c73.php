<?php /*a:2:{s:87:"E:\PHPTutorial\WWW\dzcyiranjianshen\application\manage\view\distribution\grade_run.html";i:1573796190;s:71:"E:\PHPTutorial\WWW\dzcyiranjianshen\application\manage\view\layout.html";i:1572926623;}*/ ?>
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
        <div class="layui-container">
    <div class="layui-row">
        <div class="layui-col-sm12 layui-col-md12 layui-col-lg12">
            <div class="widget-head">
                <div class="widget-title"><?php if(($comm=='')): ?>新增<?php else: ?>编辑<?php endif; ?>分销商等级</div>
            </div>
            <form id="myform" class="layui-form" method="post">



                <div class="widget-body layui-col-lg12">

                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">等级名称 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <input type="text" class="layui-input" name="level_name" value="<?php echo htmlentities($comm['level_name']); ?>" placeholder="请输入等级名称">
                        </div>
                        <div class="layui-form-mid layui-word-aux"></div>
                    </div>


                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">分销等级 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <input type="text" class="layui-input" name="level_type" value="<?php echo htmlentities($comm['level_type']); ?>" placeholder="请输入分销等级">
                        </div>
                        <div class="layui-form-mid layui-word-aux">数值与等级成正比关系</div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">简介 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <input type="text" class="layui-input" name="introduction" value="<?php echo htmlentities($comm['introduction']); ?>" placeholder="请输入加盟商简介" >
                        </div>
                        <div class="layui-form-mid layui-word-aux">加盟商简介</div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">一级佣金比例 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <input type="text" class="layui-input" name="rate1" value="<?php echo htmlentities($comm['rate1']); ?>" placeholder="请输入一级佣金比例" >
                        </div>
                        <div class="layui-form-mid layui-word-aux">处于一级上线时获得的佣金比例%</div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">二级佣金比例 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <input type="text" class="layui-input" name="rate2" value="<?php echo htmlentities($comm['rate2']); ?>" placeholder="请输入二级佣金比例" >
                        </div>
                        <div class="layui-form-mid layui-word-aux">处于二级上线时获得的佣金比例%</div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">三级佣金比例 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <input type="text" class="layui-input" name="rate3" value="<?php echo htmlentities($comm['rate3']); ?>" placeholder="请输入三级佣金比例" >
                        </div>
                        <div class="layui-form-mid layui-word-aux">处于三级上线时获得的佣金比例%</div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">加盟价格 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <input type="text" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" class="layui-input" name="join_price" value="<?php echo htmlentities($comm['join_price']); ?>" placeholder="请输入加盟价格(元)" >
                        </div>
                        <div class="layui-form-mid layui-word-aux">分销商加盟条件，支付该金额即可享受对应的分佣规则</div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">推荐奖励 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <input type="text" class="layui-input" name="reward" value="<?php echo htmlentities($comm['reward']); ?>" placeholder="请输入推荐奖励(元)" >
                        </div>
                        <div class="layui-form-mid layui-word-aux">推荐人发展一个该级别的分销商获得奖励金额</div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">升级条件 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <input type="text" class="layui-input" name="order_money" value="<?php echo htmlentities($comm['order_money']); ?>" placeholder="请输入升级条件(元)" >
                        </div>
                        <div class="layui-form-mid layui-word-aux">分销商升级条件，不填写默认为不自动升级</div>
                    </div>






                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">等级说明 </label>
                        <div class="layui-col-sm6 layer-midd-left">
                            <textarea name="level_intro" placeholder="请输入介绍" class="layui-textarea"><?php echo htmlentities($comm['level_intro']); ?></textarea>
                        </div>
                        <div class="layui-form-mid layui-word-aux"></div>
                    </div>

                    <div class="layui-form-item">
                        <button id="mysubmit" type="submit" class="layui-btn">提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function () {



        $('#myform').submit(function () {
            var level_name = $("input[name='level_name']").val();
            if(!level_name){     layer.msg('请输入等级名称', function () { }); return false;}

            var level_type = $("input[name='level_type']").val();
            if(!level_type){    layer.msg('请输入分销等级', function () { }); return false;}

            var introduction = $("input[name='introduction']").val();
            if(!introduction){    layer.msg('请输入简介', function () { }); return false;}

            var rate1 = $("input[name='rate1']").val();
            if(!rate1){    layer.msg('请输入一级佣金比例', function () { }); return false;}

            var rate2 = $("input[name='rate2']").val();
            if(!rate2){    layer.msg('请输入二级佣金比例', function () { }); return false;}

            var rate3 = $("input[name='rate3']").val();
            if(!rate3){    layer.msg('请输入三级佣金比例', function () { }); return false;}

            var join_price = $("input[name='join_price']").val();
            if(!join_price){    layer.msg('请输入加盟价格', function () { }); return false;}

            var reward = $("input[name='reward']").val();
            if(!reward){    layer.msg('请输入推荐奖励', function () { }); return false;}

            var level_intro = $("textarea[name='level_intro']").val();
            if(!level_intro){    layer.msg('请输入等级说明', function () { }); return false;}



            ajaxrun('myform');
            return false;
        });



    });
</script>

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
