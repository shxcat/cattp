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
        container: '#layout-main',
        success: function(response, url, replace) {
            if (response.code == 1) {
                $.AMUI.pjax.display(parse_html(response.data), url, replace);
            } else if (response.code == 100) {

            } else {

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
        $('#layout-menus-lists').find('li').removeClass("am-active");
        $(this).parent().addClass("am-active");
    });
    $('#layout-menus-lists').find('.sub-menus > li > a').on("click", function(){
        $('#layout-menus-lists').find('.sub-menus > li').removeClass("am-active");
        $(this).parent().addClass("am-active");
    });

    // 选中菜单
    select_menus();
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

/**
 * 选中菜单
 * @returns {boolean}
 */
function select_menus() {
    var pathinfo = parse_url(location.href).path;
    if( ! pathinfo || pathinfo == "/" ){
        return false;
    }
    var $menus = $("#layout-menus-lists");
    var $dom = $menus.find("a").filter("[href='"+pathinfo+"']");
    if( $dom.length ){
        $menus.find('li').removeClass("am-active");
        if ($dom.parent().parent().is(".sub-menus")) {
            $dom.closest('.am-panel').addClass('am-active');
            $dom.parent().addClass('am-active');
        } else {
            $dom.parent().addClass('am-active');
        }
    }
}