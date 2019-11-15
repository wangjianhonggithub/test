<?php /*a:4:{s:80:"E:\PHPTutorial\WWW\dzcyiranjianshen\application\manage\view\course\coachrun.html";i:1572241173;s:71:"E:\PHPTutorial\WWW\dzcyiranjianshen\application\manage\view\layout.html";i:1572926623;s:96:"E:\PHPTutorial\WWW\dzcyiranjianshen\application\manage\view\layouts\_template\tpl_file_item.html";i:1570786084;s:95:"E:\PHPTutorial\WWW\dzcyiranjianshen\application\manage\view\layouts\_template\file_library.html";i:1570787043;}*/ ?>
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
    <!-- <?php
        dump($url);
        dump($menu);
    ?> -->
    <div class="layui-row">
        <div class="layui-col-sm12 layui-col-md12 layui-col-lg12">
			<div class="widget-head">
				<div class="widget-title"><?php if(($comm=='')): ?>新增<?php else: ?>编辑<?php endif; ?>教练信息</div>
			</div>
			<form id="myform" class="layui-form" method="post">
				<div class="widget-body layui-col-lg12"> 
                    
                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">名称 </label>
                        <div class="layui-col-sm4 layer-midd-left">
                            <input type="text" class="layui-input" name="name" value="<?php echo htmlentities($comm['name']); ?>" placeholder="请输入名称" required>
                        </div>
                        <div class="layui-form-mid layui-word-aux"></div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">教练头像 </label>
                        <div class="layui-col-sm7 layer-midd-left ">
                            <div class="layer-form-file">
                                <button type="button" class="headpath layui-btn ">
                                    <i class="layer-icon-cloud-upload"></i> 选择图片
                                </button>
                                <div class="uploader-list layer-cf">
                                    <?php if(($comm['headpath'])): ?>
                                        <div class="file-item">
                                            <a href="<?php echo htmlentities($comm['headpath']); ?>" title="点击查看大图" target="_blank"><img src="<?php echo htmlentities($comm['headpath']); ?>"></a>
                                            <i class="mdi menu-icon mdi-window-close file-item-delete layui-icon">&#x1006;</i>
                                        </div>
                                    <?php endif; ?>
                                    <input type="hidden" name="headpath" value="<?php echo isset($comm['headpath']) ? htmlentities($comm['headpath']) : ''; ?>">
                                </div>
                            </div>
                            <div class="help-block layer-margin-top-sm">
                                <small>请选择正方形的图片以保证展示效果正常</small>
                            </div>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">教练title1 </label>
                        <div class="layui-col-sm4 layer-midd-left">
                            <input type="text" class="layui-input" name="title1" value="<?php echo htmlentities($comm['title1']); ?>" placeholder="请输入教练title1" required>
                        </div>
                        <div class="layui-form-mid layui-word-aux">此字段可输入 资深教练/专业教练/新手教练</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">教练title2 </label>
                        <div class="layui-col-sm4 layer-midd-left">
                            <input type="text" class="layui-input" name="title2" value="<?php echo htmlentities($comm['title2']); ?>" placeholder="请输入教练title2" required>
                        </div>
                        <div class="layui-form-mid layui-word-aux">此字段可输入 5年经验/十年经验</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label form-require">介绍 </label>
                        <div class="layui-col-sm4 layer-midd-left">
                            <textarea name="content" required lay-verify="required" placeholder="请输入介绍" class="layui-textarea"><?php echo htmlentities($comm['content']); ?></textarea>
                        </div>
                        <div class="layui-form-mid layui-word-aux"></div>
                    </div>
                    
                    <!-- <div class="layui-form-item">
                        <label class="layui-form-label form-require">排序 </label>
                        <div class="layui-col-sm2 layer-midd-left">
                            <input type="number" class="layui-input" name="sort" value="<?php echo $comm['sort']=='' ? 100 : htmlentities($comm['sort']); ?>" placeholder="请输入排序" required>
                        </div>
                        <label class="layui-form-label">状态　</label>
                        <div>
                            <input type="radio" name="ishow" value="1" title="展示" <?php if(($comm['ishow']==1)): ?>checked<?php else: ?><?php endif; if(($comm=='')): ?>checked<?php endif; ?>>　
                            <input type="radio" name="ishow" value="2" title="隐藏" <?php if(($comm['ishow']==2)): ?>checked<?php else: ?><?php endif; ?>>
                        </div>
                        <div class="layui-form-mid layui-word-aux"></div>
                    </div> -->

					<div class="layui-form-item">
						<button id="mysubmit" type="submit" class="j-submit layui-btn">提交</button>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

<!-- 图片文件列表模板 -->
<script id="tpl-file-item" type="text/template">
    {{ each list }}
    <div class="file-item">
        <a href="{{ $value.file_path }}" title="点击查看大图" target="_blank">
            <img src="{{ $value.file_path }}">
        </a>
        <input type="hidden" name="{{ name }}" value="{{ $value.file_id}}">
        <i class="iconfont icon-shanchu file-item-delete  layui-icon">&#x1006;</i>
    </div>
    {{ /each }}
</script>



