/**
 * 后端核心JS
 * Created by PhpStorm.
 * @version 2016-07-25 14:46:29
 * @author  cbwfree
 */
$(function(){
    // 全局Ajax配置
    $.ajaxSetup({
        timeout: 45000,
        dataType:'json',
        error: function(xhr, type) {
            var msg = [];
            msg.push('[Error Code: '+xhr.status+']');
            msg.push(xhr.statusText);
            $.AMUI.message.error(msg.join('&nbsp;'), '请求出错');
        }
    });

    // 弹出层初始化
    layer.config({ scrollbar: false, shift: 1, moveType: 1, closeBtn: false, maxWidth: 540 });

    // 监听Pjax请求
    $.AMUI.pjax.listen({
        success: function(response, replace) {
            if (response.code == 1) {
                $.AMUI.pjax.display({
                    url: response.url,
                    title: '',
                    html: parse_html(response.data),
                    node: $("#layout-menus-lists .menu-item.am-active > a").attr("href") || response.url
                }, replace);
            } else if (response.code == 100) {
                $.AMUI.message.warning(response.msg, '登录失效', function(){
                    location.href = response.data;
                });
            } else {
                $.AMUI.message.error(response.msg);
            }
        },
        error: function(type, xhr, url) {
            var html = [];
            html.push('<ol id="location" class="am-breadcrumb am-breadcrumb-slash">');
            html.push('<li><i class="am-icon-home"></i> 首页</li>');
            html.push('<li class="am-active">'+ xhr.statusText + ' ' + xhr.status + '</li>');
            html.push('<li class="am-fr"><a href="javascript:;" onclick="$.AMUI.pjax.reload();" title="刷新"><i class="am-icon-refresh"></i>刷新(F5)</a></li>');
            html.push('<li class="am-fr"><a href="javascript:;" onclick="window.open($.AMUI.pjax.location());" title="新窗口打开"><i class="am-icon-external-link"></i>新窗口打开</a></li>');
            html.push('</ol>');
            html.push('<iframe id="container" name="container" style="padding:0;width:100%;"></iframe>');
            $.AMUI.pjax.display({
                url: url,
                title: '',
                html: html.join(''),
                node: $("#layout-menus-lists .menu-item.am-active > a").attr("href") || url
            }, true);
            container.document.write(xhr.responseText);
        },
        before: function(){
            destroy_extend('#layout-main');
        },
        complete: function(){
            apply_extend('#layout-main');
        }
    });

    // 菜单切换
    $("#layout-nav").on("click", "a", function(){
        var $this = $(this);
        var $group = $($(this).data('groupMenus'));
        $('#layout-menus-lists').find("> ul").addClass("am-hide");
        $group.removeClass("am-hide").addClass("am-animation-slide-right").one($.AMUI.support.animation.end, function(){
            $group.removeClass("am-animation-slide-right");
        });
        $("#layout-nav").find("li").removeClass("am-active");
        $this.parent().addClass("am-active");
    });
    $('#layout-menus-lists').on("click", 'a', function(){
        $('#layout-menus-lists').find('li').removeClass("am-active");
        $(this).closest('.menu-group').addClass("am-active");
        $(this).closest('.menu-item').addClass("am-active");
    });

    // 核心事件
    $("body")
        // 安全退出
        .on("click", ".safe-exit", function(){
            var _this = this;
            $.AMUI.message.confirm("您确认要退出登录吗?", "安全退出", function(ok){
                if (ok) {
                    location.href = _this.href;
                }
            });
            return false;
        })
        // Ajax请求
        .on("click", "[data-request]", function(){
            var $this = $(this);
            var url = $this.attr("href") || $this.data('url');
            $.get(url, function(response){
                var type = 'error';
                if (response.code == 1) {
                    type = 'success';
                    $.AMUI.pjax.redirect(response['url'], $this.data('reload') || false);
                }
                $.AMUI.message.tips(response.msg, type);
            });
        })
        // 操作确认
        .on("click", "[data-confirm]", function(){
            var $this = $(this);
            var confirm = $this.data('confirm') || '您确认要执行此操作吗?';
            $.AMUI.message.confirm(confirm, "提示", function(ok){
                if (ok) {
                    var url = $this.attr("href") || $this.data('url');
                    $.get(url, function(response){
                        var type = 'error';
                        if (response.code == 1) {
                            type = 'success';
                            $.AMUI.pjax.redirect(response['url'], $this.data('reload') || false);
                        }
                        $.AMUI.message.tips(response.msg, type);
                    });
                }
            });
            return false;
        })
        // 密码输入框扩展
        .on("click", ".am-form-password .am-password-icon", function(){
            var $this = $(this);
            var $password = $this.prev();
            if ($password.is("[type=password]")) {
                $password.attr("type", "text");
                $this.removeClass('am-icon-eye').addClass('am-icon-eye-slash');
            } else {
                $password.attr("type", "password");
                $this.removeClass('am-icon-eye-slash').addClass('am-icon-eye');
            }
        })
        // 重置表单
        .on("reset", "[data-validator]", function(){
            $(this).validator('destroy');
            $(this).validator();
        })
        // 提交表单
        .on("submit", "[data-ajax-submit]", function(){
            var $form = $(this);
            var $submit = $form.find("[type=submit]");
            var $button = $form.find(".am-btn:not([type=submit])");
            $.ajax({
                type: $form.attr("method") || "post",
                url: $form.attr("action"),
                data: $form.serialize(),
                dataType: 'json',
                beforeSend: function(){
                    $submit
                        .attr("data-am-loading", "{spinner: 'circle-o-notch', loadingText: '加载中...'}")
                        .button("loading");
                    $button.attr("disabled", true);
                    $.AMUI.progress.start();
                },
                success: function(res){
                    if (res.code == 1) {
                        $.AMUI.message.tips(res.msg, 'success');
                        $.AMUI.pjax.redirect(res['url']);
                    } else {
                        $.AMUI.message.tips(res.msg, 'error');
                    }
                },
                complete: function() {
                    $submit.button("reset");
                    $button.attr("disabled", false);
                    $.AMUI.progress.done();
                }
            });
            return false;
        });

    validate();

    // 选中菜单
    select_menus();
    apply_extend('#layout-main');
});


