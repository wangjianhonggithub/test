<?php /*a:2:{s:86:"E:\PHPTutorial\WWW\dzcyiranjianshen\application\manage\view\market\flash_sale\add.html";i:1573206962;s:71:"E:\PHPTutorial\WWW\dzcyiranjianshen\application\manage\view\layout.html";i:1572926623;}*/ ?>
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
				<div class="widget-title"><?php if(($comm=='')): ?>新增<?php else: ?>编辑<?php endif; ?>抢购活动</div>
			</div>
			<form id="myform" class="layui-form" method="post">



				<div class="widget-body layui-col-lg12">

                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">抢购标题 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <input type="text" class="layui-input" name="title" value="<?php echo htmlentities($comm['title']); ?>" placeholder="请输入抢购标题" required>
                        </div>
                        <div class="layui-form-mid layui-word-aux"></div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">选择抢购商品 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <select id="goods_id" name="goods_id" lay-search>
                                <option <?php if($comm['cid']==0): ?> selected <?php endif; ?> value="">请选择</option>

                                <?php foreach($sale_goods as $value): ?>
                                    <option <?php if($value['goods_id']==$comm['goods_id']): ?> selected <?php endif; ?> value="<?php echo htmlentities($value['goods_id']); ?>"><?php echo htmlentities($value['goods_name']); ?>(库存:<?php echo htmlentities($value['store_count']); ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                   


                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">限时抢购价格 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <input type="text" class="layui-input" name="price" value="<?php echo htmlentities($comm['price']); ?>" placeholder="请输入限时抢购价格" required>
                        </div>
                        <div class="layui-form-mid layui-word-aux">商品抢购价格,单位：元</div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">参加抢购数量 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <input type="text" class="layui-input" name="goods_num" value="<?php echo htmlentities($comm['goods_num']); ?>" placeholder="请输入参加抢购数量" required>
                        </div>
                        <div class="layui-form-mid layui-word-aux">此抢购活动最多允许抢购的商品数量</div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">限购数量 </label>
                        <div class="layui-col-sm5 layer-midd-left">
                            <input type="text" class="layui-input" name="buy_limit" value="<?php echo htmlentities($comm['buy_limit']); ?>" placeholder="请输入限购数量" required>
                        </div>
                        <div class="layui-form-mid layui-word-aux">限时抢购每个人能购买的数量</div>
                    </div>



                    <div class="layui-form-item" id="is_multiple" >
                        <label class="layui-form-label form-require">开始时间 </label>
                        <div class="layui-col-sm2 layer-midd-left">
                            <input type="text" class="layui-input" id="startstamp" autocomplete="off" name="startstamp"
                                   value="<?php echo $comm['startstamp']==''?'':date('Y-m-d H:i:s',$comm['startstamp']); ?>"
                                   placeholder="请选择开始时间" readonly="readonly" >
                        </div>
                        <label class="layui-form-label form-require">结束时间 </label>
                        <div class="layui-col-sm2 layer-midd-left">
                            <input type="text" class="layui-input" autocomplete="off" id="endstamp" name="endstamp" value="<?php echo $comm['endstamp']==''?'':date('Y-m-d H:i:s',$comm['endstamp']); ?>" placeholder="请选择结束时间" readonly="readonly" >
                        </div>
                    </div>


                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">抢购介绍 </label>
                        <div class="layui-col-sm6 layer-midd-left">
                            <textarea name="description" required placeholder="请输入介绍" class="layui-textarea"><?php echo htmlentities($comm['description']); ?></textarea>
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

    //日期转时间戳
    function transdate(Time){
        var date = new Date();
        date.setFullYear(Time.substring(0, 4));
        date.setMonth(Time.substring(5, 7) - 1);
        date.setDate(Time.substring(8, 10));
        date.setHours(Time.substring(11, 13));
        date.setMinutes(Time.substring(14, 16));
        date.setSeconds(Time.substring(17, 19));
        return Date.parse(date) / 1000;
    }

    $('#myform').submit(function () {
        var buy_limit = $("input[name='buy_limit']").val();
        if(buy_limit <= 0){     layer.msg('限购数量请输入正整数', function () { }); return false;}

        var goods_num = $("input[name='goods_num']").val();
        if(goods_num <= 0){    layer.msg('参加抢购数量请输入正整数', function () { }); return false;}

        var startstamp = $("input[name='startstamp']").val();
        if(!startstamp){    layer.msg('请选择活动开始时间', function () { }); return false;}

        var endstamp = $("input[name='endstamp']").val();
        if(!endstamp){    layer.msg('请选择活动结束时间', function () { }); return false;}
        
        if (transdate(startstamp) >= transdate(endstamp)){
            layer.msg('开始时间不得小于结束时间', function () { }); return false;
        }

   
        ajaxrun('myform');
        return false;
    });


    layui.use(['form', 'element', 'laydate'],  function () {
        var laydate = layui.laydate;
        var myDate = new Date();  //获取当前时间
        //常规用法
        laydate.render({
            elem: '#startstamp',
            type:'datetime', // 可选择：年、月、日、时、分、秒
            format: 'yyyy-MM-dd HH:mm:ss', //指定时间格式
            istime: true, //必须填入时间
            min:myDate.toLocaleString() //最小时间
        });

        laydate.render({
            elem: '#endstamp',
            type:'datetime', // 可选择：年、月、日、时、分、秒
            format: 'yyyy-MM-dd HH:mm:ss', //指定时间格式
            istime: true, //必须填入时间
            min:myDate.toLocaleString()  //最小时间
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
