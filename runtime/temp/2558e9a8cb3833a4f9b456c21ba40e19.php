<?php /*a:2:{s:81:"/home/wwwroot/hotyiranjianshen.com/application/manage/view/course/courselist.html";i:1572251759;s:70:"/home/wwwroot/hotyiranjianshen.com/application/manage/view/layout.html";i:1572247373;}*/ ?>
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
                <div class="widget-title"><?php if(($history==1)): ?>历史<?php endif; ?>课程列表</div>
            </div>
        </div>
        <div class="layui-col-md12 layui-col-xs12 widget-body ">
            <?php if(($history!=1)): ?>
            <div class="layui-form-item layui-col-md1"><div class="layui-col-md1"><a class="layui-btn" href="<?php echo url('courserun'); ?>">新增</a></div></div>
            <?php endif; ?>
            <form class="layui-form layui-col-md11" method="post" action="<?php echo url('index'); ?>">
                <div class="layui-form-item">
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label">分类：</label>
                        <div class="layui-input-inline" >
                            <select class="layui-inline " lay-search name="cid" data-layer-selected="{btnSize: 'sm', placeholder: '分类'}">
                                <option value="0" <?php if(($para["cid"]==0)): ?>selected<?php endif; ?>>全部</option>
                                <?php foreach($classlist as $val): ?>
                                    <option value="<?php echo htmlentities($val['id']); ?>" <?php if(($para['cid']==$val['id'])): ?>selected<?php endif; ?>><?php echo htmlentities($val['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label">教练：</label>
                        <div class="layui-input-inline" >
                            <select class="layui-inline " lay-search name="coid" data-layer-selected="{btnSize: 'sm', placeholder: '教练'}">
                                <option value="0" <?php if(($para['coid']==0)): ?>selected<?php endif; ?>>全部</option>
                                <?php foreach($coachlist as $val): ?>
                                    <option value="<?php echo htmlentities($val['id']); ?>" <?php if(($para['coid']==$val['id'])): ?>selected<?php endif; ?>><?php echo htmlentities($val['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label">状态：</label>
                        <div class="layui-input-inline">
                            <select class="layui-inline " lay-search name="state" data-layer-selected="{btnSize: 'sm', placeholder: '状态'}">
                                <option value="0" <?php if(($para["state"]==0)): ?>selected<?php endif; ?>>全部</option>
                                <option value="11" <?php if(($para["state"]==11)): ?>selected<?php endif; ?>>可预约</option>
                                <option value="22" <?php if(($para["state"]==22)): ?>selected<?php endif; ?>>已满员</option>
                                <option value="33" <?php if(($para["state"]==33)): ?>selected<?php endif; ?>>已结束</option>
                                <option value="44" <?php if(($para["state"]==44)): ?>selected<?php endif; ?>>隐藏课程</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label">课程编号：</label>
                        <div class="layui-input-inline" >
                            <input name="sid" type="text" class="layui-input" placeholder="请输入课程编号" value="<?php echo htmlentities($para['sid']); ?>">
                        </div>
                    </div>

                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label">课程名称：</label>
                        <div class="layui-input-inline" >
                            <input name="name" type="text" class="layui-input" placeholder="请输入课程名称" value="<?php echo htmlentities($para['name']); ?>">
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label">课程日期：</label>
                        <div class="layui-input-inline">
                            <input id="datestamp" name="datestamp" type="text" class="layui-input" placeholder="请选择课程日期" value="<?php echo htmlentities($para['datestamp']); ?>">
                        </div>
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formDemo">查询</button>
                        <a class="layui-btn layui-btn-primary" href="<?php echo url('index'); ?>">重置</a>
                    </div>
                </div>
            </form>
            <div class="layui-col-md12">
                <table class="layui-table">
                    <thead>
                        <tr>
                            <th style="width:8%;">编号</th>
                            <th style="width:10%;">课程分类</th>
                            <th>名称</th>
                            <th>任课教练</th>
                            <th>价格</th>
                            <th>课程时间</th>
                            <th>总名额/已预约</th>

                            <th style="width:10%;">排序</th>
                            <?php if(($history!=1)): ?><th>展示/隐藏</th><?php endif; ?>
                            <th>更新时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if((empty($data['list']))): ?>
                            <tr>
                                <td colspan="99" class="layer-text-center">暂无记录</td>
                            </tr>
                        <?php else: foreach($data['list'] as $value): ?>
                                <tr>
                                    <td><?php echo htmlentities($value['sid']); ?></td>
                                    <td><?php echo htmlentities($value['classname']); ?></td>
                                    <td><?php echo htmlentities($value['name']); ?></td>
                                    <td><?php echo htmlentities($value['coname']); ?></td>
                                    <td>￥<?php echo htmlentities($value['price']); ?></td>
                                    <td><?php echo htmlentities($value['datestamp']); ?><br><?php echo htmlentities($value['stime']); ?>-<?php echo htmlentities($value['etime']); ?></td>
                                    <td>
                                        <?php echo htmlentities($value['totnum']); ?>/<?php echo htmlentities($value['nownum']); if(($value['ispast']==2)): ?>
                                            <a class="layui-btn layui-btn-sm layui-btn-radius layui-btn-normal layui-btn-disabled">已结束</a>
                                        <?php elseif(($value['isfull']==2)): ?>
                                            <a class="layui-btn layui-btn-sm layui-btn-radius layui-btn-danger">已满员</a>
                                        <?php else: ?>
                                            <span class="layui-btn layui-btn-sm layui-btn-radius layui-btn-normal">可预约</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <input class="layui-input" type="number" value="<?php echo htmlentities($value['sort']); ?>" onblur="return changesort('<?php echo url('coursesort'); ?>',this.value,'<?php echo htmlentities($value['id']); ?>');">
                                    </td>
                                    <?php if(($history!=1)): ?>
                                        <td>
                                            <a href="javascript:;" class="layui-btn layui-btn-sm layui-btn-radius layui-btn-<?php if($value['ishow'] == 1): ?>normal<?php else: ?>danger<?php endif; ?> ishowcomm " data-id="<?php echo htmlentities($value['id']); ?>"><?php if($value['ishow'] == 1): ?>展示<?php else: ?>隐藏<?php endif; ?></a>
                                        </td>
                                    <?php endif; ?>
                                    <td><?php echo date("Y-m-d H:i",$value['modstamp']); ?></td>
                                    <td>
                                        <div class="tpl-table-black-operation">
                                            <a class="tpl-table-black-operation-primary" href="<?php echo url('coursereglist',['sid' => $value['sid']]); ?>">报名列表</a>
                                            <?php if(($history!=1)): ?>
                                                <!-- <a class="tpl-table-black-operation" href="<?php echo url('courserun',['id' => $value['id']]); ?>">添加参课人</a> -->
                                                <br>
                                                <a class="tpl-table-black-operation" href="<?php echo url('courserun',['id' => $value['id']]); ?>">编辑</a>
                                            <?php endif; ?>

                                            <a href="javascript:void(0);" class="value-delete tpl-table-black-operation-del delcomm" data-id="<?php echo htmlentities($value['id']); ?>"><i class="mdi menu-icon mdi-pencil-lock"></i> 删除</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="layui-col-md12 layui-col-xs12">
                    <?php if(($data['list'])): ?><?php echo $data['pageshow']; ?><?php endif; ?>
                    <div class="pagination-total">
                        <div class="">总记录：<?php echo htmlentities($data['count']); ?> ／ 总页数：<?php echo htmlentities($data['pagetotal']); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        // 删除
        $('.delcomm').delete('id', "<?php echo url('coursedel'); ?>", '数据删除后无法恢复,确认删除？');
        $('.ishowcomm').delete('id', "<?php echo url('courseshow'); ?>", '确认操作？');

        layui.use('laydate', function () {
            var laydate = layui.laydate;
            //常规用法
            laydate.render({
                elem: '#datestamp'
            });
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