/**
 * 初始化扩展插件
 * @param container 容器选择器
 */
function apply_extend(container) {
    var $container = $(container);

    // 启用表单验证
    $container.find("[data-validator]").each(function(){
        $(this).validator();
    });

    // 初始化日期选择
    $container.find("[data-datetime]").each(function(){
        var data = $(this).data();
        $(this).datetimepicker({
            showClose: true,
            showClear: true,
            showTodayButton: true,
            widgetParent: 'body',
            format: data['datetime'] || 'YYYY-MM-DD HH:mm:ss',
            minDate: data['min'] || false,      // 起始日期范围
            maxDate: data['max'] || false,      // 结束日期范围
            disabledDates: data['disabled'] || false,   // 不可选择日期
            enabledDates: data['enabled'] || false      // 仅可选择日期
        });
    });

    // 初始化 Select2 下拉框
    $container.find("[data-select]").each(function(){
        var $this = $(this);
        var remote = $this.data("remote");
        var options = {};
        if (remote) {
            options.ajax = {
                url: remote,
                dataType: 'json',
                delay: 250,
                cache: true,
                data: function (params) {
                    return {query: params.term};
                },
                processResults: function (data) {
                    return {results: data.data || []};
                }
            }
        }
        $(this).select2(options);
    });

    console.log('[extends] apply ...');
}

/**
 * 销毁扩展插件
 * @param container 容器选择器
 */
function destroy_extend(container) {
    var $container = $(container);

    // 销毁表单验证
    $container.find("[data-validator]").each(function(){
        $(this).validator('destroy');
    });

    // 销毁日期组件
    $container.find("[data-datetime]").each(function(){
        var DateTimePicker = $(this).data('DateTimePicker');
        if( DateTimePicker ){
            DateTimePicker.destroy();
        }
    });

    // 销毁 Select2 下啦框
    $container.find("[data-select]").each(function(){
        $(this).select2('destroy');
    });

    console.log('[extends] destroy ...');
}

/**
 * 选中菜单
 * @returns {boolean}
 */
function select_menus() {
    var url = (history.state && history.state['node']) || location.href;
    var pathinfo = parse_url(url).path;
    if( ! pathinfo || pathinfo == "/" ){
        return false;
    }
    var $nav = $("#layout-nav");
    var $menus = $("#layout-menus-lists");
    var $dom = $menus.find("a").filter("[href='"+pathinfo+"']:first");
    if( $dom.length ){
        var $group = $dom.closest('.group-menus');
        $nav.find("li").removeClass('am-active');
        $menus.find("> ul").addClass("am-hide");
        $menus.find('li').removeClass("am-active");
        $dom.closest('.sub-menus').addClass('am-in');
        $dom.closest('.menu-group').addClass("am-active");
        $dom.closest('.menu-item').addClass("am-active");
        $group.removeClass("am-hide");
        $nav.find("[data-group-menus='#"+$group.attr("id")+"']").parent().addClass("am-active");
    }
}

/**
 * 分页跳转
 * @returns {boolean}
 */
function page_redirect(){
    $.AMUI.pjax.request(build_url(location.href, $("#page-paging").find("input,select").serialize()));
    return false;
}

/**
 * 页面搜索
 * @returns {boolean}
 */
function page_search(){
    $.AMUI.pjax.request(build_url(location.href, $("#page-search").serialize()));
    return false;
}

/**
 * 表单验证扩展
 */
function validate() {
    //noinspection JSUnresolvedVariable
    if ($.AMUI && $.AMUI.validator) {
        $.AMUI.validator.patterns.mobile = /^\s*1\d{10}\s*$/;                       // 手机号
        $.AMUI.validator.patterns.chinese = /[\u4e00-\u9fa5]/;                      // 中文
        $.AMUI.validator.patterns.zipcode = /^\d{6}$/;                              // 邮编
        $.AMUI.validator.patterns.phone = /^(\d{3}-|\d{4}-)(\d{8}|\d{7})$/;         // 座机
    }
}