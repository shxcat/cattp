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
        error: function(xhr) {
        }
    });

    // 监听Pjax请求
    $.AMUI.pjax.listen({
        bind: '[data-pjax]',
        display: function(html, url) {
            var $container = $("#layout-main");
            if( $container.length ){
                $container.empty().html(html);
            }
        },
        before: function(){
            destroy_extend('#layout-main');
        },
        complete: function(){
            apply_extend('#layout-main');
        }
    });

    // 分组菜单切换
    $('.admin-menus-group').on('click', '[data-toggle-menu]', function(){
        var $this = $(this);
        var toggle = $this.data('toggle-menu');
        $('.admin-menus-group').removeClass('am-active');
        $(".admin-menus-lists").addClass("am-hide");
        $("#"+toggle).removeClass("am-hide");
        $this.parent().addClass('am-active');
        return false;
    });
});


/**
 * 初始化扩展插件
 * @param container 容器选择器
 */
function apply_extend(container) {

}

/**
 * 销毁扩展插件
 * @param container 容器选择器
 */
function destroy_extend(container) {

}