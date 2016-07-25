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

    //F5刷新当前Tab & 操作快捷键
    $(document).keydown(function(e){
        var key = e.keyCode || e.which;
        if( key == 116 ){

        }else{
            return true;
        }
        return false;
    });

    // 历史记录变化监听
    History.Adapter.bind(window, 'statechange', function(){
        var state = History.getState();
        if( state.data && state.data['state'] ){
            // 销毁扩展插件
            destroy_extend("#container");
            // 显示历史页面内容
            display_container(state.data.state, state.url);
            // 初始化插件
            apply_extend("#container");
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

    // 核心事件
    $("body")
        // PJAX请求
        .on("pjax:send", function(){
            $.AMUI.progress.start();
        })
        .on("pjax:complete", function(){
            $.AMUI.progress.done();
        })
        .on("pjax:success", function(xhr, data){
            console.log(data);
            console.log(typeof data);
        })
        .on("click", '[data-pjax]', function(event){
            $.pjax.click(event, '#layout-main');
        });
});

/**
 * Pjax请求
 * @param url
 * @param container
 */
function pjax_request(url, container){

}




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