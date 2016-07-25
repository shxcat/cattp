/**
 * 后端核心JS
 * Created by PhpStorm.
 * @version 2016-07-25 14:46:29
 * @author  cbwfree
 */

$(function(){


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