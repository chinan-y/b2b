/// <reference path="jquery-1.7.1.min.js" />
$(function () {
    var landscape = '<div class="landscape"><div class="iphone"></div><div class="iphone_text">请将屏幕竖向浏览</div></div>';
    $("body").append(landscape);
    _init_phone();

    window.onresize = function () {
        _init_phone();
    }
});
function _init_phone() {
    var _width = $(window).width();
    var _height = $(window).height();
    $(".landscape").css({ width: _width, height: _height });
    if (_width > _height) {
        $(".con_box").hide();
        $(".menus").hide();
        $(".landscape").show();
        console.log("test");
    } else {
        $(".con_box").show();
        $(".menus").show();
        $(".landscape").hide();
    }
}