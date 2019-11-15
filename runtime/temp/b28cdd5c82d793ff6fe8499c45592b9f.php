<?php /*a:2:{s:96:"E:\PHPTutorial\WWW\dzcyiranjianshen\application\manage\view\distribution\commissionlog_list.html";i:1573634528;s:71:"E:\PHPTutorial\WWW\dzcyiranjianshen\application\manage\view\layout.html";i:1572926623;}*/ ?>
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
                <div class="widget-title layer-cf">分成记录列表</div>
            </div>
            <div class="layui-col-md12 layui-col-xs12 widget-body">
                <form class="layui-form layui-col-md11" id="myform" method="post" action="">

                    <div class="layui-form-item">
                        <div class="layui-inline layui-col-md4">
                            <label class="layui-form-label">获佣用户id：</label>
                            <div class="layui-input-inline" >
                                <input name="uid" type="text" class="layui-input" placeholder="请输入获佣用户id" value="<?php echo htmlentities($para['uid']); ?>">
                            </div>
                        </div>

                        <div class="layui-inline layui-col-md4">
                            <label class="layui-form-label">订单编号：</label>
                            <div class="layui-input-inline" >
                                <input name="oid" type="text" class="layui-input" placeholder="请输入订单编号" value="<?php echo htmlentities($para['oid']); ?>">
                            </div>
                        </div>


                    </div>

                    <div class="layui-form-item">
                        <div class="layui-inline layui-col-md4">
                            <label class="layui-form-label">生成日志开始时间：</label>
                            <div class="layui-input-inline" >
                                <input name="startstamp" id="startstamp" autocomplete="off" type="text" class="layui-input" placeholder="请输入生成日志开始时间" value="<?php echo $para['startstamp']==''? '':date('Y-m-d',$para['startstamp']); ?>">
                            </div>
                        </div>
                        <div class="layui-inline layui-col-md4">
                            <label class="layui-form-label">生成日志结束时间：</label>
                            <div class="layui-input-inline" >
                                <input name="endstamp" id="endstamp" type="text" autocomplete="off" class="layui-input" placeholder="请输入生成日志结束时间" value="<?php echo $para['endstamp']==''? '':date('Y-m-d',$para['endstamp']); ?>">
                            </div>
                        </div>


                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit lay-filter="formDemo">查询</button>
                            <a class="layui-btn layui-btn-primary" href="<?php echo url('commission_list'); ?>">重置</a>
                        </div>
                    </div>
                </form>

                <div class="layui-col-md12" >
                    <table width="100%" class="layui-table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>下单会员</th>
                            <th>获佣用户</th>
                            <th>订单编号</th>
                            <th>获佣金额</th>
                            <th>订单金额</th>
                            <th>获佣用户级别</th>
                            <th>记录生成时间</th>
                            <th>状态</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if((empty($data['list']))): ?>
                        <tr>
                            <td colspan="99" class="layer-text-center">暂无记录</td>
                        </tr>
                        <?php else: foreach($data['list'] as $item): ?>

                        <tr>
                            <td><?php echo htmlentities($item['id']); ?></td>
                            <td><?php echo htmlentities($item['order_nickname']); ?></td>
                            <td><?php echo htmlentities($item['fnickname']); ?></td>
                            <td><?php echo htmlentities($item['oid']); ?></td>
                            <td><?php echo htmlentities($item['commission_money']); ?></td>
                            <td><?php echo htmlentities($item['order_money']); ?></td>
                            <td><?php echo htmlentities($item['user_level']); ?></td>

                            <td><?php echo date("Y-m-d H:i:s",$item['addstamp']); ?></td>
                            <td>
                                <?php if($item['status'] == 0): ?>
                                    未分成
                                <?php elseif($item['status'] == 1): ?>
                                    已分成
                                <?php else: ?>
                                    未知状态
                                <?php endif; ?>

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
</div>
<script>
    $(function () {
        $('#myform').submit(function () {

            var startstamp = $("input[name='startstamp']").val();
            if(!startstamp){    layer.msg('筛选时间不能为空', function () { }); return false;}

            var endstamp = $("input[name='endstamp']").val();
            if(!endstamp){    layer.msg('筛选时间不能为空', function () { }); return false;}


        });

        layui.use(['form', 'element', 'laydate'],  function () {
            var laydate = layui.laydate;

            //常规用法
            laydate.render({
                elem: '#startstamp',
                type:'date', // 可选择：年、月、日、时、分、秒
                format: 'yyyy-MM-dd', //指定时间格式
                istime: true, //必须填入时间
            });

            laydate.render({
                elem: '#endstamp',
                type:'date', // 可选择：年、月、日、时、分、秒
                format: 'yyyy-MM-dd', //指定时间格式
                istime: true, //必须填入时间
            });



            var jQuery = layui.jquery
                ,layer = layui.layer
                ,form = layui.form
                ,element = layui.element;


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
