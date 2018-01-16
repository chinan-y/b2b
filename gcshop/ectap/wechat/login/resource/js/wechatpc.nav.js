/**
 * Created by lihuibiao on 15/7/26.
 */
$(function(){
    var parseQuery = function() {
        var match;
        var pl     = /\+/g;
        var search = /([^&=]+)=?([^&]*)/g;
        var decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); };
        var query  = window.location.search.substring(1);

        var urlParams = {};
        while (match = search.exec(query))
            urlParams[decode(match[1])] = decode(match[2]);
        return urlParams;
    }

    var title = $('.item-title h3').text();
    if (title != '账号同步') {
        return;
    }

    var query = parseQuery();
    if (query.gct == 'account' && query.gp != 'wechatpc') {
        $('.tab-base').append('<li><a href="index.php?gct=account&gp=wechatpc"><span>PC端微信登陆</span></a></li>');
    } else {
        $('.tab-base').append('<li><a class="current" href="javascript:;"><span>PC端微信登陆</span></a></li>');
    }
});