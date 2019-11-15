<?php /*a:2:{s:76:"/home/wwwroot/hotyiranjianshen.com/application/manage/view/sys/menulist.html";i:1571110098;s:70:"/home/wwwroot/hotyiranjianshen.com/application/manage/view/layout.html";i:1572247373;}*/ ?>
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
                <div class="widget-title">菜单权限列表</div>
            </div>
        </div>
        <div class="layui-col-md12 layui-col-xs12 widget-body ">
            <form class="layui-form" method="post" action="<?php echo url('index'); ?>">
                <div class="layui-u-sm-12 layui-u-md-12 layui-col-lg12">
                    <div class="layui-form-item ">
                        <div class="layui-col-md1"><a class="layui-btn" href="<?php echo url('menurun'); ?>">新增</a></div>

                        <!-- <div class="layui-col-md3">
                            <label class="layui-form-label">微信昵称：</label>
                            <div class="layui-input-inline">
                                <input name="nickName" type="text" class="layui-input" placeholder="请输入微信昵称"
                                    value="<?php echo htmlentities($request->get('nickName ')); ?>">
                            </div>
                        </div>
                        <div class="layui-col-md3 laui-col-width">
                            <button class="layui-btn" lay-submit lay-filter="formDemo">搜索</button>
                        </div> -->
                        
                    </div>
                </div>
            </form>
            <div class="layui-col-md12">
                <table class="layui-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>名称</th>
                            <th>路径</th>
                            <th>模块</th>
                            <th style="width:10%;">排序</th>
                            <th>展示</th>
                            <th>更新时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($lists as $value): ?>
                            <tr>
                                <td><?php echo htmlentities($value['id']); ?></td>
                                <td onclick="showson(<?php echo htmlentities($value['id']); ?>,1)">
                                    <i class="layui-icon">&#<?php echo htmlentities($value['icon']); ?>;</i> <?php echo htmlentities($value['name']); ?>
                                </td>
                                <td><?php echo htmlentities($value['url']); ?></td>
                                <td><?php echo htmlentities($value['model']); ?></td>
                                <td>
                                    <input class="layui-input" type="number" value="<?php echo htmlentities($value['sort']); ?>" name="num" onblur="return changesort('<?php echo url('changesort'); ?>',this.value,'<?php echo htmlentities($value['id']); ?>');">
                                </td>
                                <td>
                                    <a href="javascript:;" class="layui-btn layui-btn-sm layui-btn-radius layui-btn-<?php if($value['ishow'] == 1): ?>normal<?php else: ?>danger<?php endif; ?> showmenu " data-id="<?php echo htmlentities($value['id']); ?>"><?php if($value['ishow'] == 1): ?>展示<?php else: ?>隐藏<?php endif; ?></a>
                                    <!-- <div class="layui-form"><input class="showmenu" type="checkbox" lay-filter="filter" lay-skin="switch" lay-text="展示|隐藏" <?php if($value['ishow'] == 1): ?>checked<?php endif; ?>></div> -->
                                </td>
                                <td><?php echo htmlentities($value['update_time']); ?></td>
                                <td>
                                    <div class="tpl-table-black-operation">
                                        <a class="tpl-table-black-operation" href="<?php echo url('menurun',['id' => $value['id']]); ?>">编辑</a>
                                        <a href="javascript:void(0);" class="value-delete tpl-table-black-operation-del delmenu" data-id="<?php echo htmlentities($value['id']); ?>">
                                            <i class="mdi menu-icon mdi-pencil-lock"></i> 删除
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <?php if(($value['son'])): foreach($value['son'] as $val1): ?>
                                <tr class="son<?php echo htmlentities($value['id']); ?> ">
                                    <td><?php echo htmlentities($val1['id']); ?></td>
                                    <td onclick="showson(<?php echo htmlentities($val1['id']); ?>,2)">｜－<?php echo htmlentities($val1['name']); ?></td>
                                    <td><?php echo htmlentities($val1['url']); ?></td>
                                    <td><?php echo htmlentities($val1['model']); ?></td>
                                    <td>
                                        <input class="layui-input" type="number" value="<?php echo htmlentities($val1['sort']); ?>" name="num" onblur="return changesort('<?php echo url('changesort'); ?>',this.value,'<?php echo htmlentities($val1['id']); ?>');">
                                    </td>
                                    <td>
                                        <a href="javascript:;" class="layui-btn layui-btn-sm layui-btn-radius layui-btn-<?php if($val1['ishow'] == 1): ?>normal<?php else: ?>danger<?php endif; ?> showmenu " data-id="<?php echo htmlentities($val1['id']); ?>"><?php if($val1['ishow'] == 1): ?>展示<?php else: ?>隐藏<?php endif; ?></a>
                                    </td>
                                    <td><?php echo htmlentities($val1['update_time']); ?></td>
                                    <td>
                                        <div class="tpl-table-black-operation">
                                            <a class="tpl-table-black-operation" href="<?php echo url('menurun',['id' => $val1['id']]); ?>">编辑</a>
                                            <a href="javascript:void(0);" class="value-delete tpl-table-black-operation-del delmenu"data-id="<?php echo htmlentities($val1['id']); ?>"><i class="mdi menu-icon mdi-pencil-lock"></i> 删除</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php if(($val1['son'])): foreach($val1['son'] as $val2): ?>
                                    <tr class="son<?php echo htmlentities($val1['id']); ?> son11<?php echo htmlentities($value['id']); ?> ">
                                        <td><?php echo htmlentities($val2['id']); ?></td>
                                        <td onclick="showson(<?php echo htmlentities($val2['id']); ?>,3)">　　｜－<?php echo htmlentities($val2['name']); ?></td>
                                        <td><?php echo htmlentities($val2['url']); ?></td>
                                        <td><?php echo htmlentities($val2['model']); ?></td>
                                        <td>
                                            <input class="layui-input" type="number" value="<?php echo htmlentities($val2['sort']); ?>" name="num" onblur="return changesort('<?php echo url('changesort'); ?>',this.value,'<?php echo htmlentities($val2['id']); ?>');">
                                        </td>
                                        <td>
                                            <a href="javascript:;" class="layui-btn layui-btn-sm layui-btn-radius layui-btn-<?php if($val2['ishow'] == 1): ?>normal<?php else: ?>danger<?php endif; ?> showmenu " data-id="<?php echo htmlentities($val2['id']); ?>"><?php if($val2['ishow'] == 1): ?>展示<?php else: ?>隐藏<?php endif; ?></a>
                                        </td>
                                        <td><?php echo htmlentities($val2['update_time']); ?></td>
                                        <td>
                                            <div class="tpl-table-black-operation">
                                                <a class="tpl-table-black-operation" href="<?php echo url('menurun',['id' => $val2['id']]); ?>">编辑</a>
                                                <a href="javascript:void(0);" class="value-delete tpl-table-black-operation-del delmenu"data-id="<?php echo htmlentities($val2['id']); ?>"><i class="mdi menu-icon mdi-pencil-lock"></i> 删除</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php if(($val2['son'])): foreach($val2['son'] as $val3): ?>
                                        <tr class="son<?php echo htmlentities($val2['id']); ?> son12<?php echo htmlentities($value['id']); ?> son22<?php echo htmlentities($val1['id']); ?> ">
                                            <td><?php echo htmlentities($val3['id']); ?></td>
                                            <td>　　　　｜－<?php echo htmlentities($val3['name']); ?></td>
                                            <td><?php echo htmlentities($val3['url']); ?></td>
                                            <td><?php echo htmlentities($val3['model']); ?></td>
                                            <td>
                                                <input class="layui-input" type="number" value="<?php echo htmlentities($val3['sort']); ?>" name="num" onblur="return changesort('<?php echo url('changesort'); ?>',this.value,'<?php echo htmlentities($val3['id']); ?>');">
                                            </td>
                                            <td>
                                                <a href="javascript:;" class="layui-btn layui-btn-sm layui-btn-radius layui-btn-<?php if($val3['ishow'] == 1): ?>normal<?php else: ?>danger<?php endif; ?> showmenu " data-id="<?php echo htmlentities($val3['id']); ?>"><?php if($val3['ishow'] == 1): ?>展示<?php else: ?>隐藏<?php endif; ?></a>
                                            </td>
                                            <td><?php echo htmlentities($val3['update_time']); ?></td>
                                            <td>
                                                <div class="tpl-table-black-operation">
                                                    <a class="tpl-table-black-operation" href="<?php echo url('menurun',['id' => $val3['id']]); ?>">编辑</a>
                                                    <a href="javascript:void(0);" class="value-delete tpl-table-black-operation-del delmenu" data-id="<?php echo htmlentities($val3['id']); ?>"><i class="mdi menu-icon mdi-pencil-lock"></i> 删除</a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?><?php endif; ?>
                                <?php endforeach; ?><?php endif; ?>
                            <?php endforeach; ?><?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <!-- <div class="layui-col-md12 layui-col-xs12">
                    <div class=""><?php echo $page; ?> </div>
                    <div class="pagination-total">
                        <div class="">总记录：</div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        // 删除
        $('.delmenu').delete('id', "<?php echo url('delmenu'); ?>", '删除后可能会导致程序不可用,确认删除？');
        $('.showmenu').delete('id', "<?php echo url('showmenu'); ?>", '确认操作？');
    });

    // layui.use(['form', 'layedit', 'laydate', 'upload'], function () {
    //     var form = layui.form();
    //     form.on('switch(switch1)', function (data) {
    //         alert(data);
    //     });
    // }); 

    function showson(id, layer){
        switch (layer) {
            case 1:
                if ($(".son" + id).is(":hidden")) {
                    $(".son" + id).show(200);
                    $(".son11" + id).show(200);
                    $(".son12" + id).show(200);
                } else {
                    $(".son" + id).hide(200);
                    $(".son11" + id).hide(200);
                    $(".son12" + id).hide(200);
                }
                break;
            case 2:
                if ($(".son" + id).is(":hidden")) {
                    $(".son" + id).show(200);
                    $(".son22" + id).show(200);
                } else {
                    $(".son" + id).hide(200);
                    $(".son22" + id).hide(200);
                }
                break;
            case 3:
                if ($(".son" + id).is(":hidden")) {
                    $(".son" + id).show(200);
                } else {
                    $(".son" + id).hide(200);
                }
                break;
            default:
                return false;
        } 
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
