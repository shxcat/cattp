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
        success: function(response, url, replace) {
            if (response.code == 1) {
                $.AMUI.pjax.display(parse_html(response.data), url, replace);
            } else if (response.code == 100) {

            } else {

            }
        },
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

    // 菜单切换
    $('#layout-menus-lists').find('> li > a').on("click", function(){
        $('#layout-menus-lists').find('> li').removeClass("am-active");
        $(this).parent().addClass("am-active");
    });
    $('#layout-menus-lists').find('.sub-menus > li > a').on("click", function(){
        $('#layout-menus-lists').find('.sub-menus > li').removeClass("am-active");
        $(this).parent().addClass("am-active");
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