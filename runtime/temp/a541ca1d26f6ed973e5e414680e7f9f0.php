<?php /*a:2:{s:79:"/home/wwwroot/hotyiranjianshen.com/application/manage/view/user/couponlist.html";i:1572253582;s:70:"/home/wwwroot/hotyiranjianshen.com/application/manage/view/layout.html";i:1572247373;}*/ ?>
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
    <div class="layui-row">
        <div class="layui-col-md12 layui-col-xs12">
            <div class="widget-head">
                <!-- <div class="widget-title">课程包列表　　<a class="layui-btn-sm layui-btn-primary" href="<?php echo url('index'); ?>">返回列表</a></div> -->
                <div class="widget-title">优惠券列表</div>
            </div>
        </div>
        <div class="layui-col-md12 layui-col-xs12 widget-body ">
            <div class="layui-form-item layui-col-md1">
                <img src="<?php echo htmlentities($usercomm['headimg']); ?>" width="60" height="60" alt="">
            </div>

            <form class="layui-form layui-col-md11" method="post" action="">
                <div class="layui-form-item">
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label">会员编号：</label>
                        <div class="layui-input-inline" >
                            <input name="uid" type="text" class="layui-input" placeholder="请输入会员编号" value="<?php echo htmlentities($usercomm['uid']); ?>" readonly>
                        </div>
                    </div>

                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label">昵称：</label>
                        <div class="layui-input-inline" >
                            <input name="nickname" type="text" class="layui-input" placeholder="请输入会员昵称" value="<?php echo htmlentities($usercomm['nickname']); ?>" readonly>
                        </div>
                    </div>
					<div class="layui-inline layui-col-md3">
						<label class="layui-form-label">性别：</label>
						<div class="layui-input-inline">
							<select class="layui-inline " lay-search name="gender" data-layer-selected="{btnSize: 'sm', placeholder: '性别'}" disabled>
								<option value="0" <?php if(($usercomm["gender"]==0)): ?>selected<?php endif; ?>>全部 </option> 
								<option value="1" <?php if(($usercomm["gender"]==1)): ?>selected<?php endif; ?>>男 </option> 
								<option value="2" <?php if(($usercomm["gender"]==2)): ?>selected<?php endif; ?>>女 </option>
							</select> 
						</div> 
					</div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <!-- <a class="layui-btn" href="<?php echo url('courserun'); ?>">发放优惠券</a> -->
                        <a class="layui-btn layui-btn-primary" href="javascript:;" onclick="getloglist(<?php echo htmlentities($usercomm['uid']); ?>)">历史记录</a>
                    </div>
                </div>
            </form>
            <div class="layui-col-md12">
                <table class="layui-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>名称</th>
                            <th style="width:12%;">详情描述</th>
                            <th>使用范围</th>
                            <th>满足金额</th>
                            <th>减免金额</th>
                            <th>领取日期</th>
                            <th>有效期</th>
                            <th>过期日期</th>
                            <!-- <th>操作</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php if((empty($list))): ?>
                            <tr>
                                <td colspan="99" class="layer-text-center">暂无记录</td>
                            </tr>
                        <?php else: foreach($list as $value): ?>
                                <tr>
                                    <td><?php echo htmlentities($value['id']); ?></td>
                                    <td><?php echo htmlentities($value['name']); ?></td>
                                    <td title="<?php echo htmlentities($value['intro']); ?>">
                                        <?php echo mb_strlen($value['intro'], 'utf-8') > 6 ? mb_substr($value['intro'], 0, 6, 'utf-8').'...' : $value['intro'];; ?>
                                    </td>
                                    <td><?php echo htmlentities($value['classname']); ?></td>

                                    <td>￥<?php echo htmlentities($value['manyuan']); ?></td>
                                    <td>￥<?php echo htmlentities($value['jianyuan']); ?></td>
                                    <td><?php echo date("Y-m-d H:i",$value['addstamp']); ?></td>
                                    <td><?php echo htmlentities($value['validays']); ?>天</td>
                                    <td><?php echo date("Y-m-d",$value['pastamp']); ?></td>
                                    <!-- <td>
                                        <div class="tpl-table-black-operation">
                                            <a class="tpl-table-black-operation-primary" href="javascript:;" onclick="getloglist('<?php echo htmlentities($value['id']); ?>')">使用记录</a>
                                            <a href="javascript:;" class="value-delete tpl-table-black-operation-warning delcomm " data-id="<?php echo htmlentities($value['id']); ?>">删除会员</a>
                                        </div>
                                    </td> -->
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="layui-col-md12 layui-col-xs12">
                    <?php if(($data['list'])): ?><?php echo $data['pageshow']; ?><?php endif; ?>
                    <div class="pagination-total">
                        <!-- <div class="">总记录：<?php echo htmlentities($data['count']); ?> ／ 总页数：<?php echo htmlentities($data['pagetotal']); ?></div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // 获取核销记录
    function getloglist(uid) {
        $.post("<?php echo url('couponloglist'); ?>", { uid: uid }, function (data) {
            if (data == '400') {
                layer.msg('暂无记录');
            } else {
                layer.open({
                    type: 1,
                    skin: 'layui-layer-rim', //加上边框
                    area: ['680px', '240px'], //宽高
                    content: data
                });
            }
        });
    }
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