<!-- 文件库弹窗 -->
<!-- 文件库模板 -->
<script id="tpl-file-library" type="text/template">
    <div class="row">
        <div class="file-group">
            <ul class="nav-new">
                <li class="ng-scope {{ is_default ? 'active' : '' }}" data-group-id="0">
                    <a class="group-name layui-text-truncate" href="javascript:void(0);" title="全部">全部</a>
                </li>
                <li class="ng-scope" data-group-id="99999">
                    <a class="group-name" href="javascript:void(0);" title="未分组">未分组</a>
                </li>
                {{ each group_list }}
                <li class="ng-scope"  data-group-id="{{ $value.group_id }}" title="{{ $value.group_name }}">
                    <a class="group-edit" href="javascript:void(0);" title="编辑分组">
                        <i class="iconfont icon-bianji layui-icon">&#xe642;</i>
                    </a>
                    <a class="group-name" href="javascript:void(0);">
                        {{ $value.group_name }}
                    </a>
                    <a class="group-delete" href="javascript:void(0);" title="删除分组">
                        <i class="iconfont icon-shanchu1 layui-icon">&#xe640;</i>
                    </a>
                </li>
                {{ /each }}
            </ul>
            <a class="group-add" href="javascript:void(0);">新增分组</a>
        </div>
        <div class="file-list">
            <div class="v-box-header">
                <div class="h-left layui-col-flex">
					<div class="group-select">
						<button type="button" class="group-select layui-btn layer-dropdown">
							移动至 <span class="layer-icon-caret-down"></span>
						</button>
						
					   <!--  <ul class="group-list ">
							<li>请选择分组</li>
							{{ each group_list }}
							<li>
								<a class="move-file-group" data-group-id="{{ $value.group_id }}"
								   href="javascript:void(0);">{{ $value.group_name }}</a>
							</li>
							{{ /each }}
						</ul> -->
						
						<div class="layui-form-item group-list">
							<label class="layui-form-label">请选择分组</label>
							<div class="layui-input-block">
							    <select class="form-control" name="city" lay-verify="required"> 
                                    {{ each group_list }}
								        <option value="{{ $value.group_id }}" class="move-file-group" data-group-id="{{ $value.group_id }}"  href="javascript:void(0);">{{ $value.group_name }}</option>
							        {{ /each }}
							    </select>
							</div>
						</div>
					</div>
						
					<div class="h-rigth layer-fl layui-input-block">
						<div class="j-upload upload-image">
							<i class="iconfont icon-add1"></i>
							上传图片
						</div>
					</div>
					<div class="tpl-table-black-operation layer-fl">
                        <a href="javascript:void(0);" class="layui-btn-warm file-delete tpl-table-black-operation-del" data-group-id="2">
                            <i class="mdi menu-icon mdi-delete-forever"></i> 删除
                        </a>
                    </div>
                </div>	
            </div>
            <div id="file-list-body" class="v-box-body">
                {{ include 'tpl-file-list' file_list }}
            </div>
            <div class="v-box-footer"></div>
        </div>
    </div>

</script>

<!-- 文件列表模板 -->
<script id="tpl-file-list" type="text/template">
    <ul class="file-list-item">
        {{ include 'tpl-file-list-item' data }}
    </ul>
    {{ if last_page > 1 }}
    <div class="file-page-box">
        <ul class="pagination">
            {{ if current_page > 1 }}
            <li>
                <a class="switch-page" href="javascript:void(0);" title="上一页" data-page="{{ current_page - 1 }}">«</a>
            </li>
            {{ /if }}
            {{ if current_page < last_page }}
            <li>
                <a class="switch-page" href="javascript:void(0);" title="下一页" data-page="{{ current_page + 1 }}">»</a>
            </li>
            {{ /if }}
        </ul>
    </div>
    {{ /if }}
</script>

<!-- 文件列表模板 -->
<script id="tpl-file-list-item" type="text/template">
    {{ each $data }}
    <li class="ng-scope" title="{{ $value.file_name }}" data-file-id="{{ $value.id }}"
        data-file-path="{{ $value.file_path }}">
        <div class="img-cover"
             style="background-image: url('{{ $value.file_path }}')">
        </div>
        <p class="layui-word-aux">{{ $value.file_name }}</p>
        <div class="select-mask">
            <img src="assets/manage/img/chose.png">
        </div>
    </li>
    {{ /each }}
</script>

<!-- 分组元素-->
<script id="tpl-group-item" type="text/template">
    <li class="ng-scope" data-group-id="{{ group_id }}" title="{{ group_name }}">
        <a class="group-edit" href="javascript:void(0);" title="编辑分组">
            <i class="iconfont icon-bianji"></i>
        </a>
        <a class="group-name layer-text-truncate" href="javascript:void(0);">
            {{ group_name }}
        </a>
        <a class="group-delete" href="javascript:void(0);" title="删除分组">
            <i class="iconfont icon-shanchu1"></i>
        </a>
    </li>
</script>
<script id="tpl-file-item" type="text/template">
    {{ each list }}
        <div class="file-item">
            <a href="{{ $value.file_path }}" title="点击查看大图" target="_blank">
                <img src="{{ $value.file_path }}">
            </a>
            <input type="hidden" name="{{ name }}" value="{{ $value.file_id}}">
            <i class="iconfont icon-shanchu file-item-delete"></i>
        </div>
    {{ /each }}
</script>


<script>
$(function () {
    // 选择图片
    $('.headpath').selectImages({
        name: 'headpath'
    });


    $('#myform').submit(function () {
        // 执行表单验证
        var headpath = $("input[name='headpath']").val();
        if(headpath == ''){
            layer.msg('请上传教练头像', function () { });return false;
        }
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
