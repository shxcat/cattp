/*! amazeui-pjax v0.0.1 | by cbwfree | Licensed under MIT | 2016-07-25T18:19:33+0800 */
(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(_dereq_,module,exports){
(function (global){
    'use strict';

    var $ = (typeof window !== "undefined" ? window['jQuery'] : typeof global !== "undefined" ? global['jQuery'] : null);
    var UI = (typeof window !== "undefined" ? window['AMUI'] : typeof global !== "undefined" ? global['AMUI'] : null);

    var pjax = pjax || {};

    pjax.defaults = {
        bind: '[data-pjax]',
        render: '#layout-main',             // 渲染输出容器
        container: '#container',            // 动画容器
        animation: [
            'am-animation-slide-right',     // 右侧划入
            'am-animation-slide-bottom',    // 底部划入
            'am-animation-slide-top'        // 顶部划入
        ],
        error: function(xhr){},
        success: function(response, url, replace){},
        before: function(html){},
        complete: function(){}
    };

    pjax.listen = function(options){
        pjax.defaults = $.extend({}, pjax.defaults, options);
        $(window).on('popstate', function(){
            pjax.render(window.history.state.html);
        });
        $("body").on("click", pjax.defaults.bind, function(){
            var $this = $(this);
            pjax.request($this.data('pjax') || $this.attr('href'));
            return false;
        });
        //F5刷新当前Tab & 操作快捷键
        $(document).keydown(function(e){
            var key = e.keyCode || e.which;
            if( key == 116 ){
                return ! pjax.reload();
            }
        });
    };

    pjax.request = function(url, replace){
        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
            headers: { "X-PJAX": true },
            beforeSend: function(){
                UI.progress.start();
            },
            complete: function(){
                UI.progress.done();
            },
            error: function(xhr, type){
                pjax.defaults.error(type, xhr, url);
            },
            success: function(response) {
                response = response || {};
                response.url = url;
                response.replace = replace;
                pjax.defaults.success(response);
            }
        });
    };

    pjax.reload = function(){
        var url = window.history.state.url || window.location.href;
        pjax.request(url, false);
    };

    pjax.render = function(html){
        var $render = $(pjax.defaults.render);
        pjax.defaults.before(html);
        $render.empty().html(html);
        if (pjax.defaults.animation) {
            var $container = $("#container");
            var animation = pjax.defaults.animation[Math.floor((Math.random() * pjax.defaults.animation.length))];
            $container
                .addClass(animation)
                .one(UI.support.animation.end, function() {
                    $container.removeClass(animation);
                });
        }
        pjax.defaults.complete();
    };

    pjax.display = function(state){
        state = state || {};
        state['_rand'] = Math.random();
        if(state.replace === false) {
            window.history.replaceState(state, state.title || document.title, state.url);
        }else{
            window.history.pushState(state, state.title || document.title, state.url);
        }
        pjax.render(state.html);
    };

    pjax.location = function() {
        return window.history.state.url;
    };

    module.exports = UI.pjax = pjax;

}).call(this, typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{}]},{},[1]);
