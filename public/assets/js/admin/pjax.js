/*! amazeui-pjax v0.0.1 | by cbwfree | Licensed under MIT | 2016-07-25T18:19:33+0800 */
(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(_dereq_,module,exports){
(function (global){
    'use strict';

    var $ = (typeof window !== "undefined" ? window['jQuery'] : typeof global !== "undefined" ? global['jQuery'] : null);
    var UI = (typeof window !== "undefined" ? window['AMUI'] : typeof global !== "undefined" ? global['AMUI'] : null);
    var H = (typeof window !== "undefined" ? window['History'] : typeof global !== "undefined" ? global['History'] : null);

    var pjax = pjax || {};

    pjax.defaults = {
        bind: '[data-pjax]',
        container: '#container',
        success: function(response, url, replace){},
        before: function(state){},
        complete: function(state){}
    };

    pjax.listen = function(options){
        pjax.defaults = $.extend({}, pjax.defaults, options);
        H.Adapter.bind(window, 'statechange', function(){
            var state = H.getState();
            if( state.data && state.url ){
                pjax.defaults.before(state);
                $(pjax.defaults.container)
                    .fadeOut(100, function(){
                        $(this)
                            .empty()
                            .data('pjax-url', state.url)
                            .html(state.data.state)
                            .fadeIn(100);
                    });
                pjax.defaults.complete(state);
            }
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
            success: function(response) {
                pjax.defaults.success(response, url, replace);
            }
        });
    };

    pjax.reload = function(){
        var state = H.getState();
        if( state.data && state.url ){
            pjax.request(state.url, false);
            return true;
        }
        return false;
    };

    pjax.display = function(html, url, replace){
        if(replace === false) {
            History.replaceState({state: html, rand: Math.random()}, document.title, url);
        }else{
            History.pushState({state: html, rand: Math.random()}, document.title, url);
        }
    };

    pjax.location = function() {
        return History.getState().url;
    };

    module.exports = UI.pjax = pjax;

}).call(this, typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{}]},{},[1]);
