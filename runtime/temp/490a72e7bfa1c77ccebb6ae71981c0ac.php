<?php /*a:2:{s:82:"/home/wwwroot/hotyiranjianshen.com/application/manage/view/ordercourse/detail.html";i:1572248338;s:70:"/home/wwwroot/hotyiranjianshen.com/application/manage/view/layout.html";i:1572247373;}*/ ?>
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
                <div class="widget-title">订单详情</div>
            </div>
        </div>
        <div class="layui-col-md12 layui-col-xs12 widget-body ">
            
            <!-- <div class="layui-col-sm12">
                <?php
                    // 计算当前步骤位置
                    $progress = 1;
                    $detail['pay_status']['value'] === 20 && $progress += 1;
                    $detail['delivery_status']['value'] === 20 && $progress += 1;
                    $detail['receipt_status']['value'] === 20 && $progress += 1;
                ?>
                <ul class="order-detail-progress progress-<?php echo htmlentities($progress); ?> ">
                    <li>
                        <span>下单时间</span>
                        <div class="tip">
                            <?php echo htmlentities($detail['create_time']); ?>
                        </div>
                    </li>
                    <li>
                        <span>付款</span>
                        <?php if($detail['pay_status']['value'] === 20): ?>
                        <div class="tip">
                            <?php echo htmlentities(date('Y-m-d H:i:s',!is_numeric($detail['pay_time'])? strtotime($detail['pay_time']) : $detail['pay_time'])); ?>
                        </div>
                        <?php endif; ?>
                    </li>
                    <li>
                        <span>发货</span>
                        <?php if($detail['delivery_status']['value'] === 20): ?>
                        <div class="tip">
                            <?php echo htmlentities($detail['order_delivery'][count($detail['order_delivery'])-1]['create_time']); ?>
                        </div>
                        <?php endif; ?>
                    </li>
                    <li>
                        <span>收货</span>
                        <?php if($detail['receipt_status']['value'] === 20): ?>
                        <div class="tip">
                            <?php echo htmlentities(date('Y-m-d H:i',!is_numeric($detail['order_delivery'][0]['receipt_time'])? strtotime($detail['order_delivery'][0]['receipt_time']) : $detail['order_delivery'][0]['receipt_time'])); ?>
                        </div>
                        <?php endif; ?>
                    </li>
                    <li>
                        <span>完成</span>
                        <?php if($detail['order_status']['value'] === 30): ?>
                        <div class="tip">
                            <?php echo htmlentities(date('Y-m-d H:i',!is_numeric($detail['end_time'])? strtotime($detail['end_time']) : $detail['end_time'])); ?>
                        </div>
                        <?php endif; ?>
                    </li>
                </ul>
            </div> -->

            <div class="widget-head layui-col-sm12 layui-col-md12 layui-col-lg12">
                <div class="widget-title">基本信息</div>
            </div>
            <div class="layui-col-md12">
                <table width="100%" class="layui-table">
                    <thead>
                    <tr>
                        <th>订单编号</th>
                        <th>联系电话</th>
                        <th>参课人数</th>
                        <th>订单金额</th>
                        <th>时间</th>
                        <th>状态</th>
                    </tr>
                    </thead>
                    <tr>
                        <td><?php echo htmlentities($comm['oid']); ?></td>
                        <td><?php echo htmlentities($comm['phone']); ?></td>
                        <td><?php echo htmlentities($comm['number']); ?></td>
                        <td>
                            订单金额：￥<?php echo htmlentities($comm['course_price']*$comm['number']); ?>
                            <hr>
                            支付方式：
                            <?php
                                switch ($comm['paytype']) {
                                    case 1:
                                        echo '全款支付';
                                        break;
                                    case 2:
                                        echo '优惠券满减　-￥'.$comm['cprice'];
                                        break;
                                    case 3:
                                        echo '课程包抵扣';
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                            ?>
                            <hr>
                            实际付款：￥<?php echo htmlentities($comm['price']); ?>
                        </td>
                        <td>
                            提交时间：<?php echo date("Y-m-d H:i",$comm['addstamp']); ?>
                            <hr>
                            开课时间：<?php echo date("Y-m-d H:i",$comm['pastamp']); ?>
                        </td>
                        <td><?php echo $comm['stype']==1 ? '预约中' : '已结束'; ?></td>

                    </tr>
                    
                    </tbody>
                </table>
            </div>

            <div class="widget-head layui-col-sm12 layui-col-md12 layui-col-lg12">
                <div class="widget-title">支付信息</div>
            </div>
            <?php switch($comm['paytype']): case "1": ?>
                    <!-- 全款 -->
                    <div class="layui-col-md12">
                        <table width="100%" class="layui-table">
                            <thead>
                                <tr>
                                    <th>订单编号</th>
                                    <th>支付金额</th>
                                    <th>微信支付流水单号</th>
                                    <th>支付时间</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo htmlentities($comm['logpay']['oid']); ?></td>
                                    <td><?php echo htmlentities($comm['logpay']['price']/100); ?></td>
                                    <td><?php echo htmlentities($comm['logpay']['flowcode']); ?></td>
                                    <td><?php echo date("Y-m-d H:i",$comm['logpay']['addstamp']); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php break; case "2": ?>
                    <!-- 优惠券 -->
                    <div class="layui-col-md12">
                        <table width="100%" class="layui-table">
                            <thead>
                                <tr>
                                    <th>订单编号</th>
                                    <th>支付金额</th>
                                    <th>微信支付流水单号</th>
                                    <th>支付时间</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo htmlentities($comm['logpay']['oid']); ?></td>
                                    <td><?php echo htmlentities($comm['logpay']['price']/100); ?></td>
                                    <td><?php echo htmlentities($comm['logpay']['flowcode']); ?></td>
                                    <td><?php echo date("Y-m-d H:i",$comm['logpay']['addstamp']); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="layui-col-md12">
                        <table width="100%" class="layui-table">
                            <thead>
                                <tr>
                                    <th>订单编号</th>
                                    <th>优惠券名称</th>
                                    <th>备注</th>
                                    <th>使用时间</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo htmlentities($comm['logcoupon']['oid']); ?></td>
                                    <td><?php echo htmlentities($comm['logcoupon']['name']); ?></td>
                                    <td><?php echo htmlentities($comm['logcoupon']['content']); ?></td>
                                    <td><?php echo date("Y-m-d H:i",$comm['logcoupon']['addstamp']); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php break; case "3": ?>
                    <!-- 课程包 -->
                    <div class="layui-col-md12">
                        <table width="100%" class="layui-table">
                            <thead>
                            <tr>
                                <th>课程包编号</th>
                                <th>名称</th>
                                <th>总课时</th>
                                <th>使用课时</th>
                                <th>使用时间</th>
                            </tr>
                            </thead>
                            <tr>
                                <td><?php echo htmlentities($comm['logpacks']['sid']); ?></td>
                                <td><?php echo htmlentities($comm['logpacks']['name']); ?></td>
                                <td><?php echo htmlentities($comm['logpacks']['tothour']); ?></td>
                                <td><?php echo htmlentities($comm['logpacks']['hashour']); ?></td>
                                <td><?php echo date("Y-m-d H:i",$comm['logpacks']['addstamp']); ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                <?php break; default: ?>
                <!-- 默认 -->
            <?php endswitch; ?>

            <div class="widget-head layui-col-sm12 layui-col-md12 layui-col-lg12">
                <div class="widget-title ">会员信息</div>
            </div>
            <div class="layui-col-md12">
                <table width="100%" class="layui-table">
                    <thead>
                        <tr>
                            <th>编号</th>
                            <th>头像</th>
                            <th>昵称</th>
                            <th>性别</th>
                            <th>地区</th>
                            <th>注册时间</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlentities($usercomm['uid']); ?></td>
                            <td><a href="<?php echo htmlentities($usercomm['headimg']); ?>" title="点击查看大图" target="_blank"><img src="<?php echo htmlentities($usercomm['headimg']); ?>" width="60" height="60"alt=""></a></td>
                            <td><?php echo htmlentities($usercomm['nickname']); ?></td>
                            <td><?php echo $usercomm['gender']==1 ? '男' : '女'; ?></td>
                            <td><?php echo htmlentities($usercomm['country']); ?> <?php echo htmlentities($usercomm['province']); ?> <?php echo htmlentities($usercomm['city']); ?></td>
                            <td><?php echo date("Y-m-d H:i",$usercomm['addstamp']); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="widget-head layui-col-sm12 layui-col-md12 layui-col-lg12">
                <div class="widget-title ">课程信息</div>
            </div>
            <div class="layui-col-md12">
                <table width="100%" class="layui-table">
                    <thead>
                        <tr>
                            <th>编号</th>
                            <th>课程分类</th>
                            <th>名称</th>
                            <th>任课教练</th>
                            <th>价格</th>
                            <th>课程时间</th>
                            <th>总名额/已预约</th>
                            <th>更新时间</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlentities($coucomm['sid']); ?></td>
                            <td><?php echo htmlentities($coucomm['classname']); ?></td>
                            <td><?php echo htmlentities($coucomm['name']); ?></td>
                            <td><?php echo htmlentities($coucomm['coname']); ?></td>
                            <td>￥<?php echo htmlentities($coucomm['price']); ?></td>
                            <td>
                                <?php echo date("Y-m-d H:i",$coucomm['datestamp']); ?>
                                <br><?php echo htmlentities($coucomm['stime']); ?>-<?php echo htmlentities($coucomm['etime']); ?>
                            </td>
                            <td>
                                <?php echo htmlentities($coucomm['totnum']); ?>/<?php echo htmlentities($coucomm['nownum']); if(($coucomm['ispast']==2)): ?>
                                    <a class="layui-btn layui-btn-sm layui-btn-radius layui-btn-normal layui-btn-disabled">已结束</a>
                                <?php elseif(($coucomm['isfull']==2)): ?>
                                    <a class="layui-btn layui-btn-sm layui-btn-radius layui-btn-danger">已满员</a>
                                <?php else: ?>
                                    <span class="layui-btn layui-btn-sm layui-btn-radius layui-btn-normal">可预约</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date("Y-m-d H:i",$coucomm['modstamp']); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
<script>
    var thiswin = parent.layer.getFrameIndex(window.name); //获取窗口索引
</script>

<?php if(($oid == '')): ?>
<script>
    //关闭iframe
    $(function () {
        parent.layer.close(thiswin);
    });
</script>
<?php endif; ?>
<script>
    $(function () {
        $('.tpl-header').remove();
        $('.left-oidebar').remove();
        $('.left-sidebar').remove();
        $('.tpl-content-wrapper ').css({ "margin-left": "0px", "margin-top": "0px" });


        $('#myform').submit(function () {
            
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
