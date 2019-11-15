<?php /*a:2:{s:76:"E:\PHPTutorial\WWW\dzcyiranjianshen\application\manage\view\user\detail.html";i:1573785624;s:71:"E:\PHPTutorial\WWW\dzcyiranjianshen\application\manage\view\layout.html";i:1572926623;}*/ ?>
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
                <div class="widget-title">分销商信息</div>
            </div>
            <form id="myforms" class="layui-form" method="post">

                <input type="hidden" name="user_id" value="<?php echo htmlentities($comm['id']); ?>">

                <div class="widget-body layui-col-lg12">

                    <div class="layui-form-item">
                        <label class="layui-form-label  ">会员昵称 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <?php echo htmlentities($comm['nickname']); ?>
                        </div>
                        <div class="layui-form-mid layui-word-aux"></div>
                    </div>


                    <div class="layui-form-item">
                        <label class="layui-form-label  ">会员佣金 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                           <?php echo htmlentities($comm['commission']); ?>
                        </div>
                        <div class="layui-form-mid layui-word-aux"></div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label  ">手机号码 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <input type="text" class="layui-input" name="phone" value="<?php echo htmlentities($comm['phone']); ?>" >
                        </div>
                        <div class="layui-form-mid layui-word-aux"></div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label  ">性别 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <input type="radio" class="layui-input" name="gender" value="0" <?php if(($comm['gender']) == 0): ?>checked<?php endif; ?>>保密&nbsp;&nbsp;&nbsp;
                            <input type="radio" class="layui-input" name="gender" value="1" <?php if(($comm['gender']) == 1): ?>checked<?php endif; ?>>男&nbsp;&nbsp;&nbsp;
                            <input type="radio" class="layui-input" name="gender" value="2" <?php if(($comm['gender']) == 2): ?>checked<?php endif; ?>>女
                        </div>
                        <div class="layui-form-mid layui-word-aux"></div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label  ">身份 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <input type="radio" class="layui-input" name="is_grade" value="1" <?php if(($comm['is_grade']) == 1): ?>checked<?php endif; ?>>会员&nbsp;&nbsp;&nbsp;
                            <input type="radio" class="layui-input" name="is_grade" value="2" <?php if(($comm['is_grade']) == 2): ?>checked<?php endif; ?>>分销商&nbsp;&nbsp;&nbsp;
                        </div>
                        <div class="layui-form-mid layui-word-aux">分销商拥有会员的所有功能</div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label  ">分销功能 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <input type="radio" class="layui-input" name="isallow" value="1" <?php if(($comm['isallow']) == 1): ?>checked<?php endif; ?>>开启&nbsp;&nbsp;&nbsp;
                            <input type="radio" class="layui-input" name="isallow" value="2" <?php if(($comm['isallow']) == 2): ?>checked<?php endif; ?>>关闭&nbsp;&nbsp;&nbsp;
                        </div>
                        <div class="layui-form-mid layui-word-aux">如果关闭，分销商不允许使用分销功能</div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label  ">分销等级 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <select class="layui-inline " lay-search name="lid">
                                <?php foreach($level as $v): ?>
                                <option value="<?php echo htmlentities($v['id']); ?>" <?php if(($comm["lid"]==$v['id'])): ?>selected<?php endif; ?>><?php echo htmlentities($v['level_name']); ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label  ">更改上级 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <input type="text" class="layui-input" name="parent_phone" value="<?php echo htmlentities($comm['parent_phone']); ?>" placeholder="请输入上级手机号码搜索" >
                        </div>

                        <div class="layui-form-mid layui-word-aux tpl-table-black-operation">
                            <a href="javascript:void();"
                               class="item-check tpl-table-black-operation-primary""
                               data-id="<?php echo htmlentities($item['id']); ?>">
                                <i class="layer-icon-pencil"></i> 检测
                            </a>
                            <a href="javascript:void();"
                               class="item-confirm tpl-table-black-operation-primary""
                               data-id="<?php echo htmlentities($item['id']); ?>">
                                <i class="layer-icon-pencil"></i> 确认更改
                            </a>
                        </div>
                    </div>



                    <div class="layui-form-item">
                        <button id="mysubmits" type="submit" class="layui-btn">确认提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function () {


        $('.item-check').click(function(){
            var user_id = $("input[name='user_id']").val();
            var parent_phone = $("input[name='parent_phone']").val();
            if(!parent_phone){     layer.msg('手机号码不能为空', function () { }); return false;}
            $.ajax({
                type: 'GET',
                url: "<?php echo url('distribution/check_phone'); ?>",
                data: {
                    parent_phone:parent_phone,
                    id:user_id,
                },
                dataType: 'json',
                success: function(data){
                    if (data.code == 0) {
                        layer.msg(data.meg, function(){
                            //关闭后的操作
                        });
                        return false;
                    }else{
                        layer.msg(data.meg, function(){
                            //关闭后的操作
                        });

                    }
                },
            });
        })

        $('.item-confirm').click(function(){
            var user_id = $("input[name='user_id']").val();
            var parent_phone = $("input[name='parent_phone']").val();
            if(!parent_phone){     layer.msg('手机号码不能为空', function () { }); return false;}
            $.ajax({
                type: 'GET',
                url: "<?php echo url('distribution/confirm_phone'); ?>",
                data: {
                    parent_phone:parent_phone,
                    id:user_id,
                },
                dataType: 'json',
                success: function(data){
                    if (data.code == 0) {
                        layer.msg(data.meg, function(){
                            //关闭后的操作
                        });
                        return false;
                    }else{
                        layer.msg(data.meg, function(){
                            //关闭后的操作
                        });
                        setTimeout(function(){
                            window.location.reload();
                        },1500);
                    }
                },
            });
        });


        $('#mysubmits').click(function(){
            var formData = new FormData($('#myforms')[0]);
            $.ajax({
                type: 'POST',
                url:"<?php echo url('user/detail'); ?>",
                data:formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data){
                    if (data.code == 1) {
                        layer.msg(data.meg, function(){
                            //关闭后的操作
                        });
                        setTimeout(function(){

                        },1500);
                    }else{
                        layer.msg(data.meg, function(){
                            //关闭后的操作
                        });
                        return false;
                    }
                },
            });
        })



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
