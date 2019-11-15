<?php /*a:2:{s:79:"E:\PHPTutorial\WWW\dzcyiranjianshen\application\manage\view\course\reglist.html";i:1572515976;s:71:"E:\PHPTutorial\WWW\dzcyiranjianshen\application\manage\view\layout.html";i:1572926623;}*/ ?>
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
        <div class="layui-col-md12 layui-col-xs12">
            <div class="widget-head">
                <div class="widget-title">课程报名列表</div>
            </div>
        </div>
        <div class="layui-col-md12 layui-col-xs12 widget-body ">
            <div class="layui-form-item layui-col-md1">

                <a class="layui-btn" href="javascript:;" onclick='addregcomm("<?php echo htmlentities($sid); ?>")'>新增</a>
                <br>
                <br>
                <?php if(($coucomm['isover'] == 1)): ?>
                    <a class="layui-btn" href="javascript:;" onclick='classover("<?php echo htmlentities($sid); ?>")'>下课通知</a>
                <?php else: ?>
                    <a class="layui-btn layui-btn-primary" href="javascript:;">已下课</a>
                <?php endif; ?>
            </div>

            <form class="layui-form layui-col-md11" method="post" action="<?php echo url('coursereglist',['sid'=>$sid]); ?>">
                <div class="layui-form-item">
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label">课程名称：</label>
                        <div class="layui-input-inline" >
                            <input type="text" class="layui-input" value="<?php echo htmlentities($coucomm['classname']); ?>／<?php echo htmlentities($coucomm['name']); ?>" readonly>
                        </div>
                    </div>

                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label">课程时间：</label>
                        <div class="layui-input-inline" >
                            <input type="text" class="layui-input" value="<?php echo date('Y-m-d', $coucomm['datestamp']); ?>　<?php echo substr($coucomm['stime'], 0, 5); ?>-<?php echo substr($coucomm['etime'], 0, 5); ?>" readonly>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label">总名额/已报名</label>
                        <div class="layui-input-inline" >
                            <input type="text" class="layui-input" value="<?php echo htmlentities($coucomm['totnum']); ?>/<?php echo htmlentities($coucomm['nownum']); ?>　最多容纳:<?php echo htmlentities($coucomm['maxnum']); ?>" readonly>
                        </div>
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label">联系电话：</label>
                        <div class="layui-input-inline" >
                            <input name="phone" type="text" class="layui-input" placeholder="请输入联系电话" value="<?php echo htmlentities($para['phone']); ?>">
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label">备注信息：</label>
                        <div class="layui-input-inline" >
                            <input name="content" type="text" class="layui-input" placeholder="请输入备注信息" value="<?php echo htmlentities($para['content']); ?>">
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label">类型：</label>
                        <div class="layui-input-inline">
                            <select class="layui-inline " lay-search name="stype" data-layer-selected="{btnSize: 'sm', placeholder: '类型'}">
                                <option value="0" <?php if(($para["stype"]==0)): ?>selected<?php endif; ?>>全部</option>
                                <option value="1" <?php if(($para["stype"]==1)): ?>selected<?php endif; ?>>用户报名</option>
                                <option value="2" <?php if(($para["stype"]==2)): ?>selected<?php endif; ?>>后台添加</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formDemo">查询</button>
                        <a class="layui-btn layui-btn-primary" href="<?php echo url('coursereglist',['sid'=>$sid]); ?>">重置</a>
                    </div>
                </div>
            </form>
            <div class="layui-col-md12">
                <table class="layui-table">
                    <thead>
                        <tr>
                            <!-- <th>编号</th> -->
                            <th>会员信息</th>
                            <th>联系电话</th>
                            <th>参课人数</th>
                            <th>类型</th>
                            <th>添加时间</th>
                            <th>备注信息</th>
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
                                    <td>
                                        <?php if(($value['uid'] == 0)): ?>
                                            ／
                                        <?php else: ?>
                                            <a href="<?php echo htmlentities($value['userinfo']['headimg']); ?>" title="点击查看大图" target="_blank"><img src="<?php echo htmlentities($value['userinfo']['headimg']); ?>" width="60" height="60" alt=""></a><br>
                                            <?php echo htmlentities($value['userinfo']['nickname']); ?>／<?php echo htmlentities($value['userinfo']['gender']); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlentities($value['phone']); ?></td>
                                    <td><?php echo htmlentities($value['number']); ?></td>
                                    <td><?php echo htmlentities($value['stype']); ?></td>
                                    <td><?php echo date("Y-m-d H:i",$value['addstamp']); ?></td>

                                    <td>
                                        <textarea onchange="return changecontent('<?php echo url('courseregcontent'); ?>','<?php echo htmlentities($value['id']); ?>','<?php echo htmlentities($value['comment']); ?>');"  id="<?php echo htmlentities($value['id']); ?>content"><?php echo htmlentities($value['content']); ?></textarea>
                                    </td>
                                    <td>
                                        <div class="tpl-table-black-operation">
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
        $('.delcomm').delete('id', "<?php echo url('courseregdel'); ?>", '数据删除后无法恢复,确认删除？');
    });

    
    // 添加报名人员
    function addregcomm(sid){
        layer.open({
            type: 2,
            title: '新增报名信息',
            shadeClose: false,
            scrollbar: false,
            resize: true,
            shade: 0.8,
            maxmin: true, //开启最大化最小化按钮
            area: ['1200px', '80%'],
            content: "<?php echo url('courseregrun','',''); ?>&sid=" + sid, //iframe的url    
            end: function () {
                location.reload();
            }    
        }); 
    }

    // 下课通知
    function classover(sid){
        layer.confirm('确认课程结束且通知用户查看运动报告？', {
            title:'友情提示',
            btn: ['确定', '取消'] //按钮
        }, function () {
            var loading = layer.load(1, {
                shade: [0.8, '#fff'] //0.1透明度的白色背景
            });
            $.ajax({
                url:"<?php echo url('courseover'); ?>",
                data:{ stype:1},    
                type:'post',    
                cache:false,    
                dataType:'json',    
                success:function(data) {
                    if(data.code == 200){
                        sendmess(sid,data.mess);
                    }else{
                        layer.alert(data.mess);
                        location.reload();  // 非法操作 刷新页面
                    }
                },    
                error : function() {    
                    alert("异常");    
                }
            });

            layer.close(loading) 
        }, function () {} );
    }
    // 发送消息
    function sendmess(sid,mess){
        $.ajax({
            url:"<?php echo url('Index/Send/classover'); ?>",
            data:{ sid,mess},    
            type:'post',    
            cache:false,    
            dataType:'json',    
            success:function(data) {
                if(data.code == 200){
                    // 更改课程状态
                    overcouse(sid);
                }else{
                    layer.alert(data.mess);
                    location.reload();  // 非法操作 刷新页面
                }
            },    
            error : function() {    
                alert("异常");    
            }
        });
    }
    // 更改课程下课状态
    function overcouse(sid){
        $.ajax({
            url:"<?php echo url('courseover'); ?>",
            data:{ sid,stype:2},    
            type:'post',    
            cache:false,    
            dataType:'json',    
            success:function(data) {
                if(data.code == 200){
                    layer.msg('操作成功', {icon: 1});
                    location.reload();  // 非法操作 刷新页面
                }else{
                    layer.alert(data.mess);
                    location.reload();  // 非法操作 刷新页面
                }
            },    
            error : function() {    
                alert("异常");    
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
