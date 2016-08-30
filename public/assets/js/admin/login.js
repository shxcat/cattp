var container = 'login';
$(function(){
    // 提交表单数据
    $("form").on("submit", function(){
        var $form = $(this);
        if ($form.validator('isFormValid')) {
            var $submit = $form.find("[type=submit]");
            $.ajax({
                type: "post",
                url: $form.attr("action"),
                data: $form.serialize(),
                dataType: 'json',
                beforeSend: function(){
                    $submit
                        .attr("data-am-loading", "{spinner: 'circle-o-notch', loadingText: '登录中...'}")
                        .button("loading");
                    $.AMUI.progress.start();
                },
                success: function(res){
                    if (res.code == 1) {
                        $.AMUI.message.tips(res.msg, 'success');
                        location.href = res.url;
                    } else {
                        $("#login-captcha").click();
                        $form.find('[name=captcha]').val("");
                        $.AMUI.message.tips(res.msg, 'error');
                    }
                },
                complete: function() {
                    $submit.button("reset");
                    $.AMUI.progress.done();
                }
            });
        }
        return false;
    });

    // 验证码切换
    $("#login-captcha").on("click", function(){
        this.src = $(this).data('captcha') + '?_r=' + Math.random();
    });

    // 显示登录容器
    container = check_browser() ? 'login' : 'browser';
    show_container();

    $(window).on('resize', function(){
        show_container();
    });
});

/**
 * 显示容器内容
 */
function show_container() {
    var $container = $("#" + container + "-container");
    var top = parseInt(($(window).height() - $container.height()) / 2);
    if (top > 200) {
        top = 200;
    }
    $container.css("margin-top", top);
    $container.show();
}

/**
 * 浏览器兼容检查
 * @returns {boolean}
 */
function check_browser() {
    return !!(window.history && window.history.pushState && window.history.replaceState);
}