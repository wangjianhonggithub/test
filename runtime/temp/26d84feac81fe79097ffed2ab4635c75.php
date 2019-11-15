<?php /*a:2:{s:81:"E:\PHPTutorial\WWW\dzcyiranjianshen\application\manage\view\market\packs\run.html";i:1572423207;s:71:"E:\PHPTutorial\WWW\dzcyiranjianshen\application\manage\view\layout.html";i:1572926623;}*/ ?>
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
				<div class="widget-title"><?php if(($comm=='')): ?>新增<?php else: ?>编辑<?php endif; ?>课程包</div>
			</div>
			<form id="myform" class="layui-form" method="post">
				<div class="widget-body layui-col-lg12"> 
                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">使用分类 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <select id="cid" name="cid" lay-search>
                                <option <?php if($comm['cid']==0): ?> selected <?php endif; ?> value="">请选择</option>
                                <option <?php if($comm['cid']==0): ?> selected <?php endif; ?> value="0">全部</option>
                                <?php foreach($classlist as $value): ?>
                                    <option <?php if($value['id']==$comm['cid']): ?> selected <?php endif; ?> value="<?php echo htmlentities($value['id']); ?>"><?php echo htmlentities($value['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                   
                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">课程包名称 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <input type="text" class="layui-input" name="name" value="<?php echo htmlentities($comm['name']); ?>" placeholder="请输入名称" required>
                        </div>
                        <div class="layui-form-mid layui-word-aux"><?php if(($comm['sid'])): ?>课程包编号:<?php echo htmlentities($comm['sid']); ?><?php endif; ?></div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">课程包价格 </label>
                        <div class="layui-col-sm2 layer-midd-left">
                            <input type="number" class="layui-input" id="price" name="price" value="<?php echo htmlentities($comm['price']); ?>" placeholder="￥" required>
                        </div>
                        <label class="layui-form-label form-require">排序 </label>
                        <div class="layui-col-sm2 layer-midd-left">
                            <input type="number" class="layui-input" name="sort" value="<?php echo $comm['sort']=='' ? 99 : htmlentities($comm['sort']); ?>" placeholder="请输入排序" required>
                        </div>
                        <label class="layui-form-label">状态　</label>
                        <div>
                            <input type="radio" name="ishow" value="1" title="展示" <?php if(($comm['ishow']==1)): ?>checked<?php else: ?><?php endif; if(($comm=='')): ?>checked<?php endif; ?>>　
                            <input type="radio" name="ishow" value="2" title="隐藏" <?php if(($comm['ishow']==2)): ?>checked<?php else: ?><?php endif; ?>>
                        </div>
                        <div class="layui-form-mid layui-word-aux"></div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">包含课时 </label>
                        <div class="layui-col-sm2 layer-midd-left">
                            <input type="number" class="layui-input" id="tothour" name="tothour" value="<?php echo htmlentities($comm['tothour']); ?>" placeholder="请输入课程包包含课时" required>
                        </div>

                        <label class="layui-form-label form-require">有效天数 </label>
                        <div class="layui-col-sm2 layer-midd-left">
                            <input type="number" class="layui-input" id="validays" name="validays" value="<?php echo $comm['validays']=='' ? 1 : htmlentities($comm['validays']); ?>" placeholder="请输入有效天数" required>
                        </div>

                        <div class="layui-form-mid layui-word-aux">
                            课程包为用户购买后计算有效天数
                            <br>例如课程包有效期为15天,用户一月一日购买
                            <br>则课程包有效期为 一月一日至一月十五日 (过期无效) 
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">平均每次 </label>
                        <div class="layui-col-sm2 layer-midd-left">
                            <input type="number" class="layui-input" name="everyuan" value="<?php echo htmlentities($comm['everyuan']); ?>" placeholder="￥" required>
                        </div>
                        <label class="layui-form-label form-require">最多节省 </label>
                        <div class="layui-col-sm2 layer-midd-left">
                            <input type="number" class="layui-input" name="saveyuan" value="<?php echo htmlentities($comm['saveyuan']); ?>" placeholder="￥" required>
                        </div>
                        <div class="layui-form-mid layui-word-aux">
                            此处两项金额仅做展示用,请输入正整数
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">课程包介绍 </label>
                        <div class="layui-col-sm6 layer-midd-left">
                            <textarea name="intro" required placeholder="请输入课程包介绍" class="layui-textarea"><?php echo htmlentities($comm['intro']); ?></textarea>
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
        // var cid = $("#cid option:selected").val();
        // if (cid == 0) {     layer.msg('请选择使用分类', function () { });return false;}

        var price = $("input[name='price']").val();
        if(price <= 0){     layer.msg('价格请输入正整数', function () { }); return false;}

        var tothour = $("input[name='tothour']").val();
        if(tothour <= 0){    layer.msg('包含课时请输入正整数', function () { }); return false;}
        
        var validays = $("input[name='validays']").val();
        if(validays <= 0){    layer.msg('有效天数请输入正整数', function () { }); return false;}
        
        var everyuan = $("input[name='everyuan']").val();
        if(everyuan <= 0){    layer.msg('金额请输入正整数', function () { }); return false;}
        
        var saveyuan = $("input[name='saveyuan']").val();
        if(saveyuan <= 0){    layer.msg('金额请输入正整数', function () { }); return false;}
        
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
