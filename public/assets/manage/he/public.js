// 更改排序
function changesort(url, num, id) {
    $.post(url, { num: num, id: id }, function (result) {
        location.reload(true);
        // if (result.code === 1) {
        //     layer.msg(result.msg, { time: 1500, anim: 1 }, function () {
        //         window.location = result.url;
        //     });
        //     return true;
        // }

        // layer.msg(result.msg, { time: 1500, anim: 6 }, function () {
        //     // window.location = result.url;
        // });
    });
}

// ajax提交 验证之后执行提交 默认本页面
function ajaxrun(myform,url=''){
    var $mysubmit = $('#mysubmit');
    $mysubmit.attr("disabled", true);

    layer.load();

    $('#' + myform).ajaxSubmit({
        type: "post",
        dataType: "json",
        url: url,
        success: function (result) {
            $mysubmit.attr('disabled', false);
            layer.closeAll('loading');
            result.code === 1 ? $.show_success(result.msg, result.url) : $.show_error(result.msg);
        }
    });
}

// ajax提交 测试来的 验证之后执行提交 默认本页面
function ajaxrun_test(myform,url=''){
    // var $mysubmit = $('#mysubmit');
    // $mysubmit.attr("disabled", true);

    // layer.load();

    $('#' + myform).ajaxSubmit({
        type: "post",
        dataType: "json",
        url: url,
        success: function (result) {
            // $mysubmit.attr('disabled', false);
            // layer.closeAll('loading');
            result.code === 1 ? $.show_success(result.msg, result.url) : $.show_error(result.msg);
        }
    });
}

// 编辑备注信息
function changecontent(url, id, oldcomm) {
    // 获取编辑后的内容
    var newcomm = $('#' + id + 'content').val();
    // console.log(oldcomm)
    // console.log(newcomm)
    if (newcomm == oldcomm) {
        // 没修改
        return false;
    } else {
        // 询问框
        layer.confirm('备注信息已更改,是否保存', {
            btn: ['确定', '取消'] //按钮
        }, function (thislayer) {
            $.ajax({
                url,
                data: { id, comm: newcomm },
                type: 'post',
                cache: false,
                dataType: 'json',
                success: function (result) {
                    // var result = JSON.parse(result);
                    result.code === 1 ? $.show_success(result.msg, result.url) : $.show_error(result.msg);
                },
                error: function () {
                    alert("异常");
                }
            });
        }, function (thislayer) {
            layer.close(thislayer);
        });
    }
}
