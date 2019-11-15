<?php /*a:2:{s:75:"/home/wwwroot/hotyiranjianshen.com/application/manage/view/sys/menurun.html";i:1570849289;s:70:"/home/wwwroot/hotyiranjianshen.com/application/manage/view/layout.html";i:1572247373;}*/ ?>
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
            <!-- <div class="tpl-header-button"><a href="<?php echo url('Sys/menulist'); ?>" title="系统配置(非开发勿动)"><i class="layui-icon" style="font-size: 20px;">&#xe614;</i></a></div> -->

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
    <!-- <?php
        dump($url);
        dump($menu);
    ?> -->
    <div class="layui-row">
        <div class="layui-col-sm12 layui-col-md12 layui-col-lg12">
			<div class="widget-head">
				<div class="widget-title"><?php if(($comm=='')): ?>新增<?php else: ?>编辑<?php endif; ?>菜单权限</div>
			</div>
			<form id="myform" class="layui-form" method="post">
				<div class="widget-body layui-col-lg12"> 
                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">所属分类 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <select id="pid" name="pid" lay-search>
                                <option <?php if($comm['pid']==0): ?> selected <?php endif; ?> value="0">｜－顶级分类</option>
                                <?php foreach($lists as $value): ?>
                                    <option <?php if($value['id']==$comm['pid']): ?> selected <?php endif; ?> value="<?php echo htmlentities($value['id']); ?>">｜－<?php echo htmlentities($value['name']); ?></option>
                                    <?php if(($value['son'])): foreach($value['son'] as $val): ?>
                                            <option <?php if($val['id']==$comm['pid']): ?> selected <?php endif; ?> value="<?php echo htmlentities($val['id']); ?>">　　｜－<?php echo htmlentities($val['name']); ?></option>
                                            <?php if(($val['son'])): foreach($val['son'] as $vvv): ?>
                                                    <option <?php if($vvv['id']==$comm['pid']): ?> selected <?php endif; ?> value="<?php echo htmlentities($vvv['id']); ?>">　　　　｜－<?php echo htmlentities($vvv['name']); ?></option>
                                                    <?php if(($vvv['son'])): foreach($vvv['son'] as $v): ?>
                                                            <option disabled>　　　　　　｜－<?php echo htmlentities($v['name']); ?></option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">名称 </label>
                        <div class="layui-col-sm4 layer-midd-left">
                            <input type="text" class="layui-input" name="name" value="<?php echo htmlentities($comm['name']); ?>" placeholder="请输入名称" required>
                        </div>
                        <div class="layui-form-mid layui-word-aux">顶级分类填写两个汉字</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">图标 </label>
                        <div class="layui-col-sm4 layer-midd-left">
                            <input type="text" class="layui-input" name="icon" value="<?php echo htmlentities($comm['icon']); ?>" placeholder="请输入图标">
                        </div>
                        <div class="layui-form-mid layui-word-aux">图标只取中间一段 例如: xe68e<br>顶级分类必须填写图标 <a href="https://www.layui.com/doc/element/icon.html" target="_blank" rel="noopener noreferrer">图标地址</a></div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">路径 </label>
                        <div class="layui-col-sm4 layer-midd-left">
                            <input type="text" class="layui-input" name="url" value="<?php echo htmlentities($comm['url']); ?>" placeholder="请输入路径" required>
                        </div>
                        <div class="layui-form-mid layui-word-aux">输入包含模块的完整路径(不可重复)<br>manage/index/index</div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">排序 </label>
                        <div class="layui-col-sm2 layer-midd-left">
                            <input type="number" class="layui-input" name="sort" value="<?php echo $comm['sort']=='' ? 100 : htmlentities($comm['sort']); ?>" placeholder="请输入排序" required>
                        </div>
                        <label class="layui-form-label">状态　</label>
                        <div>
                            <input type="radio" name="ishow" value="1" title="展示" <?php if(($comm['ishow']==1)): ?>checked<?php else: ?><?php endif; if(($comm=='')): ?>checked<?php endif; ?>>　
                            <input type="radio" name="ishow" value="2" title="隐藏" <?php if(($comm['ishow']==2)): ?>checked<?php else: ?><?php endif; ?>>
                        </div>
                    </div>

					<div class="layui-form-item">
						<button id="mysubmit" type="submit" class="j-submit layui-btn">提交</button>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>
<script>
    // 表单提交
    var $form = $('#myform');
    $form.submit(function () {

        var pid = $("#pid option:selected").val();
        if(pid == 0){
            var icon = $("input[ name='icon']").val();
            if(icon == ''){
                layer.msg('顶级分类必须填写图标', function () {});
                $("input[ name='icon']").focus();return false;
            }
        }

        var $mysubmit = $('#mysubmit');
        // $mysubmit.attr("disabled", true);

        $form.ajaxSubmit({
            type: "post",
            dataType: "json",
            url: "<?php echo url('menurun',['id'=>$comm['id']]); ?>",
            success: function (result) {
                $mysubmit.attr('disabled', false);
                if (result.code === 1) {
                    layer.msg(result.msg, { time: 1500, anim: 1 }, function () {
                        window.location = result.url;
                    });
                    return true;
                }
                layer.msg(result.msg, { time: 1500, anim: 6 }, function () {
                    // window.location = result.url;
                });
            }
        });
        return false;
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
